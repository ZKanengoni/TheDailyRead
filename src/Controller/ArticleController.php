<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

 
class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     * @param Posts $posts
     * @return Response
     */
    public function index()
    {
        return $this->render('article/index.html.twig', [
            // 'post' => $posts
        ]);
    }
}
