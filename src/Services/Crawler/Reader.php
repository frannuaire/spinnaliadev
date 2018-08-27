<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use App\Services\Crawler\TextContent;

/**
 * Description of head
 *
 * @author kferrandon 
 */
class Reader {

    /**
     *
     * @var Symfony\Component\DomCrawler\Crawler 
     */
    protected $crawler;

    /**
     *
     * @var string 
     */
    protected $title;

    /**
     *
     * @var string 
     */
    protected $desc;

    /**
     * @var int nombre d'images
     */
    protected $nbImg;

    /**
     *
     * @var int 
     */
    protected $nbImgSansAlt;

    /**
     *
     * @var string 
     */
    protected $baseUrl;

    /**
     *
     * @var bool 
     */
    protected $isSet;

    /**
     *
     * @var Array
     */
    protected $word;
    protected $og;

    /**
     *
     * @var Array 
     */
    protected $images;

    /**
     * 
     * @param string $url
     */
    public function __construct($url) {
        $this->word = [];
        $this->nbImg = 0;
        $this->isSet = false;
        $this->nbImgSansAlt = 0;
        $this->baseUrl = $url;

        $html = file_get_contents($url);

        $this->crawler = new Crawler($html);
        $this->word = array_merge($this->word, explode(' ', $this->getH1()));
        $this->og = $this->getOpenGraph();
        $this->images = null;
    }

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * 
     * @return string
     */
    public function getTitle() {
        $this->title = $this->crawler->filter('title')->text();
        $this->word = array_merge($this->word, explode(' ', $this->title));
        // array_push($this->word, explode(' ', $this->title));
        return $this->title;
    }

    /**
     * 
     * @return string
     */
    public function getDescription() {
        $this->desc = $this->crawler->filterXPath('//meta[@name="description"]')->extract(array('content'));

        if (count($this->desc) === 0) {
            return '';
        }

        $this->word = array_merge($this->word, explode(' ', $this->desc[0]));
        // array_push($this->word, explode(' ', $this->desc[0]));
        return $this->desc[0];
    }

    public function getOpenGraph() {
        $nodes = $this->crawler->filterXPath('//meta')->extract(array('property', 'content'));

        $nbOpenGraph = 0;
        if (count($nodes) > 0) {
            foreach ($nodes as $node) {
                if ($node[0] != "") {
                    $nbOpenGraph++;
                    $this->openGraph[] = $node;
                }
            }
            if ($nbOpenGraph > 0) {
                return $this->openGraph;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * 
     * @return int
     */
    public function countH1() {

        return $this->crawler->filterXPath('//h1')->count();
    }

    /**
     * 
     * @return string
     */
    public function getH1() {

        if ($this->countH1() > 1) {
            return false;
        } elseif ($this->countH1() == 1) {

            return $this->crawler->filterXPath('//h1')->text();
        } else {
            return false;
        }
    }

    public function getH2() {
        $h2 = $this->crawler->filter('h2')->each(function (Crawler $node, $i) {
            $this->word = array_merge($this->word, explode(' ', $node->text()));
            return $node->text();
        });
        return $h2;
    }

    public function getH3() {
        $h3 = $this->crawler->filter('h3')->each(function (Crawler $node, $i) {
            $this->word = array_merge($this->word, explode(' ', $node->text()));
            return $node->text();
        });
        return $h3;
    }

    function filesize_url($url) {
        return ($data = @file_get_contents($url)) ? strlen($data) : false;
    }

    public function getAltSrcImg() {
        if ($this->isSet) {
            return $this->images;
        }
        $this->isSet = true;
        $this->images = $this->crawler->filter('img')->each(function (Crawler $node, $i) {
            $this->nbImg = $this->nbImg + 1;
            $pattern = '#^' . $this->getBaseUrl() . '#';
            $size = 0;
            $weight = 0;
            if (preg_match($pattern, $node->extract('src')[0]) !== 1) {
                $file = $this->getBaseUrl() . '/' . $node->extract('src')[0];
                if (!$fp = curl_init($file)) {
                    $size = getimagesize($file);
                    ($weight = $this->filesize_url($file)) ? $weight : 0;
                } else {
                    
                }
            } else {

                $size = getimagesize($node->extract('src')[0]);
                ($weight = $this->filesize_url($node->extract('src')[0])) ? $weight : 0;
            }

            if ($node->extract('alt')[0] == '') {
                $this->nbImgSansAlt = $this->nbImgSansAlt + 1;
            }

            $image = $node->extract(array('alt', 'src'))[0];

            array_push($image, [
                'width' => $size[0], 'height' => $size[1],
                'weigth' => $weight, 'mime' => $size['mime']
            ]);
            return $image;
        });

        return $this->images;
    }

    public function getNbImg() {
        if ($this->isSet === false) {
            $this->getAltSrcImg();
        }
        return $this->nbImg;
    }

    public function getNbImgSansAlt() {
        if ($this->isSet === false) {
            $this->getAltSrcImg();
        }
        return $this->nbImgSansAlt;
    }

    public function getLinks() {
        $link = $this->crawler->filter('a')->each(function (Crawler $node, $i) {
            //$this->nbLink= $this->nbLink+1;
            return $node->extract(array('href', 'title'));
        });
        return $link;
    }

    public function getWords() {
        $this->getContentText('body');
        $this->limitData($this->word);
        return new TextContent($this->word);
        // return implode(' ', $this->word);
    }

    public function getContentText() {
        //dump($this->crawler->filter('body')->children());die;
        $this->crawler->filter('body')->children()->each(function (Crawler $node, $i) {
            if ($node->nodeName() !== 'style' && $node->nodeName() !== 'script') {
                $this->word = array_merge($this->word, explode(' ', $node->text()));
            }
        });
    }

    protected function limitData() {
        $tmp = $this->word;
        foreach ($tmp as $key => $value) {
            if (strlen($value) <= 2) {
                unset($tmp[$key]);
            }
        }
        $this->word = $tmp;
        //   dump($this->word);            
    }

    public function getOg() {
        return $this->og;
    }

}
