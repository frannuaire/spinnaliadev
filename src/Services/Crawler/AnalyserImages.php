<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Reader;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of AnalyserImages
 *
 * @author kferrandon 
 */
class AnalyserImages {

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

    /**
     *
     * @var int 
     */
    public static $noteImgTt = 4;
    protected $noteAltImg;
    protected $noteImgWeight;
    protected $noteGlobale;
    public $images = array();

    /**
     * 
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(Reader $reader, TranslatorInterface $translator) {
        $this->reader = $reader;
        $this->translator = $translator;
        $this->images = $this->unique_multidim_array($this->reader->getAltSrcImg(), 0);
    }

    /**
     * return int
     */
    public function getNbImages(): int {
        return $this->reader->getNbImg();
    }

    /**
     * return int
     */
    public function getNbImagesSansAlt(): int {
        return $this->reader->getNbImgSansAlt();
    }

    /**
     * 
     * @return array
     */
    public function getImagesInfos(): array {
        return $this->images;
        //$this->reader->getAltSrcImg();
    }

    /**
     * 
     * 
     */
    public function getNoteImgAlt(): int {
        if ($this->getNbImagesSansAlt() > 1) {
            $this->noteAltImg = 0;
        } else {
            $this->noteAltImg = 2;
        }
        return $this->noteAltImg;
    }

    public function getNoteImgWeight(): int {
        $this->noteImgWeight = 2;
        foreach ($this->images as $value) {
            foreach ($value[2] as $key => $weigth) {
                if ($key == "weigth") {
                    if ($weigth > 300000) {
                        $this->noteImgWeight = 0;
                      break;                     
                    }
                }
            }
        }

        return $this->noteImgWeight;
    }

    protected function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
        
    public function getNoteGlobale(): int {
        $this->noteGlobale = $this->getNoteImgAlt()+$this->getNoteImgWeight();
        return $this->noteGlobale;
    }
    public function getNoteImgTt(){
        return self::$noteImgTt;
    }
}
