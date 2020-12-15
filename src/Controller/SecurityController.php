<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="Login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Login";
        $info['menu2'] = "Register";

        if ($this->getUser()) {
            $this->addFlash('error', 'Must logout before logging in again.');
            return $this->redirectToRoute('Home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig',
            ['last_username' => $lastUsername,
            'error' => $error,
            'info' => $info]);
    }

    /**
     * @Route("/Logout", name="Logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/logout_message", name="logout_message")
     */
    public function logoutMessage()
    {
        $this->addFlash('standard', "See you back soon. Bye!");
        return $this->redirectToRoute('Home');
    }

    /**
     * @Route("/login_message", name="login_message")
     */
    public function loginMessage()
    {
        $this->addFlash('success', "It's here".$this->getUser());
        return $this->redirectToRoute('Home');
    }
}
