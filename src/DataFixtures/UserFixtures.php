<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName("Ivan");
        $user->setLastName("Horvat");
        $user->setMail("ihorvat@example.com");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole('ROLE_ADMIN');

        $user2 = new User();
        $user2->setFirstName("John");
        $user2->setLastName("Smith");
        $user2->setMail("jsmith@example.com");
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'abc123'));

        $user3 = new User();
        $user3->setFirstName("Pero");
        $user3->setLastName("Perić");
        $user3->setMail("pperic@example.com");
        $user3->setPassword($this->passwordEncoder->encodePassword($user3, 'abc123'));
        $user3->setRole('ROLE_ADMIN');

        $user4 = new User();
        $user4->setFirstName("John");
        $user4->setLastName("Doe");
        $user4->setMail("jdoe@example.com");
        $user4->setPassword($this->passwordEncoder->encodePassword($user4, 'pass123'));

        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);

        $manager->flush();
    }
}
