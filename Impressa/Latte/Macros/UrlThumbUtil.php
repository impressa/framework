<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Latte\Macros;

/**
 * Description of UrlThumbUtil
 *
 * @author puty
 */
class UrlThumbUtil
{

	public static function getThumb($url, $parentId, $width, $height)
	{
		$context = \Nette\Environment::getContext();

		$basePath = $context->params['wwwDir'];
		$url = str_replace(' ', '%20', $url);

		$thumbPath = $context->params['thumbsPath'] . $width . "_" . $height . "_C" . '/' . $parentId . '/' . sha1($url) . '.jpg';
		$fullPath = $basePath . $thumbPath;

		if (!is_file($fullPath)) {
			$dir = substr($fullPath, 0, strrpos($fullPath, '/'));

			if (!is_dir($dir)) {
				$old = umask(0);
				mkdir($dir, 0777, TRUE);
				umask($old);
			}

			if (!self::checkRemoteFile($url)) {
				$url = 'http://www.designofsignage.com/application/symbol/building/image/600x600/no-photo.jpg';
			}

			try {
				$thumb = \Nette\Image::fromFile($url);

				if ($thumb->getWidth() > $thumb->getHeight()) {
					$thumb->resize(NULL, $height);
				} else {
					$thumb->resize($width, NULL);
				}
				$left = ($thumb->getWidth() - $width) / 2;
				$left = $left <= 0 ? 0 : (int)$left;
				$top = ($thumb->getHeight() - $height) / 2;
				$top = $top <= 0 ? 0 : (int)$top;
				$thumb->crop($left, $top, $width, $height);
				$thumb->save($fullPath, '80%', \Nette\Image::JPEG);
			} catch (\Nette\UnknownImageFileException $e) {

			}

		}

		return $thumbPath;
	}

	public static function checkRemoteFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (curl_exec($ch) !== FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}


