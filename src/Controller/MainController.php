<?php

namespace App\Controller;

use PhpParser\Node\Scalar\MagicConst\Dir;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{
    /**
     * @Route("/login1", name="login")
     */
    public function index(): Response
    {
        return $this->render('login.html.twig');
    }
    /**
     * @Route("/", name="main")
     */
    public function main(): Response
    {

        return $this->render('home.html.twig');

    }
    /**
     * @Route("/success", name="app_homepage")
     */

    public function afterLogin(){
        require __DIR__ . '../Functions/start.php';
        return new Response('Sve je dobro.');
    }
    /**
     * @Route("/google", name="app_homepage")
     */

    public function google(){
        return $this->render('test.html.twig');
    }
}
