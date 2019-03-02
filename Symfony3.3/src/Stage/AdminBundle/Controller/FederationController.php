<?php

namespace Stage\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stage\AdminBundle\Entity\Federation;
use Stage\AdminBundle\Entity\Joueur;
use Stage\AdminBundle\Entity\Pays;
use Stage\AdminBundle\Form\PaysType;
use Stage\AdminBundle\StageAdminBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Federation controller.
 *
 * @Route("federation")
 */
class FederationController extends Controller
{
    /**
     * Lists all federation entities.
     *
     * @Route("/{_locale}", name="federation_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {     $variable = array();
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('StageAdminBundle:Federation')->findAll();
         $variable['nb']= $em->getRepository("StageAdminBundle:Federation")->countAll();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */

         $paginator = $this->get('knp_paginator');
         $federations= $paginator->paginate(
              $blog,
              $request->query->getInt('page' ,1) ,
              $request->query->getInt('limit', 4)
          );
        return $this->render('StageAdminBundle:federation:index.html.twig', array(
            'federations' => $federations,
            'variables'=> $variable,
        ));
    }

    /**
     * @Route("/search/{_locale}" ,name="search_filter")
     */
    public function searchAction(Request $request)
    { $variable = array();
        $em = $this->getDoctrine()->getManager();
        $variable['nb']= $em->getRepository("StageAdminBundle:Federation")->countAll();
        $term = $request->query->get('search-term');
        $repository = $this->getDoctrine()
            ->getRepository(Federation::class);

        $query = $repository->createQueryBuilder('f')
            ->Join(Pays::class,'p','p.nom==f.nomPays')
            ->Where('f.nom LIKE :value OR f.sigle LIKE :value  OR f.nomPays = :value OR f.creation like :value OR f.president LIKE :value')
            ->setParameter('value', '%'.$term.'%')
            ->orderBy('f.nom', 'ASC')
            ->getQuery();

        $blog = $query->getResult();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $federations= $paginator->paginate(
            $blog,
            $request->query->getInt('page' ,1) ,
            $request->query->getInt('limit', 4)
        );

        return $this->render('StageAdminBundle:federation:index.html.twig', array(
            'federations' => $federations,
            'variables'=> $variable,
        ));

          }

    /**
     * Creates a new federation entity.
     *
     * @Route("/new/{_locale}", name="federation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {   $variable = array();
        $em = $this->getDoctrine()->getManager();

        $variable['nb']= $em->getRepository("StageAdminBundle:Federation")->countAll();
        $federation = new Federation();
        $form = $this->createForm('Stage\AdminBundle\Form\FederationType', $federation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($federation);

            $em->flush();

            return $this->redirectToRoute('federation_show', array('id' => $federation->getId()));
        }

        return $this->render('StageAdminBundle:federation:new.html.twig', array(
            'federation' => $federation,
            'form' => $form->createView(),
            'variables' =>$variable,
        ));
    }

    /**
     * Finds and displays a federation entity.
     *
     * @Route("/{id}/{_locale}", name="federation_show")
     * @Method("GET")
     */
    public function showAction(Federation $federation)
    {    $variable = array();
        $em = $this->getDoctrine()->getManager();

        $variable['nb']= $em->getRepository("StageAdminBundle:Federation")->countAll();
        $deleteForm = $this->createDeleteForm($federation);

        return $this->render('StageAdminBundle:federation:show.html.twig', array(
            'federation' => $federation,
            'delete_form' => $deleteForm->createView(),
            'variables' => $variable,
        ));
    }

    /**
     * Displays a form to edit an existing federation entity.
     *
     * @Route("/{id}/edit/{_locale}", name="federation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Federation $federation)
    {   $variable = array();
        $em = $this->getDoctrine()->getManager();

        $variable['nb']= $em->getRepository("StageAdminBundle:Federation")->countAll();
        $deleteForm = $this->createDeleteForm($federation);
        $editForm = $this->createForm('Stage\AdminBundle\Form\FederationType', $federation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('federation_edit', array('id' => $federation->getId()));
        }

        return $this->render('StageAdminBundle:federation:edit.html.twig', array(
            'federation' => $federation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'variables' => $variable,
        ));
    }

    /**
     * Deletes a federation entity.
     *
     * @Route("/{id}/{_locale}", name="federation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Federation $federation)
    {
        $form = $this->createDeleteForm($federation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($federation);
            $em->flush();
        }

        return $this->redirectToRoute('federation_index');
    }

    /**
     * Creates a form to delete a federation entity.
     *
     * @param Federation $federation The federation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Federation $federation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('federation_delete', array('id' => $federation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
