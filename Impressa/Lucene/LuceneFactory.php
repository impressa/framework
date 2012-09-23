<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 14.8.2012
 * Time: 12:59
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Lucene;

class LuceneFactory extends \Nette\Object
{
    /**
     * @static
     * @param $dir
     * @return \Zend_Search_Lucene_Interface
     */
    public static function getLuceneIndex($dir)
    {
        try{
            return \Zend_Search_Lucene::open($dir);
        }catch(\Zend_Search_Exception $e){
        }
        return \Zend_Search_Lucene::create($dir);

    }

}
