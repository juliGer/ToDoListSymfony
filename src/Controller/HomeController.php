<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Item;

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
      
      if($request->query->has('filtro')){
        $filtro=$request->query->get('filtro');
        switch ($filtro) {
          case "s":
            return $this->render('home/index.html.twig', array('items' => $success,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant']));    	          break;
          case "p":
            return $this->render('home/index.html.twig', array('items' => $pending,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant']));   
          break;
          case "d":
            return $this->render('home/index.html.twig', array('items' => $deleted,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant']));    	
          break;

      } 
      } else{
        return $this->render('home/index.html.twig', array('items' => $success,'success' => $success, 'pending' => $pending , 'deleted' => $deleted , 'countPending' => $countPending[0]['cant'] ,'countSuccess'=> $countSuccess[0]['cant'] ,'countDeleted'=> $countDeleted[0]['cant']));   
    }
    
  }
    
    /**                                                                                   
    * @Route("/ajax",
    * options = { "expose" = true}, 
    * name = "checked",
    * )
    * @method({"POST"})
    */
    public function ajaxAction(Request $request)    
    {
        $entityManager = $this->getDoctrine()->getManager();
        $check = $request->request->get('check');
        $id = $request->request->get('id');
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        if ($check == 1) {
          $item->setChecked(0);
        }elseif ($check == 0) {
          $item->setChecked(1);
        }
        $entityManager->persist($item);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return new JsonResponse(['item'=>$check]);          
    }

    /**                                                                                   
    * @Route("/delete",
    * options = { "expose" = true}, 
    * name = "delete",
    * )
    * @method({"GET"})
    */
    public function delete(Request $request)    
    {
        $entityManager = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
      
        $item->setChecked(2);
        $entityManager->persist($item);
        $entityManager->flush();
        return $this->home($request);         
    }
}

?>