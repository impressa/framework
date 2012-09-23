<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 22.7.2012
 * Time: 19:42
 * To change this template use File | Settings | File Templates.
 */

namespace Impressa\ImageManager;
class BondedImage extends \Nette\Object
{
    /**
     * @var \Nette\Image
     */
    protected $image;

    /**
     * @var ImageEntity
     */
    protected $entity;

    protected $pathPrefix;

    function __construct(\Nette\Image $image, ImageEntity $entity, $pathPrefix = "")
    {
        $this->entity = $entity;
        $this->image = $image;
        $this->pathPrefix = $pathPrefix;
    }

    /**
     * @return \Impressa\ImageManager\ImageEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return \Nette\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }


}
