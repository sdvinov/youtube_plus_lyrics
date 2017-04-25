<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/register", name="user_registration")
 */

class RegistrationController extends Controller
{
  /**
   * @Route("/", name="user_registration")
   */
  public function registerAction(Request $request)
  {
    // Create new user
    $user = new User();
    // Create form
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    // If form is submitted and info is valid
    if ($form->isSubmitted() && $form->isValid()) {
      // Encode password
      $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
      $user->setPassword($password);
      // Send data to database
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      // Redirect upon success
      return $this->redirectToRoute('search');
    }
    // Render form
    return $this->render(
      'user/register.html.twig',
      array('form' => $form->createView())
    );
  }
}
?>
