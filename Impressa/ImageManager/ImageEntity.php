<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 20.7.2012
 * Time: 14:23
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\ImageManager;

interface ImageEntity
{
    public function getPath();
    public function setPath($path);

    public function getTitle();
    public function setTitle($title);
}
