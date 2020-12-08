<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load (ObjectManager $manager) {
        $user = new Users();
        $user->setEmail();
        $user->setPasswordDigest($this->encoder->encodePassword($user,'password'));
        $manager->persist($user);

        $manager->flush();
    }
}