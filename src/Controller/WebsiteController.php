<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 22/03/2018
 * Time: 17:47
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class WebsiteController extends Controller
{
    public function homepage()
    {
        return $this->render("website/homepage.html.twig");
    }

    public function registrationRules()
    {
        return $this->file(new File("files/registration_rules.pdf"), "Règlement d'inscription.pdf", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function legalNotices()
    {
        return $this->render("legal-notices.html.twig");
    }
}