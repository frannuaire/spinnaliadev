<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Crawler;

/**
 * Description of Content
 *
 * @author kferrandon 
 */
class Content {
    
    protected $h1;
    /**
     * 
     * @param App\Services\Crawler $crawler
     */
    public function __construct($crawler) {
        $this->crawler = $crawler;
    }
    
    public function countH1(){
         dump($this->crawler->filterXPath('//h1')->evaluate('count()'));
    }

    public function getH1(){
        if($this->countH1()>1){
            return 'Error you have to many h1';
        }else{
            return $this->crawler->filterXPath('//h1')->text();
        }
    }
}
