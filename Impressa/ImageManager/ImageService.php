<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 22.7.2012
 * Time: 14:04
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\ImageManager;
class ImageService extends \Nette\Object
{

    protected $wwwDir;
    protected $mapping = array();
    protected $basePrefix;


    function __construct($wwwDir, $basePrefix, $mapping)
    {
        $this->wwwDir = $wwwDir;
        $this->mapping = $mapping;
        $this->basePrefix = $basePrefix;
    }


    public function createFromUpload(\Nette\Http\FileUpload $file, $entity, $pathPrefix = "", $dontSetTitle = false)
    {
        if (is_string($entity)) {
            $entity = new $entity;
        }
        if (!$dontSetTitle) {
            $entity->title = $file->getName();
        }
        $entity->path = $pathPrefix . '/' . $file->getSanitizedName();
        return new BondedImage($file->toImage(), $entity, $pathPrefix);
    }

    public function createFromFile(\Nette\Image $file, $entity, $name, $pathPrefix = "", $dontSetTitle = false)
    {
        if (is_string($entity)) {
            $entity = new $entity;
        }
        if (!$dontSetTitle) {
            $entity->title = $name;
        }
        $entity->path = $pathPrefix . '/' . trim(\Nette\Utils\Strings::webalize($name, '.', FALSE), '.-');
        return new BondedImage($file, $entity, $pathPrefix);
    }

    public function saveImage(BondedImage $image)
    {

        $className = $image->getEntity()->reflection->name;
        $prefix = array_key_exists($className, $this->mapping) ? $this->mapping[$className] : $className;

        $dir = $this->wwwDir . $this->basePrefix . $prefix . $image->getPathPrefix();
        if (!is_dir($dir)) {
            mkdir($dir, 0770, true);
        }
        $i = 0;
        $path = $this->wwwDir . $this->basePrefix . $prefix . $image->getEntity()->getPath();
        $entityPath = $image->getEntity()->getPath();
        while (is_file($path)) {
            $i++;
            $newPath = substr($entityPath, 0, strrpos($entityPath, '.')) . "($i)" . substr($entityPath, strrpos($entityPath, '.'));
            $image->getEntity()->setPath($newPath);
            $path = $this->wwwDir . $this->basePrefix . $prefix . $newPath;
        }
        $image->getImage()->save($path);

    }

    public function removeImage(ImageEntity $entity)
    {
        $className = $entity->reflection->name;
        $prefix = array_key_exists($className, $this->mapping) ? $this->mapping[$className] : $className;
        $path = $this->wwwDir . $this->basePrefix . $prefix . $entity->getPath();
        unlink($path);
        //TODO zistit ci nie je posledny subor v adresari + odstranenie thumbov

    }

    public function getImageUrl(ImageEntity $imageEntity, $width = 0, $height = 0, $crop = true)
    {
        //aby nacitalo entitu namiesto proxy
        $className = !$imageEntity->reflection->is('\Doctrine\ORM\Proxy\Proxy') ? $imageEntity->reflection->name : $imageEntity->reflection->getParentClass()->name;
        $prefix = array_key_exists($className, $this->mapping) ? $this->mapping[$className] : $className;
        if ($width > 0 || $height > 0) {
            $thumbPath = $this->basePrefix . $prefix . "/thumbs/" . $width . "_" . $height;
            $thumbPath .= $crop ? "_C" . $imageEntity->getPath() : $imageEntity->getPath();
            $filePath = $this->wwwDir . $thumbPath;
            if (!is_file($filePath)) {
                $dir = substr($filePath, 0, strrpos($filePath, '/'));
                if (!is_dir($dir)) {
                    $old = umask(0);
                    mkdir($dir, 0777, true);
                    umask($old);
                }
                ;
                if (is_file($this->wwwDir . $this->basePrefix . $prefix . $imageEntity->getPath())) {
                    try {
                        $thumb = \Nette\Image::fromFile($this->wwwDir . $this->basePrefix . $prefix . $imageEntity->getPath());
                        if (!$crop) {
                            $thumb->resize($width, $height);
                        } elseif (($thumb->getWidth() > $thumb->getHeight() || $width == 0) && $height != 0) {
                            $thumb->resize(null, $height);
                        } else {
                            $thumb->resize($width, null);
                        }
                        if ($width > 0 && $height > 0 && $crop) {
                            $left = ($thumb->getWidth() - $width) / 2;
                            $left = $left <= 0 ? 0 : (int)$left;
                            $top = ($thumb->getHeight() - $height) / 2;
                            $top = $top <= 0 ? 0 : (int)$top;
                            $thumb->crop($left, $top, $width, $height);

                        }
                        $thumb->save($filePath, '80%', \Nette\Image::JPEG);
                    } catch (\Nette\UnknownImageFileException $e) {

                    }
                }
            }
        } else {
            $thumbPath = $this->basePrefix . $prefix . $imageEntity->getPath();
        }
        return $thumbPath;
    }

    public static function installLatteMacros(\Nette\Latte\Engine $latte)
    {
        $set = new \Nette\Latte\Macros\MacroSet($latte->getCompiler());
        $set->addMacro('image', function($node, $writer)
        {
            $data = explode(',', $node->args);
            $path = \Nette\Utils\Strings::trim($data[0]);

            if (isset($data[1]) && isset($data[2])) {
                $width = \Nette\Utils\Strings::trim($data[1]);
                $height = \Nette\Utils\Strings::trim($data[2]);
            } else {
                $width = 0;
                $height = 0;
            }
            if (isset($data[3])) {
                $crop = \Nette\Utils\Strings::trim($data[3]);
            } else {
                $crop = "true";
            }
            return $writer->write(" echo \$basePath . \$presenter->context->imageManager->getImageUrl($path, $width, $height, $crop); ");
        });
    }

}
