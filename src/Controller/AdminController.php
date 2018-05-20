<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 04/05/2018
 * Time: 18:33
 */

namespace App\Controller;


use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends Controller
{
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findAll();

        return $this->render("admin.html.twig", array(
            "user" => $this->getUser(),
            "movies" => $movies
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
            "error"         => $error,
        ));
    }
}