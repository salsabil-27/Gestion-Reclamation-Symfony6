<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\FavoriteEvent;
use App\Entity\TypeEvenement;

use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }




    
    





    #[Route('/list', name: 'list_event')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Evenement::class); 

        $Evenement=$repository->findall();
        return $this->render('evenement/list.html.twig', [
            'evenement' => $Evenement,
        ]);


    }


    #[Route('/supp/{id}', name: 's')]
   
    public function supprimer($id,Request $request ,EntityManagerInterface $em ,ManagerRegistry $doctrine  ): Response
    { $repository= $doctrine->getRepository(Evenement::class); 
        $Evenement=$repository->find($id);
       
      
       $em->remove($Evenement);
       $em->flush();
        return $this->redirectToRoute('list_event');


    }

    #[Route('/add', name: 'a')]

    public function ajouter( Request  $request ,EntityManagerInterface $em ): Response
    {
        $Evenement= new Evenement();
        $form=$this->createForm(EvenementType::class,$Evenement);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
          
            $imageFile = $form->get('imageEvenement')->getData();
            $Filename =  md5(uniqid()).'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $Filename
                );
            } catch (FileException $e) {

            }

            $Evenement->setimageEvenement($Filename);
    

         $em->persist($Evenement);
         $em->flush();

         return $this->redirectToRoute('list_event');

        }
        return $this->render('evenement/ajouter.html.twig',[

            'form'=>$form->createView()
        ]);


    }





    #[Route('/up/{id}', name: 'u')]

    public function update(Request $request ,$id ,EntityManagerInterface $em,ManagerRegistry $doctrine )
    {
        $repository= $doctrine->getRepository(Evenement::class); 
        $Evenement=$repository->find($id);
        $form=$this->createForm(EvenementType::class,$Evenement);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {
            $imageFile = $form->get('imageEvenement')->getData();
            $Filename = md5(uniqid()).'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $Filename
                );
            } catch (FileException $e) {

            }

            $Evenement->setImageEvenement($Filename);


         $em->persist($Evenement);
        $em->flush();
        return $this->redirectToRoute('list_event');

        }
        return $this->render('evenement/modifier.html.twig',
        [
            'f'=>$form->createView()
        ]);
    }
    #[Route('/DisplayE ', name: 'DisplayEvenement')]
    public function DisplayEvenement(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Evenement::class); 

        $Evenement=$repository->findall();
        return $this->render('Front/DisplayEvenement.html.twig', [
            'evenement' => $Evenement,
        ]);


    }
    #[Route('/home ', name: 'home')]
    public function home (ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Evenement::class); 

        $Evenement=$repository->findall();
        return $this->render('Front/Home.html.twig', [
            'evenement' => $Evenement,
        ]);}
        #[Route('/about ', name: 'about')]
        public function about (ManagerRegistry $doctrine): Response
        {
            $repository= $doctrine->getRepository(Evenement::class); 
    
            $Evenement=$repository->findall();
            return $this->render('Front/about.html.twig', [
                'evenement' => $Evenement,
            ]);}
      
            #[Route('/list/{id}', name: 'details')]
            public function afficherEvent(Evenement $Evenement): Response
            {
               
                return $this->render('Front/details.html.twig', [
                    'evenement' => $Evenement,
                ]);
        
        
            }
             
        
             
            #[Route('/recherche', name: 'recherche')]

            public function recherche(EvenementRepository $repository, Request $request)
            {
                
                $data=$request->get('search');
                $Evenement =$repository->findBy(['titreEvent'=>$data]);
              
              
                return $this->render('evenement/list.html.twig', [
                    'evenement' => $Evenement,
                ]);
            
        }


          #[Route('/stats ', name: 'stats')]
          public function stats(EntityManagerInterface $entityManager): Response
          {
              // Retrieve the list of favorites for the current user
              $favorites = $entityManager->getRepository(FavoriteEvent::class)->findBy(['user' => $this->getUser()]);
          
              // Create an array of event IDs from the favorites
              $eventIds = array_map(function($favorite) {
                  return $favorite->getEvents()->getId();
              }, $favorites);
          
              // Count the number of times each event ID appears in the array
              $eventCounts = array_count_values($eventIds);
          
              // Retrieve the events associated with the favorite event IDs
              $events = $entityManager->getRepository(Evenement::class)->findById(array_keys($eventCounts));
          
              // Sort the events by the number of times they appear in the array
              usort($events, function($a, $b) use ($eventCounts) {
                  return $eventCounts[$b->getId()] <=> $eventCounts[$a->getId()];
              });
          
              // Get the top 7 events with the most favorites
              $topEvents = array_slice($events, 0, 7);
          
              // Create an array of labels and data for the line chart
              $labels = array_map(function($event) {
                  return $event->getTitreEvent();
              }, $topEvents);
              $data = array_map(function($event) use ($eventCounts) {
                  return $eventCounts[$event->getId()];
              }, $topEvents);
          
              return $this->render('evenement/stats.html.twig', [
                  'labels' => $labels,
                  'data' => $data,
              ]);
    }
}