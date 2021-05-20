<?php

namespace App\Controller;

use PhpParser\Node\Scalar\MagicConst\Dir;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        return $this->render('login.html.twig');
    }
    /**
     * @Route("/main", name="main1")
     */
    public function main(): Response
    {

        return $this->render('login.html.twig');

    }
    /**
     * @Route("/success", name="app_homepage")
     */

    public function afterLogin(){
        require __DIR__.'/../start.php';
        return new Response('Sve je dobro.');
    }
}
