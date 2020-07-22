<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
    	$user = new User();
    	$form = $this->createForm(UserType::class,$user);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$entityManager = $this->getDoctrine()->getManager();
    		$user->setRoles(["ROLE_USER"]);
            $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            )
        );            
    		$entityManager->persist($user);
    		$entityManager->flush();
    		return $this->redirectToRoute('app_login');
    	}

        return $this->render("register/index.html.twig" , ['controller_name' => "register","form" => $form->createView()]);
       
    }
}
