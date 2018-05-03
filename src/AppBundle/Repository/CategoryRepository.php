<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @param int $type
     * @return Category[]
     */
    public function getCategoriesByType(int $type)
    {
        return $this->createQueryBuilder('category')
            ->select('category')
            ->innerJoin('category.products', 'product')
            ->andWhere('product.type = :type')
            ->setParameter('type', $type)
            ->orderBy('category.name','ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCategories()
    {
        return $this->createQueryBuilder('category')
            ->select('category')
            ->innerJoin('category.products', 'product')
            ->orderBy('category.name','ASC')
            ->getQuery()
            ->getResult();
    }
}