<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function createProduct(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = new Users();
        $user->setUserID('1');
        $user->setFirstName('Fran');
        $user->setLastName('Grenko');
        $user->setMail('fran.grenko@gmail.com');

        $entityManager->persist($user);

        $entityManager->flush();

        return new Response('Saved new user with id' .$user->getId());


    }
}
