<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User; 

class SignupController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $data = $request->request->all();

        if($data !== []) {
            $user = new User();
    
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );
            $user->setEmail($data['email']);
            $user->setName($data['firstName']);
            $user->setSurname($data['surname']);
            $user->setActive(true);
    
            $em = $this->getDoctrine()->getManager();
    
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('app_login'));
        }          
    
        return $this->render('signup/index.html.twig', [
           
        ]);
    }
}
