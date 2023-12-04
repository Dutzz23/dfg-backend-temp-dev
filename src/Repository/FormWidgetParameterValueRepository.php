<?php

namespace App\Repository;

use App\Entity\FormWidgetParameterValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormWidgetParameterValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormWidgetParameterValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormWidgetParameterValue[] findAll()
 * @method FormWidgetParameterValue[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormWidgetParameterValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormWidgetParameterValue::class);
    }
}
