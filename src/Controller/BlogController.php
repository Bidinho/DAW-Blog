<?php

namespace App\Controller;

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
    public function index(UsersRepository $usersRepository, MicropostsRepository $micropostsRepository): Response
    {
        $session = new Session();
        $session->start();

        $posts = $this->getPosts();
        $info = $this->get_User($session);
        return $this->render('home/index.html.twig', [
             'posts' => $posts,
            'info' => $info
        ]);
    }



    function getPosts() {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = "SELECT microposts.id as postId, users.id as userId, users.name, microposts.content, microposts.created_at, microposts.updated_at, microposts.likes
FROM microposts,users
WHERE microposts.user_id = users.id ORDER by microposts.updated_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAllAssociative();
    }

    function get_User(Session $session) {
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


        dump($session);
        if (!empty($session->get('username'))) {
            $info['uName'] = $session->get('username');
            $info['uId'] = $session->get('userId');
            $info['menu1'] = "Post";
            $info['menu2'] = "Logout";
        }

        return $info;
    }

    function getMenu() {

    }

}
