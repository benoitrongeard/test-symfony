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
      $em = $this->getDoctrine()->getManager();
      $listAdverts = $em->getRepository("OCPlatformBundle:Advert")->findAll();
      $listCategory = $em->getRepository("OCPlatformBundle:Category")->findAll();

      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
          'listAdverts' => $listAdverts,
          'listCategory' => $listCategory
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

      $advert = new Advert();
      $advert->setTitle('Recherche développeur Symfony.');
      $advert->setAuthor('Alexandre');
      $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");

      $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

      foreach ($listSkills as $skill) {
          $advertSkill = new AdvertSkill();

          $advertSkill->setAdvert($advert);
          $advertSkill->setSkill($skill);

          $advertSkill->setLevel('Expert');

          $em->persist($advertSkill);
      }

      $listCategory = $em->getRepository('OCPlatformBundle:Category')->findAll();

      foreach ($listCategory as $category) {
          $advert->addCategory($category);
      }

      $em->persist($advert);

      $em->flush();

      if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
      }

      return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
  }

  public function editAction($id, Request $request)
  {
    $advert = array(
      'title'   => 'Recherche développpeur Symfony',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }

  public function deleteAction($id)
  {
    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }

  public function listFromCategoryAction($name)
  {
      $em = $this->getDoctrine()->getManager();

      $listAdvert = $em->getRepository('OCPlatformBundle:Advert')->getAdvertWithCategories(urldecode($name));

      echo('bonjour');
      echo(print_r($listAdvert, true));
      die();
      return $listAdvert;
  }
}
