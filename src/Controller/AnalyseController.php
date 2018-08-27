<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AnalyseController
 *
 * @author kferrandon 
 */
class AnalyseController extends Controller {

    /**
     * @Route("/{_locale}/analyse/{uri}", name="analyse")
     */
    public function testUri(Request $request, $uri) {

        $url = 'https://' . $uri;
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new \Exception('URL Non valide');
        }
        $audit = new \App\Services\Crawler\Reader($url);
    //    $tmp = $audit->getWords();
        // $tmp->getDensity();die;
        return $this->render('audit/free.html.twig', [
                    'audit' => $audit,
        ]);
    }

}
