<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Reader;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of AnalyserImages
 *
 * @author kferrandon 
 */
class AnalyserContent {

    /**
     *
     * @var Reader 
     */
    protected $reader;

    /**
     *
     * @var TranslatorInterface 
     */
    protected $translator;
    protected $links;
    protected $messages;


    /**
     * 
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(Reader $reader, TranslatorInterface $translator) {
        $this->reader = $reader;
        $this->translator = $translator;

    }
    
    public function getBestWords(){
        $best = $this->reader->getWords()->getDensity();
        $bestWords=array();
        $i=0;
       foreach($best as $words){
           if($words['density']>=3 && $i<=3){
               array_push($bestWords, $words);
               $i++;
           }
       }
       return $bestWords;
        
    }

   

}
