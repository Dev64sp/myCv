<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $User = new User();
            $User->setFirstName('User_firstname '.$i);
            $User->setLastName('User_lastname '.$i);
            $User->setFullname('User_firstname '.$i.'User_lastname '.$i);
            $User->setPassword($this->hasher->hashPassword($User, 'Password'.$i));
            $User->setEmail('email'.$i.'@gmail.com');
            $User->setRoles(array('role'=>'user'));
            $User->setCreatedAt(date_create_immutable_from_format('Y-m-d', date('Y-m-d')));
            
            $manager->persist($User);

        }    
        
        for ($i = 0; $i < 2; $i++) {
            $User = new User();
            $User->setFirstName('User_firstname '.$i);
            $User->setLastName('User_lastname '.$i);
            $User->setFullname('User_firstname '.$i.'User_lastname '.$i);
            $User->setPassword($this->hasher->hashPassword($User, 'Password'.$i));
            $User->setEmail('email'.$i.'@gmail.com');
            $User->setRoles(array('role'=>'admin'));
            $User->setCreatedAt(date_create_immutable_from_format('Y-m-d', date('Y-m-d')));
            
            $manager->persist($User);

        }
            $manager->persist($User);



        $manager->flush();

    }
}
