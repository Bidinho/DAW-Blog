<?php

namespace App\Controller;

use App\Entity\Microposts;
use App\Repository\MicropostsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("/", name="Home")
     */
    public function index(MicropostsRepository $micropostsRepository): Response
    {
        $session = new Session();
        $session->start();

        $posts = $micropostsRepository->getPosts();
        $info = $this->get_User($session);
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'info' => $info
        ]);
    }


    function get_User(Session $session)
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Login";
        $info['menu2'] = "Register";
        $info['uName'] = "";
        $info['uId'] = "";

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

//        $_COOKIE['rememberMe'] = 1;
//        if (!empty($_COOKIE['rememberMe'])) {
//            $qb->select('id')
//                ->from('Users', 'u')
//                ->where('u.remember_digest = cookieRemember_digest')
//                ->setParameter('cookieRemember_digest', $_COOKIE['rememberMe'])->getQuery()->getResult();
//            $_SESSION['userName'] = $qb[0]['name'];
//            $_SESSION['userId'] = $qb[0]['id'];
//            dump($_SESSION);
//        }
        if ($this->getUser()) {
            $info['uName'] = $this->getUser()->getUsername();
            $info['uId'] = $this->getUser()->getId();
            $info['menu1'] = "Post";
            $info['menu2'] = "Logout";
        }

        return $info;
    }

    function getMenu()
    {

    }

}
