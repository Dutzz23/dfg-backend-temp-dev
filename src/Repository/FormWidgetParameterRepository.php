<?php

namespace App\Repository;

use App\Entity\FormWidgetParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormWidgetParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormWidgetParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormWidgetParameter[] findAll()
 * @method FormWidgetParameter[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormWidgetParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormWidgetParameter::class);
    }
}
