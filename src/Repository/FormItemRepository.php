<?php

namespace App\Repository;

use App\Entity\FormItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormItem[] findAll()
 * @method FormItem[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormItem::class);
    }
}
