<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

 /**
* @Route("/dash", name="dash")
*/

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     * @param PostsRepository $postRepository
     * @return Response
     */
    public function index(PostsRepository $postRepository, UserRepository $user)
    {
        $author = $this->getUser()->getId();

        $posts = $postRepository->findBy([
            'author' => $author
        ]);

        $authorInfo = $user->findBy([
            'id' => $author
        ]);

        return $this->render('post/index.html.twig', [
            'posts'=> $posts,
            'user'=> $authorInfo[0]
        ]);
    }

     /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        // Create new post
        $post = new Posts();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            
            date_default_timezone_set('Africa/Johannesburg');
    
            $date = new \DateTime();
            $author = $this->getUser()->getId();
    
            $post->setAuthor($author);
            $post->setDate($date);
        
            // entity manager
            $em = $this->getDoctrine()->getManager();
            /**
             * @var UploadedFile $file
             */
            $file = dump($request->files->get('post')['attachment']);
            if($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

                 $file->move (
                     $this->getParameter('uploads_dir'),
                     $filename
                 );

                $post->setImage($filename);           
            }

    
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('dashpost'));
        }


        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /** 
     * @Route("/show/{id}", name="show")
     * @param Posts $posts
     * @return Response
     */
    public function show(Posts $posts) {
        // Create the show view
        return $this->render('post/show.html.twig', [
            'post' => $posts
        ]);
    }

    /** 
     * @Route("/delete/{id}", name="delete")
     */
    public function remove(Posts $posts) {
        $em = $this->getDoctrine()->getManager();

        $em->remove($posts);
        $em->flush();

        $this->addFlash('success','Your post was removed');

        return $this->redirect($this->generateUrl('dashpost'));
    }
}
