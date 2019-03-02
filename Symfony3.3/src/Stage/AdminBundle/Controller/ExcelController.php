<?php

namespace Stage\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExcelController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $results= $em->getRepository('StageAdminBundle:Federation')->findAll();


        $excel = $this->get('phpexcel')->createPHPExcelObject();
        $excel->getProperties()->setCreator('iStyle')
            ->setTitle('Report');

        $i = 2;
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle('Liste Des Federations ')
            ->mergeCells('A1:G1')
            ->setCellValue('A1', 'Liste Des Federations')
            ->setCellValue('A'.$i, 'nom')
            ->setCellValue('B'.$i, 'sigle')
            ->setCellValue('C'.$i, 'nomPays')
            ->setCellValue('D'.$i, 'president')
            ->setCellValue('F'.$i, 'creation');
        $i = 3;
        foreach ($results as $result)
        { $excel->getActiveSheet()
            ->setCellValue('A'.$i, $result->getNom())
            ->setCellValue('B'.$i, $result->getSigle())
            ->setCellValue('C'.$i, $result->getNomPays())
            ->setCellValue('D'.$i, $result->getPresident())
            ->setCellValue('F'.$i, $result->getCreation());
            $i++;}

        $writer = $this->get('phpexcel')->createWriter($excel, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Federations.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);


        return $response;
    }

}
