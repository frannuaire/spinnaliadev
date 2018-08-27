<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \App\Services\Crawler\Reader;
use \App\Services\Crawler\AnalyserMeta;
use App\Services\Crawler\AnalyserImages;

use Symfony\Component\Translation\TranslatorInterface;
/**
 * Description of SearchController
 *
 * @author kferrandon 
 */
class SearchController extends Controller {

    /**
     * @Route("/{_locale}/search", name="search")
     */
    public function searchAction(TranslatorInterface $translator, Request $request) {
   
        $uri =$request->get('uri');
        if (null !== $uri) {
            $url = $uri;
            if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
                throw new \Exception('URL Non valide');
            }
            $audit = new Reader($url);
            $analyserMeta = new AnalyserMeta($audit, $translator);
            $analyserImage = new AnalyserImages($audit, $translator);
            $analyserLinks = new \App\Services\Crawler\AnalyserLink($audit, $translator);
            $analyseContent = new \App\Services\Crawler\AnalyserContent($audit, $translator);
            return $this->render('audit/free.html.twig', [
                        'audit' => $audit,
                        'analyser'=>$analyserMeta,
                        'images'=>$analyserImage,
                        'links'=>$analyserLinks,
                        'content'=>$analyseContent,
            ]);
        }
        return $this->render('index/index.html.twig', [
        ]);
    }

}
