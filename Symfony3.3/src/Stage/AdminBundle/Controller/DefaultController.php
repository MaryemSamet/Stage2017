<?php

namespace Stage\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stage\AdminBundle\Entity\Joueur;
use Stage\AdminBundle\StageAdminBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {     $variable = array();
        $em = $this->getDoctrine()->getManager();

        $variable['nbFederation']= $em->getRepository("StageAdminBundle:Federation")->countAll();
        $variable['nbJoueur']= $em->getRepository("StageAdminBundle:Joueur")->countAll();


        return $this->render('StageAdminBundle:Default:index.html.twig', array(
            'variables' => $variable,
        ));
    }
}
