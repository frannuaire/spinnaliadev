<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Reader;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of AnalyserImages
 *
 * @author kferrandon 
 */
class AnalyserLink {

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
    protected $noteLinkTarget;
    protected $noteLinkRatio;
    protected $noteIntern;
    protected $noteExterne;
    public static $noteLinkTotal = 5;
    protected $internalLink;
    protected $internalUniqLink;
    protected $externalLink;
    protected $externalUniqLink;
    protected $linkWithoutTitle;

    /**
     * 
     * @param Reader $reader
     * @param TranslatorInterface $translator
     */
    public function __construct(Reader $reader, TranslatorInterface $translator) {
        $this->reader = $reader;
        $this->translator = $translator;
        $this->internalLink = 0;
        $this->externalLink = 0;
        $this->noteLinkTarget = 0;
        $this->links = $this->reader->getLinks();
        $this->analyseAnchors();
    }

    public function getLinks() {
        return $this->links;
    }

    public function analyseAnchors() {
        $linkWithoutTitle = 0;
        $nbInternalLink = 0;
        $nbExternalLink = 0;
        foreach ($this->links as $link) {
            if (filter_var($link[0][0], FILTER_VALIDATE_URL) == TRUE) {
              //  if (parse_url($link[0][0])["host"] !== parse_url($this->reader->getBaseUrl())["host"]) {
                if (parse_url($link[0][0],PHP_URL_HOST) !== parse_url($this->reader->getBaseUrl(),PHP_URL_HOST)) {
                    $nbExternalLink++;
                } else {
                    $nbInternalLink++;
                }
            } else {
                $nbInternalLink++;
            }

            if ($link[0][1] == '') {
                $linkWithoutTitle++;
            }
        }

        $this->internalLink = $nbInternalLink;
        $this->externalLink = $nbExternalLink;
        $this->linkWithoutTitle = $linkWithoutTitle;

        if ($this->linkWithoutTitle > 0) {
            $this->noteLinkTarget = 0;
            $this->messages[] = 'target.empty';
        } else {
            $this->noteLinkTarget = 1;
        }
        if ($this->getLinkRatio() < 50 && $this->getLinkRatio() >= 25) {
            $this->noteLinkRatio = 1;
        } elseif ($this->getLinkRatio() < 25) {
            $this->noteLinkRatio = 0;
        } else {
            $this->noteLinkRatio = 2;
        }
        if($this->getExternalLink()<=2){
            $this->noteExterne = 0;
        }else{
            $this->noteExterne =1;
        }
        if($this->getInternalLink()<=5){
            $this->noteIntern = 0;
        }else{
            $this->noteIntern =1;
        }

    }

    public function getInternalLink(): int {
        return $this->internalLink;
    }

    public function getExternalLink(): int {
        return $this->externalLink;
    }

    public function getLinkRatio(): float {
        return round((($this->externalLink * 100) / $this->internalLink), 2);
    }

    public function getNote() {
        return ($this->noteLinkTarget+$this->noteExterne+$this->noteIntern+$this->noteLinkRatio);
    }

    public function getNoteTotal() {
        return self::$noteLinkTotal;
    }

    /**
     * 
     * @return Array
     */
    public function getMessages(): Array {
        if (isset($this->messages)) {
            return $this->messages;
        } else {
            return ['link.ok'];
        }
    }

}
