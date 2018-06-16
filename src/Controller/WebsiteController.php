<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 22/03/2018
 * Time: 17:47
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\ScreeningDay;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class WebsiteController extends Controller
{
    public function homepage()
    {
        return $this->render("website/homepage.html.twig");
    }

    public function schedule()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $screeningDayRepository = $entityManager->getRepository(ScreeningDay::class);
        $movieRepository = $entityManager->getRepository(Movie::class);
        $screeningDays = $screeningDayRepository->findBy(array(), array("date" => "ASC")); // Retrieve screening days in the right order
        // Then sort movies
        /* @var $screeningDay ScreeningDay*/
        foreach ($screeningDays as $screeningDay) {
            $entityManager->detach($screeningDay);
            $screeningDay->clearScreenedMovies();
            $sortedMovies = $movieRepository->findBy(array("screeningDay" => $screeningDay->getId()), array("screeningDayIndex" => "ASC"));

            if ($sortedMovies != null) {
                /* @var $movie Movie */
                foreach ($sortedMovies as $movie) {
                    $entityManager->detach($movie);
                    $screeningDay->addScreenedMovie($movie);
                }
            }
        }

        return $this->render("website/schedule.html.twig", array(
            "screeningDays" => $screeningDays
        ));
    }

    public function registrationRules()
    {
        return $this->file(new File("files/registration_rules.pdf"), "Règlement d'inscription.pdf", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function legalNotices()
    {
        return $this->render("website/legal-notices.html.twig");
    }
}