<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $admin_user = new User();
        $admin_user->setLogin('aymeric');
        $admin_user->setPassword($this->passwordEncoder->encodePassword(
            $admin_user,
            'admin1234'
        ));
        $admin_user->setRoles(array('ROLE_SUPER_ADMIN'));
        $manager->persist($admin_user);

        $classic_user = new User();
        $classic_user->setLogin('theo');
        $classic_user->setPassword($this->passwordEncoder->encodePassword(
            $classic_user,
            'user1234'
        ));
        $classic_user->setRoles(array('ROLE_USER'));
        $manager->persist($classic_user);

        $manager->flush();
    }
}
