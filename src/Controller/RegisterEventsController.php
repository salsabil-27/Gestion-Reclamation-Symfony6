<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\RegisterEvenement;
use App\Form\RegisterEventsType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterEventsController extends AbstractController
{
    #[Route('/register/events', name: 'app_register_events')]
    public function index(): Response
    {
        return $this->render('register_events/index.html.twig', [
            'controller_name' => 'RegisterEventsController',
        ]);
    }
 /**
 * @Route("/event/{id}/register", name="event_register")
 */
public function register(Request $request, $id, EntityManagerInterface $entityManager)
{
    $evenement = $entityManager->getRepository(Evenement::class)->find($id);
    
dump("test",$evenement);
    if (!$evenement) {
        throw $this->createNotFoundException(
            'No event found for id '.$id
        );
    }

    $registration = new RegisterEvenement();
    $registration->setEvenement($evenement);
    dump($registration);
    $form1 = $this->createForm(RegisterEventsType::class, $registration);
    $form1->add('Ajouter', SubmitType::class);
    $form1->handleRequest($request);
    if ($form1->isSubmitted() && $form1->isValid()) {
        // handle the registration
        // save the registration to the database       
         dump("testform");


        $entityManager->persist($registration);
        $entityManager->flush();

        // redirect the user to the event page
        return $this->redirectToRoute('list_event', ['id' => $evenement->getId()]);
    }
    return $this->render('registerEvents/registerEvents.html.twig', [
        'evenement' => $evenement,
        'form1' => $form1->createView(),
    ]);
}






#[Route('/registernow/{id}', name: 'RegisterNow')]
public function RegisterNow(ManagerRegistry $doctrine , $id ): Response
{
    $repository= $doctrine->getRepository(Evenement::class); 
    $evenement=$repository->find($id);
    $registration = new RegisterEvenement();
    $form1 = $this->createForm(RegisterEventsType::class, $registration);
    
    return $this->render('registerEvents/registerEvents.html.twig', [
        
        'form1' => $form1->createView(),
        'evenement' => $evenement,
    ]);
}
}