<?php

namespace App\Controller;

use App\Entity\CategorieReclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategorieReclamationType;
use App\Repository\CategorieReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\Manifest;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class CategoriereclamationController extends AbstractController
{
    #[Route('/categoriereclamation', name: 'app_categoriereclamation')]
    public function index(): Response
    {
        return $this->render('categoriereclamation/index.html.twig', [
            'controller_name' => 'CategorieReclamationController',
        ]);
    }
    public function indexAdmin(): Response
    {
        return $this->render('Admin/index.html.twig', [
            'controller_name' => 'CategorieReclamationController',
        ]);
    }
    
    #[Route('/listnew', name: 'listnew')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Categoriereclamation::class); 

        $CategorieReclamation=$repository->findall();
        return $this->render('categoriereclamation/listnew.html.twig', [
            'categoriereclamation' => $CategorieReclamation,
        ]);


    }

    #[Route('/supprission/{id}', name: 'su')]
   
    public function supprimer($id,request $request , EntityManagerInterface $em , ManagerRegistry $doctrine ): Response
    {
        
        $repository= $doctrine->getRepository(Categoriereclamation::class);
        $CategorieReclamation=$repository->find($id);
      
        $em->remove($CategorieReclamation);
        $em->flush();
        return $this->redirectToRoute('listnew');


    }
    #[Route('/ajout', name: 'add')]

    public function ajouter(Request $request ,EntityManagerInterface $em)
    {
        $CategorieReclamation= new Categoriereclamation();
        $form=$this->createForm(CategorieReclamationType::class,$CategorieReclamation);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
        
         $em->persist($CategorieReclamation);
         $em->flush();

         return $this->redirectToRoute('listnew');

        }
        return $this->render('categoriereclamation/ajout.html.twig',[

            'form'=>$form->createView()
        ]);


    }
    #[Route('/upC/{id}', name: 'update')]

    public function update(CategorieReclamationRepository $repository,Request $request ,$id , EntityManagerInterface $em,ManagerRegistry $doctrine)
    {   
        $repository= $doctrine->getRepository(Categoriereclamation::class);
        $cReclamation=$repository->find($id);
        $form=$this->createForm(CategorieReclamationType::class,$cReclamation);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {
         $em->persist($cReclamation);
        $em->flush();
        return $this->redirectToRoute('listnew');

        }
        return $this->render('categoriereclamation/updateC.html.twig',
        [
            'f'=>$form->createView()
        ]);
    }
}
