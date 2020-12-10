<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/Register", name="Register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Login";
        $info['menu2'] = "Register";

        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPasswordDigest());
            $user->setPasswordDigest($password);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registo Completo!
            Bem-vindo ' . $user->getName());
            return $this->redirect($this->generateUrl('Home'));
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'info' => $info
        ]);
    }

}
