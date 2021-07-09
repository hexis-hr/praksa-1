<?php

namespace App\Controller;

use App\Entity\User;
use Elasticsearch\Endpoints\AsyncSearch\Submit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/manage_users", name="manage_users")
     */

    public function manage_users(): Response
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
        $users = $rep->findAll();
        $cur_user_id = $this->getUser()->getUserID();

        return $this->render('admin/manage_users.html.twig' ,
            ['users' => $users, 'cur_user_id' => $cur_user_id]);
    }

    // TODO: Implement function to modify user details
    /**
     * @Route("/admin/manage_users/modify/{uid}", name="modify_users",
     *     requirements={"uid"="\d+"})
     */

    /*public function modify_user(Request $req, int $uid) : Response
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
        $user = $rep->find($uid);

        if ($req->isMethod("POST")) {

            return new Response(var_dump($req->getContent()));
            //$user->setMail($req->request->get('username'));
            //$user->setFirstName($req->request->get('modify_form')['firstname']);
            //$user->setLastName($req->request->get('modify_form')['lastname']);
        }

        return new RedirectResponse($this->generateUrl("manage_users"));
    }*/
}
