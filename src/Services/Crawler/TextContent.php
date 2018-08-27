<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Crawler;

/**
 * Description of TextContent
 *
 * @author kferrandon 
 */
class TextContent {

    /**
     * Array of all content
     * @var Array  
     */
    protected $content;

    /**
     * Array with unique word
     * @var Array 
     */
    protected $uniqueContent;

    /**
     * Array with word => density
     * @var Array 
     */
    protected $density;

    /**
     * Array filter with some word
     * @var Array 
     */
    protected $filterContent;

    /**
     * count words
     * @var int 
     */
    protected $countWord;

    /**
     * count distinct word
     * @var int 
     */
    protected $countDistinctWord;

    /**
     * Array with word and occurence
     * @var Array 
     */
    protected $wordOccurence;

    public function __construct(Array $content) {
        //  sort($content);
        $content = array_map('strtolower', $content);
        $this->content = $content;
        $this->badCaracters();
        $this->filterWord();
        $this->countWord = str_word_count(implode(' ', $content));
        $this->uniqueContent = array_unique($this->content);
        $this->countDistinctWord = str_word_count(implode(' ', $this->uniqueContent));
    }

    /**
     * FilterWord with some short word
     */
    public function filterWord() {
        $notWord = ['sous', 'sur', 'que', 'qui', 'dont', 'donc', 'mais', 'ou',
            'est', 'or', 'ni', 'car', 'tout', 'tous', 'toute', 'toutes',
            'vous', 'votre', 'une', 'bien', 'ans', 'des', 'pour', 'ces',
            'vos', 'ses', 'selon', 'nos', 'nous',
            'par', 'le', 'la', 'les', 'un', 'une'];
        $tmp = $this->content;
        foreach ($tmp as $key => $value) {
            foreach ($notWord as $nw) {
                if (in_array($value, $notWord)) {
                    unset($tmp[$key]);
                }
            }
        }
        $this->filterContent = $tmp;
        return $this;
    }

    /**
     * delete some bad caracters
     */
    public function badCaracters() {
        foreach ($this->content as $key => $value) {
            $this->content[$key] = preg_replace('~[\\\\/:*?"<>\\n\.|]~', ' ', $value);
            $this->content[$key] = \trim($this->content[$key]);
        }
        return $this;
    }

    public function getDensity() {
        $this->wordOccurence = array_count_values($this->filterContent);
        foreach ($this->wordOccurence as $word => $occ) {
            if (($occ * 100 / $this->countWord) > 0.3) {
                $this->density[] = ['word'=>$word, 
                    'density'=>($occ * 100 / $this->countWord),
                    'occurence'=>$occ
                    ];
            }
        }
        arsort($this->density);
        return $this->density;
    }

    /**
     * return number of Words
     * @return int
     */
    public function getWordCount() {
        return $this->countWord;
    }

    /**
     * return number of distinct Words
     * @return int
     */
    public function getDistinctWordCount() {
        return $this->countDistinctWord;
    }

}
