<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class HomeController
 * @package App\Controller
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/ecert", name="ecert")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function ecert()
    {
        return $this->render('ecert.html.twig', []);
    }

    /**
     * @Route({
     *     "en": "/logout",
     *     "fr": "/deconnexion"
     * }, name="ecert_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/", name="ecert_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() && $this->getUser()->getRoles() && in_array('ROLE_ECERT', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('ecert');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}