<?php

namespace AppBundle\Model\Repository;

use AppBundle\Model\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function __construct()
    {

    }

    public function create(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}