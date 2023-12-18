<?php

namespace App\Repository;
use App\Entity\UserForms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForms|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForms|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForms[] findAll()
 * @method UserForms[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFormsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForms::class);
    }
}