<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
     /**
     * @Route("/blog", name="blog")
     * @param PostsRepository $postRepository
     * @return Response
     */
    public function index(PostsRepository $postRepository)
    {

        $posts = $postRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts'=> $posts
        ]);
    }


      /**
     * @Route("/blog/article/{id}", name="article")
     * @param Posts $posts
     * @return Response
     */
    public function article(Posts $posts,UserRepository $user )
    {
        $author = $posts->getAuthor();

        $authorInfo = $user->findBy([
            'id' => $author
        ]);

        
        return $this->render('article/index.html.twig', [
            'post' => $posts,
            'user' => $authorInfo[0]
        ]);
    }
}
