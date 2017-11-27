<?php

namespace LFR\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function fetchText($role){
      $textRepository = $this->getDoctrine()->getManager()->getRepository('LFRStoreBundle:Text');
      return $textRepository->findBy(array('role' => $role))[0];
    }
    public function fetchImage($role){
      $textRepository = $this->getDoctrine()->getManager()->getRepository('LFRStoreBundle:Image');
      $image = $textRepository->findBy(array('role' => $role))[0];
      $filename = $image->getImage();
      $imagehandler = $this->container->get('lfr_store.imagehandler');
      $path_small_image = $imagehandler->get_image_in_quality($filename, 'md');
      $image->small_image = $path_small_image;
      return $image;
    }
    public function workAction()
    {
        return $this->render('LFRStoreBundle:Home:work.html.twig');
    }
    public function animationAction()
    {
        return $this->render('LFRStoreBundle:Home:animation.html.twig');
    }
    public function homepageAction()
    {
        return $this->render('LFRStoreBundle:Home:home_page.html.twig');
    }
    public function homeAction()
    {
        $text = [];
        $text['home']['title']['left'] = $this->fetchText('home:title:left');
        $text['home']['text']['left'] = $this->fetchText('home:text:left');
        $text['home']['btn']['left'] = $this->fetchText('home:btn:left');
        $text['home']['left_img'] = $this->fetchImage('home:left_img');
        $text['home']['title']['right'] = $this->fetchText('home:title:right');
        $text['home']['text']['right'] = $this->fetchText('home:text:right');
        $text['home']['btn']['right'] = $this->fetchText('home:btn:right');
        $text['home']['right_img'] = $this->fetchImage('home:right_img');

        $text['home']['collection']['title'] = $this->fetchText('home:collection:title');
        $text['home']['creation']['title'] = $this->fetchText('home:creation:title');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LFRStoreBundle:Collection');
        $collections = $repository->findBy(array(), array('id' => 'desc'));
        $imagehandler = $this->container->get('lfr_store.imagehandler');
        foreach ($collections as $collection) {
          $filename = $collection->getImage();
          $path_small_image = $imagehandler->get_image_in_quality($filename, 'sm');
          $collection->small_image = $path_small_image;
        }

        $repository = $em->getRepository('LFRStoreBundle:Creation');
        $creations = $repository->findBy(array(), array('id' => 'desc'));
        $imagehandler = $this->container->get('lfr_store.imagehandler');
        foreach ($creations as $creation) {
          $fileNames = $creation->getImages();
          $path_small_image = $imagehandler->get_image_in_quality($fileNames[0], 'xs');
          $creation->small_image = $path_small_image;
        }
        return $this->render('LFRStoreBundle:Home:home_2.html.twig', array(
          'data' => $text,
          'collections' => $collections,
          'creations' => $creations
        ));
    }
}
