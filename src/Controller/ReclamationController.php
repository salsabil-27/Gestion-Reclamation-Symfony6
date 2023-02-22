<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\ReclamationType;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/admin', name: 'admin')]
    public function indexAdmin(): Response
    {
        return $this->render('Admin/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/listreclamation', name: 'list_reclamation')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Reclamation::class); 

        $Reclamation=$repository->findall();
        return $this->render('Reclamation/list.html.twig', [
            'reclamation' => $Reclamation,
        ]);


    }

    #[Route('/suppreclamation/{id}', name: 's')]
   
    public function supprimer($id,request $request ,EntityManagerInterface $em , ManagerRegistry $doctrine): Response
    { 
        $repository= $doctrine->getRepository(Reclamation::class); 
        $Reclamation= $repository->find($id);
        
       $em->remove($Reclamation);
       $em->flush();
       $this->addFlash('message','Reclamtion supprimé avec succèss');
        return $this->redirectToRoute('list_reclamation');


    }
    #[Route('/addreclamation', name: 'a')]

    public function ajouter(Request $request , EntityManagerInterface $em)
    {
        $Reclamation= new Reclamation();
        $form=$this->createForm(ReclamationType::class,$Reclamation);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
        
         $em->persist($Reclamation);
         $em->flush();

         return $this->redirectToRoute('FR');

        }
        return $this->render('reclamation/Add.html.twig',[

            'form'=>$form->createView()
        ]);


    }
    #[Route('/upreclamation/{id}', name: 'up')]

    public function update(ReclamationRepository $repository,Request $request ,$id ,EntityManagerInterface $em,ManagerRegistry $doctrine)
    {  
        $repository= $doctrine->getRepository(Reclamation::class);
        $Reclamation=$repository->find($id);
        $form=$this->createForm(ReclamationType::class,$Reclamation);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {  

            $em->persist($Reclamation);
             $em->flush();
        return $this->redirectToRoute('list_reclamation');

        }
        return $this->render('reclamation/update.html.twig',
        [
            'f'=>$form->createView()
        ]);
    }
    #[Route('/front', name: 'front')]
    public function indexFront(): Response
    {
        return $this->render('Front/home.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
   
    #[Route('/frontreclamation', name: 'FR')]
    public function indexFrontReclamation(): Response
    {
        return $this->render('FrontReclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    
}