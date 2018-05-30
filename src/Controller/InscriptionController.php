<?php

namespace App\Controller;

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
        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();

            // Check if nominate actor was checked and remove any actor if not
            if (!$form->get("nominateActor")->getData()) {
                $movie->setActor(null);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute("homepage");
        }

        return $this->render('website/inscription.html.twig', [
            "form" => $form->createView(),
            "mobileForm" => $form->createView(),
            "nominateActor" => $form->get("nominateActor")->getData()
        ]);
    }
}
