<?php

namespace YSoft\InstrumBundle\Repository;

/**
 * SizeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SizeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByDimension($width, $height)
    {
        $qb = $this->createQueryBuilder('s');
        $qb ->where($qb->expr()->between(':width', 's.minWidth', 's.maxWidth'))
            ->andWhere($qb->expr()->between(':height', 's.minHeight', 's.maxHeight'))
            ->setParameters(array('width' => $width, 'height' => $height));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
