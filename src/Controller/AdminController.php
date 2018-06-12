<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 04/05/2018
 * Time: 18:33
 */

namespace App\Controller;


use App\Controller\Exception\WrongCSRFTokenNameException;
use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\ScreeningDay;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends Controller
{
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findAll();

        $waitingMovies = array();
        $selectedMovies = array();
        $eliminatedMovies = array();

        /* @var $movie Movie */
        foreach ($movies as $movie) {
            if ($movie->getShortlisted() === null) {
                $waitingMovies[] = $movie;
            } else if ($movie->getShortlisted()) {
                $selectedMovies[] = $movie;
            } else {
                $eliminatedMovies[] = $movie;
            }
        }

        return $this->render("admin/movieList.html.twig", array(
            "user" => $this->getUser(),
            "waitingMovies" => $waitingMovies,
            "selectedMovies" => $selectedMovies,
            "eliminatedMovies" => $eliminatedMovies
        ));
    }

    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $response = new Response();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');
        // Set ETag
        $eTag = $this->getParameter("version");
        $response->setEtag($eTag, true);
        $response->setPublic();

        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($request)) {
            // return the 304 Response immediately
            return $response;
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("admin/login.html.twig", array(
            "last_username" => $lastUsername,
            "error" => $error,
        ));
    }

    public function movieDetails(Request $request, $movieId)
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repository->find($movieId);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            // Set movie and user
            $comment->setMovie($movie);
            $comment->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            //Reload the page to clear form and prevent multiple send by refreshing the page
            return $this->redirect($request->getUri());
        }

        return $this->render("admin/movieDetails.html.twig", array(
            "commentForm" => $form->createView(),
            "user" => $this->getUser(),
            "movie" => $movie
        ));
    }

    public function deleteComment($movieId, $commentId)
    {
        $repository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $repository->find($commentId);
        $userId = $this->getUser()->getId();

        if ($comment != null) {
            if ($comment->getUser()->getId() == $userId && $comment->getMovie()->getId() == $movieId) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($comment);
                $entityManager->flush();
            }
        }

        //TODO return an error if th user or the movie doesn't match
        return $this->redirect($this->generateUrl("movieDetails", array("movieId" => $movieId)));
    }

    public function accountSettings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get("changePassword")->getData() && $user->getPlainPassword() != null) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            }

            $entityManager->flush();

            $this->addFlash(
                "flashMessageModal",
                ""
            );

            return $this->redirectToRoute("accountSettings");
        }

        return $this->render("admin/accountSettings.html.twig", array(
            "form" => $form->createView(),
            "user" => $this->getUser(),
            "changePassword" => $form->get("changePassword")->getData()
        ));
    }

    public function deleteCurrentAccount(TokenStorageInterface $tokenStorage)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

        $tokenStorage->setToken(null);
        $this->get("session")->invalidate();

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("homepage");
    }

    public function shortlistMovie($movieId, $value)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $movie = $entityManager->getRepository(Movie::class)->find($movieId);

        if ($value == "true") {
            $movie->setShortlisted(true);
        } elseif ($value == "false") {
            $movie->setShortlisted(false);
        } elseif ($value == "null") {
            $movie->setShortlisted(null);
        }

        $entityManager->flush();
        return $this->redirectToRoute("admin");
    }

    public function adminSchedule(Request $request)
    {
        $response = new Response();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        $entityManager = $this->getDoctrine()->getManager();
        $screeningDayRepository = $entityManager->getRepository(ScreeningDay::class);
        $movieRepository = $entityManager->getRepository(Movie::class);

        // Handle POST

        // Create form
        $postData = array();
        $screeningDays = $screeningDayRepository->findBy(array(), array("date" => "ASC")); // Retrieve screening days in the right order
        $formBuilder = $this->createFormBuilder($postData)
            ->add("day_0", HiddenType::class, array(
                "attr" => array("id" => "day_0")
            ));
        /* @var $screeningDay ScreeningDay */
        foreach ($screeningDays as $screeningDay) {
            $formBuilder->add("day_" . $screeningDay->getId(), HiddenType::class, array(
                "attr" => array("id" => "day_" . $screeningDay->getId())
            ));
        }
        $formBuilder->add("saveChanges", SubmitType::class, array(
            "attr" => array(
                "class" => "btn btn-primary"
            )
        ));
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        // If form submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $postData = $form->getData();

            // Set all unscreened shorts
            foreach (explode(",", $postData["day_0"]) as $movieIdStr) {
                // Remove the "item_" in the id
                $movieId = substr($movieIdStr, 5);
                $movie = $movieRepository->find($movieId);
                if ($movie != null) {
                    $movie->setScreeningDay(null);
                    $movie->setScreeningDayIndex(null);
                }
            }
            // Remove unscreened shorts from the post data
            array_splice($postData, 0, 1);

            // Set all shorts into their screening day with the right index
            foreach ($postData as $screeningDayIdStr => $scheduleStr) {
                // Retrieve the screening day and clear the screened movie list, the substr() is use to get rid of the "day_" in the screeningDayIdStr
                $screeningDay = $screeningDayRepository->find(substr($screeningDayIdStr, 4));
                $screeningDay->clearScreenedMovies();
                // Iterate over the retrieved string to set screening day and index
                foreach (explode(",", $scheduleStr) as $index => $movieIdStr) {
                    // Remove the "item_" in the id
                    $movieId = substr($movieIdStr, 5);
                    $movie = $movieRepository->find($movieId);
                    if ($movie != null) {
                        $movie->setScreeningDay($screeningDay);
                        $movie->setScreeningDayIndex($index);
                    }
                }
            }
            // Flush changes
            $entityManager->flush();
            return $this->redirectToRoute("adminSchedule");
        }

        // Sort screening day movie list and compute eTag
        $eTag = "";
        /* @var $screeningDay ScreeningDay */
        foreach ($screeningDays as $screeningDay) {
            $entityManager->detach($screeningDay);
            $screeningDay->clearScreenedMovies();
            $sortedMovies = $movieRepository->findBy(array("screeningDay" => $screeningDay->getId()), array("screeningDayIndex" => "ASC"));
            // If there's no screenedMovie this day, add 0
            if ($sortedMovies == null) {
                $eTag .= "0";
            } else { // Else just concat movie ids
                /* @var $movie Movie */
                foreach ($sortedMovies as $movie) {
                    $entityManager->detach($movie);
                    $screeningDay->addScreenedMovie($movie);
                    $eTag .= $movie->getId();
                }
            }
        }
        $eTag .= "_" . $this->getParameter("version"); // Concat website version to eTag
        $response->setEtag($eTag, true);
        $response->setPublic();

        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($request)) {
            // return the 304 Response immediately
            return $response;
        }

        // Else finish rendering

        $selectedMovies = $movieRepository->findBy(array(
            "shortlisted" => true,
            "screeningDay" => null
        ));

        return $this->render("admin/adminSchedule.html.twig", array(
            "user" => $this->getUser(),
            "form" => $form->createView(),
            "selectedMovies" => $selectedMovies,
            "screeningDays" => $screeningDays
        ));
    }

    /*
     * This function generate a CSRF Token and render a hidden field with the token inside.
     * Use this on form that aren't handle by Symfony form to protect them against CSRF attack.
     * Don't forget to use $this->isCsrfTokenValid('token_name', $submittedToken) to check if the token submitted is the right one
     */
    public function generateCSRFToken($tokenName)
    {
        if (!is_string($tokenName)) {
            throw new WrongCSRFTokenNameException();
        }
        return $this->render('admin/security/generateCSRFTokenField.html.twig', array(
            "tokenName" => $tokenName
        ));
    }

    /*
     * This function simply render a hidden token field form form. Use this instead of generateCSRFToken() if the form is completely handle by Symfony.
     */
    public function renderCSRFToken($tokenValue)
    {
        return $this->render('admin/security/renderCSRFTokenField.html.twig', array(
            "tokenValue" => $tokenValue
        ));
    }
}