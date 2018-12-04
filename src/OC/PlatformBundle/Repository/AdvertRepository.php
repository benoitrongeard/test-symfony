<?php

namespace OC\PlatformBundle\Repository;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAdvertWithCategories($categoryId)
    {

        $qb = $this->createQueryBuilder('a');

        $qb
            ->addSelect('c')
            ->innerJoin('a.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter(':categoryId', $categoryId);

        return $qb->getQuery()->getResult();
    }
}
