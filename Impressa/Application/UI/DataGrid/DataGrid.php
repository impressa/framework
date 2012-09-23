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
abstract class DataGrid extends \Impressa\Application\UI\Control {

    public function __construct($parent, $name) {
        parent::__construct($parent, $name);
        $this->vp = new \Impressa\Application\UI\VisualPaginator($this, 'vp');
        $this->vp->getPaginator()->setItemsPerPage(20);
    }

    /**
     *
     * @var \VisualPaginator
     */
    public $vp;

    /** @persistent */
    public $sortBy;

    /** @persistent */
    public $sortOrder;

    /** @persistent */
    public $qbe;

    public function handleSort($col) {
        if ($col == $this->sortBy) {
            $this->sortOrder = $this->sortOrder == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $col;
            $this->sortOrder = 'ASC';
        }
    }

    public function render() {
        $this->preRender();

        $data = $this->getData();
        //$count = $this->getDataCount();
        $dataName = $this->getDataName();
        $this->template->items = $data;
        $this->template->qbeForm = $this['qbeForm'];


        $this->postRender();
        $this->template->render();
    }

    public function createComponentQbeForm() {
        $form = new \Nella\Forms\Form($this, "qbeForm");


        $this->initQbe($form);
        if ($this->qbe) {
            $form->setDefaults($this->qbe);
        }
        $form->addSubmit("filter", "Hľadať");
        $form->addSubmit("reset", "Zrušiť filter");


        $form->onSuccess[] = \callback($this, 'processQbe');

        $renderer = $form->getRenderer();

        $renderer->wrappers['controls']['container'] = 'tr';
        $renderer->wrappers['pair']['container'] = 'th';
        $renderer->wrappers['label']['container'] = NULL;
        $renderer->wrappers['control']['container'] = NULL;

        return $form;
    }

    public function processQbe(\Nella\Forms\Form $form) {
        if ($form['filter']->isSubmittedBy()) {
            $this->qbe = (array) $form->getValues();
        } else {
            $this->qbe = array();
        }
        $this->redirect("this");
    }

    protected abstract function getData();

    protected abstract function getDataCount();

    protected  function initQbe(\Nella\Forms\Form $form){
        
    }

    protected function preRender() {
        $this->vp->getPaginator()->setItemCount($this->getDataCount());
    }

    protected function postRender() {
        
    }

    public function getLimit() {
        return $this['vp']->getPaginator()->getItemsPerPage();
    }

    public function getOffset() {
        $p = $this->vp->getPaginator();
        $o = $p->getOffset();
        return $o;
    }

}

