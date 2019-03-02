<?php

namespace Stage\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PrintController extends Controller
{
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $federations = $em->getRepository('StageAdminBundle:Federation')->findAll();
        return $this->render('StageAdminBundle:federation:pdf.html.twig', array(
            'federations' => $federations,
        ));
    }

    /**
     *  Render in a PDF the sandbox_homepage URL
     * @return Response
     */
    public function pdfAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        $filename = 'FederationPDF';

        // use absolute path !
        $pageUrl = $this->generateUrl('sandbox_homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL);

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
