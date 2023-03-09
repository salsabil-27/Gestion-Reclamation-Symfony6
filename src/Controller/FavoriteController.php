<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use App\Entity\FavoriteEvent;
use App\Form\FavoriteEventType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(): Response
    {
        return $this->render('favorite/index.html.twig', [
            'controller_name' => 'FavoriteController',
        ]);
    }


  

    /**
     * @Route("/favorite/add/{id}", name="favorite_add")
     *  @ParamConverter("event", options={"mapping": {"id": "id"}})
     */

public function addToFavorites(Request $request, EntityManagerInterface $entityManager, Evenement $event, $id): Response
{
    // Check if the user is authenticated
   // if (!$this->getUser()) {
     //   return $this->json(['message' => 'You need to be authenticated to add an event to favorites.'], 401);
   // }

    // Check if the event already exists in user's favorites
    $favorite = $entityManager->getRepository(FavoriteEvent::class)->findOneBy([
        'event' => $event,
        'user' => $this->getUser()
    ]);

    if ($favorite) {
        return $this->redirectToRoute('details', ['id' => $id]);
    }

    // Create a new favorite entity and set its properties
    $favorite = new FavoriteEvent();
    $favorite->setEvents($event);
    $favorite->setUser($this->getUser());

    // Save the favorite to the database
    $entityManager->persist($favorite);
    $entityManager->flush();
    return $this->redirectToRoute('details');
}

    
    /**
     * @Route("/favorite/remove/{id}", name="favorite_remove")
     */
    public function removeFavorite(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        // Check if the user is authenticated
        $user = $this->getUser();
     //   if (!$user) {
           // return $this->redirectToRoute('app_login');
       // }
        
        // Get the favorite from the database
        $favorite = $entityManager->getRepository(FavoriteEvent::class)->findOneBy([
            'event' => $id,
            'user' => $user
        ]);
        if (!$favorite) {
            // User has not favorited the event
            return $this->redirectToRoute('DisplayEvenement', ['id' => $id]);
        }
        
        // Remove the favorite from the database
        $entityManager->remove($favorite);


        $entityManager->flush();
        
        return $this->redirectToRoute('DisplayEvenement', ['id' => $id]);
    }

    #[Route('/list/{id}', name: 'details')]
    public function afficherEvent(Evenement $Evenement): Response
    {
       
        return $this->render('Front/details.html.twig', [
            'evenement' => $Evenement,
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

#[Route('/listeFavorite ', name: 'listeFavorite ')]
  public function listFavorites(EntityManagerInterface $entityManager): Response
        {
            // Retrieve the list of favorites for the current user
            $favorites = $entityManager->getRepository(FavoriteEvent::class)->findBy(['user' => $this->getUser()]);
        
            // Create an array of event IDs from the favorites
            $eventIds = array_map(function($favorite) {
                return $favorite->getEvents() ;
            }, $favorites);
        
            // Retrieve the events associated with the favorites
            $events = $entityManager->getRepository(Evenement::class)->findById($eventIds);
        
            return $this->render('Front/favorite.html.twig', [
                'favorites' => $events,
            ]);
        } 


}
