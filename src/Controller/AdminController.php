<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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
     * @Route("/admin/manage_users/modify/{uid}", name="modify_user",
     *     requirements={"uid"="\d+"}, methods={"POST"})
     */

    public function modify_user(Request $req, int $uid) : Response
    {
        $man = $this->getDoctrine()->getManager();
        $rep = $man->getRepository(User::class);
        $user = $rep->find($uid);

        if ($req->isMethod("POST")) {

            $user->setMail($req->request->get('username'));
            $user->setFirstName($req->request->get('firstname'));
            $user->setLastName($req->request->get('lastname'));

            if (!empty($req->request->get('password')))
                $user->setPassword($this->passwordEncoder->
                encodePassword($user, $req->request->get('password')));

            // TODO: Allow administrators to grant or revoke administrator privileges
            /*if ($req->request->get('admin_role') == 'on')
                $user->setRole('ROLE_ADMIN');
            else if (array_key_exists('ROLE_ADMIN', $user->getRoles()))
                $user->revokeRole('ROLE_ADMIN');*/

            $man->flush();

            $this->addFlash('success', 'Record successfully updated!');
        }

        return $this->redirectToRoute('manage_users');
    }

    /**
     * @Route("/admin/manage_users/delete/{uid}", name="delete_user",
     *     requirements={"uid"="\d+"})
     */

    public function delete_user(Request $req, int $uid) : Response
    {
        $man = $this->getDoctrine()->getManager();
        $rep = $man->getRepository(User::class);
        $user = $rep->find($uid);

        if ($req->isMethod("GET")) {

            $man->remove($user);
            $man->flush();

            $this->addFlash('success', 'User deleted!');
        }

        return $this->redirectToRoute('manage_users');
    }

    /**
     * @Route("/admin/manage_users/add", name="add_user", methods={"POST"})
     */

    public function add_user(Request $req) : Response
    {
        $man = $this->getDoctrine()->getManager();
        $rep = $man->getRepository(User::class);
        $user = new User();

        if ($req->isMethod("POST")) {

            $user->setMail($req->request->get('username'));
            $user->setFirstName($req->request->get('firstname'));
            $user->setLastName($req->request->get('lastname'));
            $user->setPassword($this->passwordEncoder->
            encodePassword($user, $req->request->get('password')));

            // TODO: Allow administrators to grant or revoke administrator privileges
            /*if ($req->request->get('admin_role') == 'on')
                $user->setRole('ROLE_ADMIN');
            else if (array_key_exists('ROLE_ADMIN', $user->getRoles()))
                $user->revokeRole('ROLE_ADMIN');*/

            $man->persist($user);
            $man->flush();

            $this->addFlash('success', 'User added!');
        }

        return $this->redirectToRoute('manage_users');
    }
}
