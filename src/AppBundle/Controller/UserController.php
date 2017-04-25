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
    $deleteForm = $this->createDeleteForm($user);
    $editForm = $this->createForm('AppBundle\Form\UserType', $user);
    $editForm->handleRequest($request);
    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('account_edit', array('id' => $user->getId()));
    }
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
    $form = $this->createDeleteForm($user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($user);
      $em->flush();
    }
    $this->get('security.context')->setToken(null);
    $this->get('request')->getSession()->invalidate();
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
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('account_delete', array('id' => $user->getId())))
      ->setMethod('DELETE')
      ->getForm()
    ;
  }
}
