<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Impressa\Application\UI;

/**
 * Description of DataGrid
 *
 * @author puty
 */
class DataGrid extends \Impressa\Application\UI\Control
{
	protected $data;

	protected $columns;



	public function setData($data) {
		$this->data = $data;
	}

	public function setColumns($columns) {
		$this->columns = $columns;
	}

	public function render(){
		$this->template->setFile(__DIR__  . '/datagrid.latte');
		$this->template->data = $this->data;
		$this->template->columns = $this->columns;
		$this->template->render();
	}
}

