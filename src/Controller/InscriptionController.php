<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Movie;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends Controller
{
    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $movie = new Movie();
        // FIXME: handle the current festival instead of 2
        $movie->setFestival($entityManager->getRepository(Festival::class)->find(2));

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();

            // Check if nominate actor was checked and remove any actor if not
            if (!$form->get("nominateActor")->getData()) {
                $movie->setActor(null);
            }

            $entityManager->persist($movie);
            $entityManager->flush();

            $this->addFlash(
                "flashMessageModal",
                ""
            );

            return $this->redirectToRoute("homepage");
        }

        return $this->render('website/inscription.html.twig', [
            "form" => $form->createView(),
            "mobileForm" => $form->createView(),
            "nominateActor" => $form->get("nominateActor")->getData()
        ]);
    }
}
