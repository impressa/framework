<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 18.7.2012
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Doctrine;
class BaseRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $paginator \Nette\Utils\Paginator
     * @param $qb \Doctrine\ORM\QueryBuilder
     * @return array
     */
    protected function getPaginatedResults(\Nette\Utils\Paginator $paginator, \Doctrine\ORM\QueryBuilder $qb){
        $qb->setFirstResult($paginator->offset);
        $qb->setMaxResults($paginator->itemsPerPage);
        $results = new \Doctrine\ORM\Tools\Pagination\Paginator($qb->getQuery(), true);
        $paginator->setItemCount(count($results));
        return new \Impressa\Doctrine\PaginatedResult($results);
    }

}
