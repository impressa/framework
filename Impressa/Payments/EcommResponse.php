<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 19.8.2012
 * Time: 14:34
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Payments;
class EcommResponse extends \Nette\Object
{

    protected $redirectUrl;
    protected $transactionId;

    public function __construct( $transactionId, $redirectUrl = null){
        $this->transactionId = $transactionId;
        $this->redirectUrl = $redirectUrl;
    }


    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
