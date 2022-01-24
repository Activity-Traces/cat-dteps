<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $user = new User();

        // $product = new Product();
        $user->setPassword($this->passwordEncoder->encodePassword(
                         $user,
                         'the_new_password'
                     ));

         $manager->persist($user);


        $manager->flush();
    }
}
