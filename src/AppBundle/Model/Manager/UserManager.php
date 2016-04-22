<?php

namespace AppBundle\Model\Manager;

use AppBundle\Model\Entity\User;
use AppBundle\Model\Repository\UserRepository;

class UserManager
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($name)
    {
        $user = new User();
        $user->setName($name);

        $this->userRepository->create($user);
    }

    public function userList()
    {
        return $this->userRepository->findAll();
    }
}