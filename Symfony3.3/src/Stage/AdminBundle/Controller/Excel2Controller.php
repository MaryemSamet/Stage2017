<?php

namespace Stage\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Excel2Controller extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $results= $em->getRepository('StageAdminBundle:Joueur')->findAll();


        $excel = $this->get('phpexcel')->createPHPExcelObject();
        $excel->getProperties()->setCreator('iStyle')
            ->setTitle(' Report');

        $i = 2;
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle('Liste Des Joueurs ')
            ->mergeCells('A1:G1')
            ->setCellValue('A1', 'Liste Des Joueurs')
            ->setCellValue('A'.$i, 'nom')
            ->setCellValue('B'.$i, 'nomPays')
            ->setCellValue('C'.$i, 'nomFederation')
            ->setCellValue('D'.$i, 'datenaiss');
        $i = 3;
        foreach ($results as $result)
        { $excel->getActiveSheet()
            ->setCellValue('A'.$i, $result->getNom())
            ->setCellValue('B'.$i, $result->getNomPays())
            ->setCellValue('C'.$i, $result->getNomFederation())
            ->setCellValue('D'.$i, $result->getDatenaiss());
            $i++;}

        $writer = $this->get('phpexcel')->createWriter($excel, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Joueurs.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);


        return $response;
    }

}
