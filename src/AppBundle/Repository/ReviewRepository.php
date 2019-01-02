<?php

namespace AppBundle\Repository;

/**
 * ReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLatest()
    {
        return $this->getEntityManager()
            ->createQuery("SELECT r FROM AppBundle:Review r ORDER BY r.lastEdit DESC")
            ->setMaxResults(10)
            ->getResult();
    }
}
