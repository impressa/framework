<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Latte\Macros;
/**
 * Description of ThumbUtil
 *
 * @author puty
 */
class ThumbUtil {
    public static function getThumb($path, $width, $height){
        $context = \Nette\Environment::getContext();
       

        $basePath =  $context->params['wwwDir'];
        $thumbPath = $context->params['imagesPath'] . "/thumbs/" . $width . "_". $height . "_C" . $path;
        $filePath = $basePath . $thumbPath;
        if(!is_file($filePath)){
            $dir = substr($filePath, 0, strrpos($filePath, '/')); 
            if(!is_dir($dir)){
                $old = umask(0); 
                mkdir($dir, 0777,true);
                umask($old); 
            };
            if(is_file($basePath . $context->params['imagesPath'] . $path)){
                try {
                    $thumb = \Nette\Image::fromFile($basePath . $context->params['imagesPath'] . $path);
                    if ($thumb->getWidth() > $thumb->getHeight()) {
                         $thumb->resize(null, $height);
                    } else {
                         $thumb->resize($width, null);
                    }
                    $left = ($thumb->getWidth() - $width) / 2;
                    $left = $left <= 0 ? 0 : (int) $left;
                    $top = ($thumb->getHeight() - $height) / 2;
                    $top = $top <= 0 ? 0 : (int) $top;
                    $thumb->crop($left, $top, $width, $height);
                    $thumb->save($filePath, '80%', \Nette\Image::JPEG);
                }catch(\Nette\UnknownImageFileException $e){
                    
                }
            }
        }
        return $thumbPath;
    }
}


