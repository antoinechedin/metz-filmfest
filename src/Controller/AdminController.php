<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 04/05/2018
 * Time: 18:33
 */

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("login.html.twig", array(
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
}