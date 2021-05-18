<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MainController extends AbstractController
{
    /**
     * @Route("/test", name="main")
     */
    public function index(): Response
    {
        return $this->render('test.html.twig');
    }
    /**
     * @Route("/main", name="main1")
     */
    public function main(){
        $entityManager = $this->getDoctrine()->getManager();

        return new Response('Main');
    }
    /**
     * @Route("/success", name="app_homepage")
     */

    public function afterLogin(){
        return new Response('Sve je dobro.');
    }
}
