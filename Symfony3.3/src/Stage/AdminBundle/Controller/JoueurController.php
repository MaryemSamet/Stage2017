<?php

namespace Stage\AdminBundle\Controller;

use Stage\AdminBundle\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Joueur controller.
 *
 * @Route("joueur")
 */
class JoueurController extends Controller
{
    /**
     * Lists all joueur entities.
     *
     * @Route("/{_locale}", name="joueur_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {    $variable = array();
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('StageAdminBundle:Joueur')->findAll();
        $variable['nb']= $em->getRepository("StageAdminBundle:Joueur")->countAll();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $joueurs= $paginator->paginate(
            $blog,
            $request->query->getInt('page' ,1) ,
            $request->query->getInt('limit', 4)
        );
        return $this->render('StageAdminBundle:joueur:index.html.twig', array(
            'joueurs' => $joueurs,
            'variables'=> $variable,
        ));
    }

    /**
     * @Route("/searchj/{_locale}" ,name="search_filter_joueur")
     */
    public function searchAction(Request $request)
    { $variable = array();
        $em = $this->getDoctrine()->getManager();
        $variable['nb']= $em->getRepository("StageAdminBundle:Joueur")->countAll();
        $term = $request->query->get('search-term');
        $repository = $this->getDoctrine()
            ->getRepository(Joueur::class);

        $query = $repository->createQueryBuilder('j')
            ->Where('j.nom LIKE :value OR  j.datenaiss LIKE :value ')
            ->setParameter('value', '%'.$term.'%')
            ->orderBy('j.nom', 'ASC')
            ->getQuery();

        $blog = $query->getResult();

        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $joueurs= $paginator->paginate(
            $blog,
            $request->query->getInt('page' ,1) ,
            $request->query->getInt('limit', 4)
        );

        return $this->render('StageAdminBundle:joueur:index.html.twig', array(
            'joueurs' => $joueurs,
            'variables'=> $variable,
        ));

    }


    /**
     * Creates a new joueur entity.
     *
     * @Route("/new/{_locale}", name="joueur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {   $variable = array();
        $em = $this->getDoctrine()->getManager();
        $variable['nb']= $em->getRepository("StageAdminBundle:Joueur")->countAll();
        $joueur = new Joueur();
        $form = $this->createForm('Stage\AdminBundle\Form\JoueurType', $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($joueur);
            $em->flush();

            return $this->redirectToRoute('joueur_show', array('id' => $joueur->getId()));
        }

        return $this->render('StageAdminBundle:joueur:new.html.twig', array(
            'joueur' => $joueur,
            'form' => $form->createView(),
            'variables' => $variable,
        ));
    }

    /**
     * Finds and displays a joueur entity.
     *
     * @Route("/{id}/{_locale}", name="joueur_show")
     * @Method("GET")
     */
    public function showAction(Joueur $joueur)
    {    $variable = array();
        $em = $this->getDoctrine()->getManager();
        $variable['nb']= $em->getRepository("StageAdminBundle:Joueur")->countAll();
        $deleteForm = $this->createDeleteForm($joueur);

        return $this->render('StageAdminBundle:joueur:show.html.twig', array(
            'joueur' => $joueur,
            'delete_form' => $deleteForm->createView(),
            'variables' => $variable,
        ));
    }

    /**
     * Displays a form to edit an existing joueur entity.
     *
     * @Route("/{id}/edit/{_locale}", name="joueur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Joueur $joueur)
    {    $variable = array();
        $em = $this->getDoctrine()->getManager();
        $variable['nb']= $em->getRepository("StageAdminBundle:Joueur")->countAll();
        $deleteForm = $this->createDeleteForm($joueur);
        $editForm = $this->createForm('Stage\AdminBundle\Form\JoueurType', $joueur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('joueur_edit', array('id' => $joueur->getId()));
        }

        return $this->render('StageAdminBundle:joueur:edit.html.twig', array(
            'joueur' => $joueur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'variables' => $variable,
        ));
    }

    /**
     * Deletes a joueur entity.
     *
     * @Route("/{id}/{_locale}", name="joueur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Joueur $joueur)
    {
        $form = $this->createDeleteForm($joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($joueur);
            $em->flush();
        }

        return $this->redirectToRoute('joueur_index');
    }

    /**
     * Creates a form to delete a joueur entity.
     *
     * @param Joueur $joueur The joueur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Joueur $joueur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('joueur_delete', array('id' => $joueur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
