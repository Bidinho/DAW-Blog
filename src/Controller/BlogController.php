<?php

namespace App\Controller;

use App\Repository\MicropostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $info = $this->get_User();
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'info' => $info
        ]);
    }


    function get_User()
    {
        $info['menu0'] = 'Home';
        $info['menu1'] = "Login";
        $info['menu2'] = "Register";
        $info['uName'] = "";
        $info['uId'] = "";

        if ($this->getUser()) {
            $info['uName'] = $this->getUser()->getUsername();
            $info['uId'] = $this->getUser()->getId();
            $info['menu1'] = "Post";
            $info['menu2'] = "Logout";
        }

        return $info;
    }

    /**
     * @Route("/post/{postId?}", name="Post")
     * @param Request $request
     * @return Response
     */
    public function post(Request $request, MicropostsRepository $micropostsRepository): Response
    {
        $user = $this->getUser();
        if ($user == NULL) {
            $this->addFlash('error', 'You must login in first');
            return $this->redirect($this->generateUrl('Home'));
        }
        $content = '';
        $info = $this->get_User();
        $postId = $request->get('postId');
        if ($postId) {
            $content = $micropostsRepository->getContentById($postId);
        }
        return $this->render('post/post.html.twig', [
            'postId' => $postId,
            'info' => $info,
            'content' => $content
        ]);
    }

    /**
     * @Route("/post_blog/{postId?}", name="post_blog")
     * @param Request $request
     * @return Response
     */
    public function post_blog(Request $request, MicropostsRepository $micropostsRepository): Response
    {
        $content = $request->get('content');
        $user = $this->getUser();
        $postId = $request->get('postId');
        $postUid = $micropostsRepository->getPostUid($postId);
        if ($user == NULL) {
            $this->addFlash('error', 'You must login in first');
            return $this->redirect($this->generateUrl('Home'));
        } else if ($postUid && ($user->getId() != $postUid)) {
            $this->addFlash('error', 'You can not edit other user\'s posts');
            return $this->redirect($this->generateUrl('Home'));
        } else if ($postId) {
            $this->addFlash('success', 'Post updated with success');
            $micropostsRepository->updatePost($content, $postId);
            return $this->redirect($this->generateUrl('Home'));
        } else {
            $this->addFlash('success', 'New post created with success');
            $micropostsRepository->insertPost($content, $user);
            return $this->redirect($this->generateUrl('Home'));
        }
    }


}
