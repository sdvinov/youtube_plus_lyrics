<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 * @METHOD("GET")
 */
class UserController extends Controller
{
  /**
   * Displays a form to edit an existing User entity.
   *
   * @Route("/{id}/edit", name="account_edit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, User $user)
  {
    // Create form
    $deleteForm = $this->createDeleteForm($user);
    $editForm = $this->createForm('AppBundle\Form\UserType', $user);
    $editForm->handleRequest($request);
    // If form is submitted and valid
    if ($editForm->isSubmitted() && $editForm->isValid()) {
      // Send data to database
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      // Reload the page
      return $this->redirectToRoute('account_edit', array('id' => $user->getId()));
    }
    // Render edit page
    return $this->render('user/edit.html.twig', array(
      'user' => $user,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
   * Deletes a User entity.
   *
   * @Route("/{id}", name="account_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, User $user)
  {
    // Create delete button
    $form = $this->createDeleteForm($user);
    $form->handleRequest($request);
    // If button is clicked
    if ($form->isSubmitted() && $form->isValid()) {
      // Remove entry from the database
      $em = $this->getDoctrine()->getManager();
      $em->remove($user);
      $em->flush();
    }
    // Logout user
    $this->get('security.context')->setToken(null);
    $this->get('request')->getSession()->invalidate();
    // Redirect to search page
    return $this->redirectToRoute('search');
  }

  /**
   * Creates a form to delete a User entity.
   *
   * @param User $user The User entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(User $user)
  {
    // Create delete button
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('account_delete', array('id' => $user->getId())))
      ->setMethod('DELETE')
      ->getForm()
    ;
  }
}
