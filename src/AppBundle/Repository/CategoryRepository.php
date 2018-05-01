<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @param  $type
     * @return Category[]
     */
    public function getCategoriesByType($type)
    {
        return $this->createQueryBuilder('category')
            ->select('category.slug, category.name, product.type as type')
            ->innerJoin('category.products', 'product')
            ->orderBy('category.name','ASC')
            ->andWhere('product.type = :type')
            ->setParameter('type', $type)
            ->groupBy('category.slug, category.name, type')
            ->getQuery()
            ->getResult();
    }

    public function getCategories()
    {
        return $this->createQueryBuilder('category')
            ->select('category.slug, category.name')
            ->orderBy('category.name','ASC')
            ->getQuery()
            ->getResult();
    }
}