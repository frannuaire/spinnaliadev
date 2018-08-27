<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Audit;

use App\Services\Crawler\Head;

/**
 * Description of Free
 *
 * @author kferrandon 
 */
class Free {

    /**
     *
     * @var String
     */
    protected $crawl;

    /**
     *
     * @var App\Services\Crawler\Head
     */
    protected $head;

    /**
     *
     * @var html 
     */
    protected $html;
    /**
     * 
     * @param String $html
     */
    public function __construct($html) {
        
        $this->html = file_get_contents($html);
        // var_dump($html);
        
        $this->crawl = new Head(new Crawler($this->html ));
        
        ;
    }

    public function getTitle() {
        return $this->crawl->getTitle();
    }

    public function getDescription() {
        return $this->crawl->getDescription();
    }

}
