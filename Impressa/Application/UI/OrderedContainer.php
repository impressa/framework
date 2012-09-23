<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 27.6.2012
 * Time: 12:53
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Application\UI;

class OrderedContainer extends Control
{
    private $components = array();

    public function insertAsLast(\Nette\Application\UI\Control $control, $name)
    {
        $this->addComponent($control, $name);
        array_push($this->components, $name);
    }

    public function insertFirst(\Nette\Application\UI\Control $control, $name)
    {
        $this->addComponent($control, $name);
        array_unshift($this->components, $name);
    }

    public function render()
    {
        foreach ($this->components as $component) {
            $this[$component]->render();
        }
    }

    public function insertBefore(\Nette\Application\UI\Control $control, $name, $position)
    {
        $this->addComponent($control, $name);
        $index = array_search($position, $this->components);
        if ($index) {
            $tail = array_slice($this->components, $index);
            $head = array_slice($this->components, 0, $index);
            $this->components = array_merge($head, array($name), $tail);
        } else {
            array_push($this->components, $name);
        }
    }

    public function insertAfter(\Nette\Application\UI\Control $control, $name, $position)
    {
        $this->addComponent($control, $name);
        $index = array_search($position, $this->components);
        if ($index) {
            $tail = array_slice($this->components, $index + 1);
            $head = array_slice($this->components, 0, $index + 1);
            $this->components = array_merge($head, array($name), $tail);
        } else {
            array_push($this->components, $name);
        }
    }

}
