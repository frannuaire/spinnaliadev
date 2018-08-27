<?php
namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of MenuController
 *
 * @author kferrandon
 */
class MenuController extends Controller{
    
    /**
     * @Route("/menu", name="menu")
     * 
     */
    public function menu(){
       
        return $this->render('nav/menu.html.twig', [ ]); 
        
    }
}
