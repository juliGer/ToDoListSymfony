<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Item;
use App\Form\ItemType;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", 
    * options = { "expose" = true},
     * name="home",)
     * @method({"GET","POST"})
     */
    public function home(Request $request)
    {
    	$items=$this->getDoctrine()->getRepository(Item::class)->findAll();
    	$success=$this->getDoctrine()->getRepository(Item::class)->findAllSuccess();
      $pending=$this->getDoctrine()->getRepository(Item::class)->findAllPending();
      $deleted=$this->getDoctrine()->getRepository(Item::class)->findAllDeleted();
    	$countSuccess=$this->getDoctrine()->getRepository(Item::class)->findCountSuccess();
      $countPending=$this->getDoctrine()->getRepository(Item::class)->findCountPending();    
      $countDeleted=$this->getDoctrine()->getRepository(Item::class)->findCountDeleted();  

      $item = new Item();
      $form = $this->createForm(ItemType::class,$item);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){        
        $entityManager = $this->getDoctrine()->getManager();
        $id = $form->get('id')->getData();
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id); 
        $name = $form->get('name')->getData();
        $item = $item ->setName($name);
        $entityManager->persist($item);
        $entityManager->flush();
        return $this->redirectToRoute('home');
      }
      if($request->query->has('filtro')){
        $filtro=$request->query->get('filtro');
        switch ($filtro) {
          case "s":
            return $this->render('home/index.html.twig', array('items' => $success,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant'],"form" => $form->createView()));    	          break;
          case "p":
            return $this->render('home/index.html.twig', array('items' => $pending,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant'],"form" => $form->createView()));   
          break;
          case "d":
            return $this->render('home/index.html.twig', array('items' => $deleted,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant'],"form" => $form->createView()));    	
          break;

      } 
      } else{
        return $this->render('home/index.html.twig', array('items' => $success,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant'],"form" => $form->createView()));   
    }
    
  }
    


}

?>