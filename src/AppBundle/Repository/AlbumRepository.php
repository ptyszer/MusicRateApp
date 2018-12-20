<?php

namespace AppBundle\Repository;

/**
 * AlbumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlbumRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByName($phrase)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM AppBundle:Album a WHERE a.name LIKE :string AND a.public = 1  ORDER BY a.name ASC"
            )->setParameter('string', '%'.$phrase.'%')
            ->getResult();
    }
}
