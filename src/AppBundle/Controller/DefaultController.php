<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request)
  {
    // If user is logged in
    if ($this->get('security.context')->isGranted('ROLE_USER'))
    {
      // Get info about logged in user
      $username = $this->get('security.context')->getToken()->getUser();
      $username->getUsername();
    }
    else
    {
      // If user is anonymous
      $username = null;
    }
    // Render default template and pass variables
    return $this->render('default/index.html.twig', array(
      'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      'username' => $username
    ));
  }
}
