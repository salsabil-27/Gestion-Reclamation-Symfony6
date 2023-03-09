<?php

namespace App\Controller;

use App\Entity\TypeEvenement;
use App\Form\TypeEvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class TypeEvenementController extends AbstractController
{
    #[Route('/type/evenement', name: 'app_type_evenement')]
    public function index(): Response
    {
        return $this->render('type_evenement/index.html.twig', [
            'controller_name' => 'TypeEvenementController',
        ]);
    }
    #[Route('/listType', name: 'l')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(TypeEvenement::class); 

        $TypeEvenement =$repository->findall();
        return $this->render('type_evenement/listType.html.twig', [
            'type' => $TypeEvenement,
        ]);


    }

    #[Route('/suppType/{id}', name: 'supprimer')]
   
    public function supprimer($id,request $request ,EntityManagerInterface $em ,ManagerRegistry $doctrine ): Response
    {
        $repository= $doctrine->getRepository(TypeEvenement::class); 
        $TypeEvenement=$repository->find($id);
     
       $em->remove($TypeEvenement);
       $em->flush();
        return $this->redirectToRoute("l");


    }



    #[Route('/addType', name: 'ajouter')]

    public function ajouter(Request $request ,EntityManagerInterface $em )
    {
        $TypeEvenement= new TypeEvenement();
        $form=$this->createForm(TypeEvenementType::class,$TypeEvenement);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
        
         $em->persist($TypeEvenement);
         $em->flush();

         return $this->redirectToRoute('l');

        }
        return $this->render('type_evenement/Add.html.twig',[

            'form'=>$form->createView()
        ]);


    }
    
    #[Route('upType/{id}', name: 'update')]

    public function update(Request $request ,$id ,EntityManagerInterface $em,ManagerRegistry $doctrine)
    { $repository= $doctrine->getRepository(TypeEvenement::class); 
        $TypeEvenement=$repository->find($id);
        $form=$this->createForm(TypeEvenementType::class,$TypeEvenement);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {
            $em->persist( $TypeEvenement);
        $em->flush();
        return $this->redirectToRoute('l');

        }
        return $this->render('type_evenement/update.html.twig',
        [
            'f'=>$form->createView()
        ]);
    }
   

}

