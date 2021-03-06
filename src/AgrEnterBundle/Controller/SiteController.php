<?php

namespace AgrEnterBundle\Controller;

use AgrEnterBundle\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Site controller.
 *
 * @Route("site")
 */
class SiteController extends Controller
{

    /**
    * Get lattitude and longitude for each plant.
    *
    * @Route("/site/coordinates/get", name="getCoordinates")
    * @Method("GET", "POST")
    */
    public function getCoordinates($address){
        $apiKey ='AIzaSyCN0IkM9PljUR6ftqU8-0Hn8WWbQ_N9wCE';
        $query = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.$apiKey;
        $return = file_get_contents($query);
        $result = json_decode($return, true);
        return $result;
    }

    /**
     * @Route("/site/coordinates/set", name="setCoordinates")
     *
     */
    public function setCoordinate()
    {
        $em = $this->getDoctrine()->getManager();
        $sites = $em->getRepository('AgrEnterBundle:Site')->findAll();
        var_dump($sites);
        /*foreach ($one as $key => $sites){

            $coord = getCoordinates($one.voie.$one.cp.$one.ville);
            $getLat = $coord['results'][0]['geometry']['location']['lat'];
            $getLng = $coord['results'][0]['geometry']['location']['lng'];
            $line = $theSite['id'].','.$getLat.','.$getLng;
            $myFile = fopen('../../web/coordList.cvs','w+');
            fwrite($myFile,$line);
            fclose($myFile);
        }*/
    }

    /**
     * Lists all site entities.
     *
     * @Route("/", name="site_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sites = $em->getRepository('AgrEnterBundle:Site')->findAll();

        return $this->render('site/index.php.twig', array(
            'sites' => $sites,
        ));
    }

    /**
     * Creates a new site entity.
     *
     * @Route("/new", name="site_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $site = new Site();
        $form = $this->createForm('AgrEnterBundle\Form\SiteType', $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush($site);

            return $this->redirectToRoute('site_show', array('id' => $site->getId()));
        }

        return $this->render('site/new.html.twig', array(
            'site' => $site,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a site entity.
     *
     * @Route("/{id}", name="site_show")
     * @Method("GET")
     */
    public function showAction(Site $site)
    {
        $deleteForm = $this->createDeleteForm($site);

        return $this->render('site/show.html.twig', array(
            'site' => $site,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing site entity.
     *
     * @Route("/{id}/edit", name="site_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Site $site)
    {
        $deleteForm = $this->createDeleteForm($site);
        $editForm = $this->createForm('AgrEnterBundle\Form\SiteType', $site);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_edit', array('id' => $site->getId()));
        }

        return $this->render('site/edit.html.twig', array(
            'site' => $site,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a site entity.
     *
     * @Route("/{id}", name="site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Site $site)
    {
        $form = $this->createDeleteForm($site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush($site);
        }

        return $this->redirectToRoute('site_index');
    }

    /**
     * Creates a form to delete a site entity.
     *
     * @param Site $site The site entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Site $site)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('site_delete', array('id' => $site->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
