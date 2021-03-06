<?php

namespace YSoft\InstrumBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * LendingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LendingRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *
     * @param array $criteria
     * @param array $orderBy
     * @param integer $limit
     * @param integer $offset
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function searchBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('l');
        $qb ->leftJoin('l.band', 'b')->addSelect('b')
            ->leftJoin('l.pieces', 'p')->addSelect('p');

        foreach ($criteria as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $marker = ':' . $field;
            $qb->andWhere($qb->expr()->like($this->sanitizeField($field), $marker));
            if ($field == 'name') {
                $qb->orWhere($qb->expr()->like('p.translation', $marker));
            }
            $qb->setParameter($marker, '%' . $value . '%');
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($this->sanitizeField($field), $direction);
        }

        if (null != $limit) {
            $qb->setMaxResults($limit);
        }
        if (null != $offset) {
            $qb->setFirstResult($offset);
        }

        $paginator = new Paginator($qb);

        return $paginator;
    }

    private function sanitizeField($field)
    {
        switch ($field) {
            case 'band':
                $field = 'b.name';
                break;
            case 'piece':
                $field = 'p.name';
                break;
            case 'contact':
                $field = 'l.contact';
                break;
            case 'start':
            case 'id':
                $field = 'p.' . $field;
            default:
                break;
        }

        return $field;
    }
}
