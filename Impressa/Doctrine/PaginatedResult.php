<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 18.7.2012
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */

namespace Impressa\Doctrine;
class PaginatedResult implements \Iterator
{
    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    protected $result;
    protected $totalCount;


    function __construct(\Doctrine\ORM\Tools\Pagination\Paginator $paginator)
    {
        $this->result = $paginator->getIterator();
        $this->totalCount = $paginator->count();
    }

    protected function sanitizeElement($element){
        if(is_array(($element))){
            foreach ($element as $key => $aggregate) {
                if($key !== 0){
                    $element[0]->scalars[$key] = $aggregate;
                }
            }
            return $element[0];
        }
        return $element;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->sanitizeElement($this->result->current());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return $this->sanitizeElement($this->result->next());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->result->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->result->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->result->rewind();
    }

    public function getTotalCount(){
        return $this->totalCount;
    }

}
