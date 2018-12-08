<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
  {
      if($page < 1) {
          throw new NotFoundHttpException("Page $page inexistante");
      }

      $nbPerPage = 1;

      $em = $this->getDoctrine()->getManager();
      $listAdverts = $em
          ->getRepository('OCPlatformBundle:Advert')
          ->getAdverts($page, $nbPerPage)
      ;

      $nbPages = ceil(count($listAdverts) / $nbPerPage);

      $listCategory = $em->getRepository("OCPlatformBundle:Category")->findAll();

      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
          'listAdverts' => $listAdverts,
          'listCategory' => $listCategory,
          'nbPages' => $nbPages,
          'page' => $page
      ));
  }

  public function menuAction($limit)
  {
      $em = $this->getDoctrine()->getManager();
      $listAdverts = $em->getRepository("OCPlatformBundle:Advert")->findBy(array(), array('date' => 'DESC'), $limit);

      return $this->render('@OCPlatform/Advert/menu.html.twig', array(
         'listAdverts' => $listAdverts
      ));
  }

  public function viewAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em
          ->getRepository('OCPlatformBundle:Advert')
          ->find($id)
      ;

      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $listApplications = $em
          ->getRepository('OCPlatformBundle:Application')
          ->findBy(array('advert' => $advert))
      ;

      $listAdvertSkills = $em
          ->getRepository('OCPlatformBundle:AdvertSkill')
          ->findBy(array('advert' => $advert))
      ;

      return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
          'advert'           => $advert,
          'listApplications' => $listApplications,
          'listAdvertSkills' => $listAdvertSkills
      ));
  }

  public function addAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
      }

      return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
      }

      return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
          'advert' => $advert
      ));
  }

  public function deleteAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      foreach ($advert->getCategories() as $category) {
          $advert->removeCategory($category);
      }

      $em->flush();

      return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }

  public function listFromCategoryAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->getAdvertWithCategories(urldecode($id));

      return $this->render('OCPlatformBundle:Advert:list.html.twig', array(
          'listAdverts' => $listAdverts
      ));
  }
}
