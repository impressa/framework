<?php
namespace Impressa\Doctrine;



/**
 * Description of KeyValueHydrator
 *
 * @author puty
 */
class KeyValueHydrator extends \Doctrine\ORM\Internal\Hydration\AbstractHydrator{
    protected function hydrateAllData()
    {
        return $this->_stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
