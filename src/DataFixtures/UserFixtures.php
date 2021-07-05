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

        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }
}
