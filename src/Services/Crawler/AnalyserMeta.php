<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Reader;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of Analyser
 *
 * @author kferrandon 
 */
class AnalyserMeta {

    /**
     *
     * @var Reader 
     */
    protected $reader;

    /**
     *
     * @var Array 
     */
    protected $meta;

    /**
     *
     * @var TranslatorInterface 
     */
    protected $translator;

    /**
     *
     * @var int 
     */
    protected $ptTitle;
    protected $title;

    /**
     *
     * @var int 
     */
    public static $ptTTTitle = 4;
    protected $ptOG;
    public static $ptTTOG = 1;
    protected $ptHx;
    public static $ptTTHx = 3;
    protected $ptTTHeader;
    protected $h1;
    protected $h2;

    /**
     *
     * @var int 
     */
    protected $ptMeta;

    /**
     *
     * @var int
     */
    protected $ptTTMeta;
    protected $description;

    /**
     * 
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(Reader $reader, TranslatorInterface $translator) {

        $this->meta = [];
        $this->reader = $reader;
        $this->translator = $translator;
        $this->ptTitle = self::$ptTTTitle;
        $this->ptHx = self::$ptTTHx;
        $this->title();
        $this->description();
    }

    public function title() {
        $title = $this->reader->getTitle();
        $szTitle = strlen($title);
        $todo = $this->translator->trans('perfect', [], 'analyse');
        $msg[] = array('info' => $this->translator->trans('analyse.title.google', [], 'analyse'));
        if ($szTitle > 60) {
            $this->ptTitle = $this->ptTitle - 1;
            $todo = $this->translator->trans('imrpove', [], 'analyse');
            $msg[] = array('alert' => $this->translator->trans('analyse.title.long', [], 'analyse'));
        }
        if ($szTitle < 20) {
            $this->ptTitle = ($szTitle == 0) ? $this->ptTitle - 2 : $this->ptTitle - 1;
            $todo = $this->translator->trans('imrpove', [], 'analyse');
            $msg[] = array('alert' => $this->translator->trans('title.short', [], 'analyse'));
        }
        $this->title = ['name' => 'Title', 'value' => $title, 'size' => $szTitle, 'todo' => $todo, 'messages' => $msg];
    }

    /**
     * 
     * @return Array
     */
    public function getTitle(): Array {
        return $this->title;
    }

    public function description() {
        $desc = $this->reader->getDescription();
        $szDesc = strlen($desc);
        $todo = $this->translator->trans('perfect', [], 'analyse');
        $msg[] = array('info' => $this->translator->trans('description.google', [], 'analyse'));
        if ($szDesc > 160) {
            $this->ptTitle = $this->ptTitle - 1;
            $todo = $this->translator->trans('imrpove', [], 'analyse');
            $msg[] = array('alert' => $this->translator->trans('description.long', [], 'analyse'));
        }
        if ($szDesc < 80) {
            $todo = $this->translator->trans('imrpove', [], 'analyse');
            $this->ptTitle = ($szDesc == 0) ? $this->ptTitle - 2 : $this->ptTitle - 1;
            $msg[] = array('alert' => $this->translator->trans('description.short', [], 'analyse'));
        }
        $this->description = ['name' => 'Description', 'value' => $desc, 'size' => $szDesc, 'todo' => $todo, 'messages' => $msg];
    }

    /**
     * 
     * @return Array
     */
    public function getDescription(): Array {
        return $this->description;
    }

    /**
     * 
     * @return array
     */
    public function getMeta(): Array {
        array_push($this->meta, $this->getTitle(), $this->getDescription());
        return $this->meta;
    }

    /**
     * 
     * @return int
     */
    public function getSimilarTitleDesc() {
        return similar_text($this->reader->getTitle(), $this->reader->getDescription(), $similar);
        ;
    }

    /**
     * 
     * @return string
     */
    public function getSimilarWord() {
        return $this->contains($this->reader->getTitle(), explode(' ', $this->reader->getDescription()));
    }

    /**
     * 
     * @return int
     */
    public function getPtTitle() {
        return $this->ptTitle;
    }

    /**
     * 
     * @return int
     */
    public function getPrTTTitle() {
        return self::$ptTTTitle;
    }

    /**
     * 
     * @return string
     */
    public function conclusionTitle() {
        $conclusion = '';
        if ($this->ptTitle == 4) {
            $conclusion = $this->translator->trans('conclusion.weldone', [], 'analyse');
        } else {
            $conclusion = $this->translator->trans('conclusion.improve', [], 'analyse');
        }
        return $conclusion;
    }

    /**
     * 
     * @param string $str
     * @param array $arr
     * @return boolean|string
     */
    private function contains($str, array $arr) {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false)
                return $a;
        }
        return false;
    }

    /**
     * 
     * @return NULL | array
     */
    public function getOg() {
        $this->ptOG = self::$ptTTOG;
        if (is_null($this->reader->getOg())) {
            $this->ptOG = $this->ptOG - 1;
            return null;
        } else {
            return $this->reader->getOg();
        }
    }

    /**
     * 
     * @return int
     */
    public function getPtOg() {
        if (!isset($this->ptOG)) {
            $this->getOg();
        }
        return $this->ptOG;
    }

    /**
     * 
     * @return int
     */
    public function getPtTTOg() {
        return self::$ptTTOG;
    }

    public function getConclusionOG() {
        if ($this->ptOG == 1) {
            return '';
        } else {
            return '';
        }
    }

    public function getH1() {
        if (!isset($this->h1)) {
            if ($this->reader->getH1() === false) {
                $this->ptHx = $this->ptHx - 2;
            } else {
                $this->h1 = $this->reader->getH1();
            }
        }
        return $this->h1;
    }

    public function getH2() {
        if (!isset($this->h2)) {
            if (count($this->reader->getH2()) == 0 && count($this->reader->getH3()) == 0) {
                $this->ptHx = $this->ptHx - 1;
                $this->h2 = '';
                return '';
            }
            $this->h2 = $this->reader->getH2();
        }
        return $this->h2;
    }

    public function getH3() {
        return $this->reader->getH3();
    }

    public function getPtHx() {
        if (isset($this->h1) && isset($this->h2)) {
            return $this->ptHx;
        } else {
            $this->getH1();
            $this->getH2();
            return $this->ptHx;
        }
    }

    public function getPtTTHx() {
        return self::$ptTTHx;
    }

    public function getPtTotalHeader() {
        return ($this->getPtHx() + $this->getPtOg() + $this->getPtTitle());
    }

}
