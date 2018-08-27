<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Description of IndexController
 *
 * @author kferrandon 
 */
class IndexController extends Controller {

    /**
     * @Route("/{_locale}", name="homepage")
     */
    public function home() {

        return $this->render('index/index.html.twig', [
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/{_locale}/uri", name="uri")
     */
    public function testUri() {
     //      $html = "http://www.winatou.fr";
     //   $html = "https://www.frannuaire-gratuit.com";
       $html = "https://www.restaurant-saint-hilaire.fr";
        $audit = new \App\Services\Crawler\Read($html);
   //     $html = "www.restaurant-saint-hilaire.fr";


        return $this->render('audit/free.html.twig', [
                    'audit' => $audit,
        ]);
    }

}
