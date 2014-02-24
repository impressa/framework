<?php

/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Impressa\Latte\Macros;

/**
 * @author    Pavel Kučera
 * @author    Patrik Votoček
 */
class CustomMacros extends \Nette\Latte\Macros\MacroSet
{

	/**
	 * @param \Nette\Latte\Parser
	 */
	public static function install(\Nette\Latte\Compiler $compiler)
	{
		$set = new static($compiler);
		$set->addMacro('thumb', function ($node, $writer) {
			$data = explode(',', $node->args);
			$path = \Nette\Utils\Strings::trim($data[0]);
			$width = \Nette\Utils\Strings::trim($data[1]);
			$height = \Nette\Utils\Strings::trim($data[2]);

			return $writer->write(" echo \Impressa\Latte\Macros\ThumbUtil::getThumb($path, $width, $height); ");
		});

		$set->addMacro('urlThumb', function ($node, $writer) {
			$data = explode(',', $node->args);
			$url = \Nette\Utils\Strings::trim($data[0]);
			$parentId = \Nette\Utils\Strings::trim($data[1]);
			$width = \Nette\Utils\Strings::trim($data[2]);
			$height = \Nette\Utils\Strings::trim($data[3]);

			return $writer->write(" echo \Impressa\Latte\Macros\UrlThumbUtil::getThumb($url, $parentId, $width, $height); ");
		});

	}

}