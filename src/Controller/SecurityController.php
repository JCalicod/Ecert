<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route({
 *     "en": "/security",
 *     "fr": "/securite"
 * })
 */
class SecurityController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/login",
     *     "fr": "/connexion"
     * }, name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() && $this->getUser()->getRoles() && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('security_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route({
     *     "en": "/logout",
     *     "fr": "/deconnexion"
     * }, name="app_logout")
     */
    public function logout()
    {
        return $this->redirect($this->generateUrl('security_home'));
    }

    /**
     * @Route({
     *     "en": "/home",
     *     "fr": "/accueil"
     * }, name="security_home")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function home()
    {
        return $this->render('security/home.html.twig', []);
    }
}