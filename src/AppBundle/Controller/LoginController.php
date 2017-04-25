<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/login", name="login")
 */

class LoginController extends Controller
{
	/**
   * @Route("/", name="login")
   */
  public function login(Request $request)
  {
		// If authentication was not successful
    if (!$this->get('security.context')->isGranted('ROLE_USER'))
    {
			// Get error
    	$authenticationUtils = $this->get('security.authentication_utils');
    	$error = $authenticationUtils->getLastAuthenticationError();
    	return $this->render('user/login.html.twig', array(
        'error'         => $error,
      ));
		}
		else
		{
			// If user is already logged in, show message and redirect to search page
			$this->addFlash('loggedin', 'You are already logged in!');
			return $this->redirect($this->generateUrl('search'));
		}
	}
}
?>
