<?php

namespace Stage\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PrintjController extends Controller
{
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $joueurs = $em->getRepository('StageAdminBundle:Joueur')->findAll();
        return $this->render('StageAdminBundle:joueur:pdf.html.twig', array(
            'joueurs' => $joueurs,
        ));
    }

    /**
     *  Render in a PDF the sandbox_homepage URL
     * @return Response
     */
    public function pdfAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        $filename = 'JoueursPDF';

        // use absolute path !
        $pageUrl = $this->generateUrl('print_joueur', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        return new Response(
            $snappy->getOutput($pageUrl),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
}
