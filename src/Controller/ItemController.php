<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Item;
use App\Form\ItemType;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item")
     */
    public function index()
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
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
        /**                                                                                   
    * @Route("/edit",
    * options = { "expose" = true}, 
    * name = "edit",
    * )
    * @method({"POST"})
    */
    public function edit(Request $request)    
    {
        $entityManager = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);     

      	return new JsonResponse(['name'=>$item->getName(),'status'=>$item->getChecked()]);  
    }

    /**                                                                                   
    * @Route("/getById",
    * options = { "expose" = true}, 
    * name = "getById",
    * )
    * @method({"POST"})
    */
    public function getById(Request $request)    
    {
        $entityManager = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);
        return new JsonResponse(['name'=>$item->getName(),'status'=>$item->getChecked()]);  
    }
}
