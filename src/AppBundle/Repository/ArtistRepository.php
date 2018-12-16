<?php

namespace AppBundle\Repository;

/**
 * ArtistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArtistRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByName($phrase)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM AppBundle:Artist a WHERE a.name LIKE :string ORDER BY a.name ASC"
            )->setParameter('string', '%'.$phrase.'%')
            ->getResult();
    }
}
