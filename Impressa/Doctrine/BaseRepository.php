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
	protected function getPaginatedResults(\Nette\Utils\Paginator $paginator, \Doctrine\ORM\QueryBuilder $qb)
	{
		$qb->setFirstResult($paginator->offset);
		$qb->setMaxResults($paginator->itemsPerPage);
		$results = new \Doctrine\ORM\Tools\Pagination\Paginator($qb->getQuery(), TRUE);
		$paginator->setItemCount(count($results));
		return new \Impressa\Doctrine\PaginatedResult($results);
	}

	public function checkUniqueValue($fieldName, $fieldValue, Entity $entity = NULL)
	{
		// FIX: v pripade, ze je v DB prazdny string namiesto NULL, tak dalsie prazde stringy sa mu nezdali unikatne
		// napr. nevyplnene ICO sposobovalo error, ze nie je unikatne
		if ($fieldValue == '') {
			return TRUE;
		}

		$existingEntity = $this->findOneBy(array($fieldName => $fieldValue));
		return !($existingEntity && $existingEntity != $entity);
	}

}
