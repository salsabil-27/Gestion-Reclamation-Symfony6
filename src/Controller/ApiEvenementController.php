<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\FavoriteEvent;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ApiEvenementController extends AbstractController

{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/api/evenement', name: 'app_api_evenement')]
    public function index(): Response
    {
        return $this->render('api_evenement/index.html.twig', [
            'controller_name' => 'ApiEvenementController',
        ]);
    }

   

    

 
    
    /**
     * @Route("/api/list", name="list_event", methods={"GET"})
     */
    public function afficher(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
{
    $repository = $doctrine->getRepository(Evenement::class);
    $evenements = $repository->findAll();
    
    // Serialize the data to JSON using the Serializer component
    $data = $serializer->serialize($evenements, 'json', [
        'attributes' => [
            'id',
            'titreEvent',
            'DateDebutEvent',
            'dateFinEvent',
            'placeEvent',
            'DescriptionEvent',
            'imageEvenement',
            'type' => [
                
                'typeName',
            ],
        ],
        'format' => 'json',
    ]);
    
    // Return the serialized data as a JSON response
    return new JsonResponse($data, 200, [], true);
}

  


  /**
     * @Route("/api/supp/{id}", name="delete_event_api", methods={"DELETE"})
     */

public function supprimerApi($id, EntityManagerInterface $em): JsonResponse
{
    $evenement = $em->getRepository(Evenement::class)->find($id);

    if (!$evenement) {
        return new JsonResponse(['error' => 'L\'événement n\'existe pas.'], 404);
    }

    $em->remove($evenement);
    $em->flush();

    return new JsonResponse(['success' => 'L\'événement a été supprimé.'], 200);
}




 /**
     * @Route("/api/up/{id}", name="u", methods={"PUT"})
     */

public function update(Request $request, $id, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
{
    $repository = $doctrine->getRepository(Evenement::class); 
    $evenement = $repository->find($id);
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->add('modifier', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageEvenement')->getData();
        $filename = md5(uniqid()) . '.' . $imageFile->guessExtension();
        try {
            $imageFile->move(
                $this->getParameter('images_directory'),
                $filename
            );
        } catch (FileException $e) {
        }

        $evenement->setImageEvenement($filename);

        $em->persist($evenement);
        $em->flush();

        $jsonContent = $serializer->serialize($evenement, 'json', [
            'attributes' => 
                [  'id',
                'titreEvent',
                'DateDebutEvent',
                'dateFinEvent',
                'placeEvent',
                'DescriptionEvent',
                'imageEvenement',
                'typeEvenement' => [
                    'id',
                    'typeName'
                ]
                ],
            'format' => 'json',
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    return new JsonResponse(['error' => 'Invalid data'], 400);
}

#[Route('/ajouterE', name: 'ajouterE')]
public function ajouter(Request $request,EntityManagerInterface $em , ManagerRegistry $doctrine)
{  
    $evenement = new Evenement();
    $id= $request->query->get("id");
    $titreEvent = $request->query->get("titreEvent");
    $DateDebutEvent = $request->query->get("DateDebutEvent");
    $dateFinEvent= $request->query->get("dateFinEvent");
    $placeEvent = $request->query->get("placeEvent");
    $DescriptionEvent = $request->query->get("DescriptionEvent");
    $imageEvenement = $request->query->get("imageEvenement");
    $type = $request->query->get("type");
    $this->entityManager->persist( $evenement);
    $this->entityManager->flush();

   $evenement ->setTitreEvent ( $titreEvent );
   $evenement->setDateDebutEvent($DateDebutEvent);
   $evenement->setDateFinEvent( $dateFinEvent);
   $evenement->setPlaceEvent( $placeEvent );
   $evenement->setDescriptionEvent($DescriptionEvent);
   $evenement->setImageEvenement($imageEvenement);
   $evenement->setType($type);

     $em->persist( $evenement);
     $em->flush();
     $formatted = [

        'titreEvent' =>  $evenement->getTitreEvent(),
        'DateDebutEvent' =>   $evenement->getDateDebutEvent(),
        'dateFinEvent' =>  $evenement->getDateFinEvent (),
        'placeEvent' =>  $evenement->getPlaceEvent(),
        'DescriptionEvent' =>   $evenement->getDescriptionEvent(),
        'imageEvenement' =>  $evenement->getImageEvenement (),
        "type"=>$evenement->getType("type"),
        
    ];

    return new JsonResponse($formatted);
}
#[Route('/upE/{id}', name: 'modifierE')]
public function modifer(Request $request, $id, EntityManagerInterface $em, ManagerRegistry $doctrine): JsonResponse


{
    $repository = $doctrine->getRepository(Evenement::class); 
    $evenement = $repository->find($id);
    $evenement ->setTitreEvent ( $request->get("TitreEvent") );
    $evenement->setDateDebutEvent($request->get("DateDebutEvent"));
    $evenement->setDateFinEvent( $request->get("DateFinEvent"));
    $evenement->setPlaceEvent( $request->get("PlaceEvent") );
    $evenement->setDescriptionEvent($request->get("DescriptionEvent"));
    $evenement->setImageEvenement($request->get("ImageEvenement"));
    $evenement->setType($request->get("Type"));


 
    
$em->persist($evenement);
     $em->flush();

     $formatted = [

        'titreEvent' =>  $evenement->getTitreEvent(),
        'DateDebutEvent' =>   $evenement->getDateDebutEvent(),
        'dateFinEvent' =>  $evenement->getDateFinEvent (),
        'placeEvent' =>  $evenement->getPlaceEvent(),
        'DescriptionEvent' =>   $evenement->getDescriptionEvent(),
        'imageEvenement' =>  $evenement->getImageEvenement (),
        "type"=>$evenement->getType(),
    ];

    return new JsonResponse($formatted);


}


// favorisapi



    /**
    
     *  @ParamConverter("event", options={"mapping": {"id": "id"}})
     */
    #[Route('/favoris/{id}', name: 'addfavoris')]
public function addToFavorites(Request $request, EntityManagerInterface $entityManager, Evenement $event, $id): JsonResponse
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
        return $this->json(['message' => 'Event already exists in favorites.'], 400);
    }

    // Create a new favorite entity and set its properties
    $favorite = new FavoriteEvent();
    $favorite->setEvents($event);
    $favorite->setUser($this->getUser());

    // Save the favorite to the database
    $entityManager->persist($favorite);
    $entityManager->flush();

    return $this->json(['message' => 'Event added to favorites successfully.'], 200);
}


#[Route('/listeF', name: 'listeF')]
public function listFavorites(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
{
    // Retrieve the list of favorites for the current user
    $favorites = $entityManager->getRepository(FavoriteEvent::class)->findBy(['user' => $this->getUser()]);
    
    // Create an array of event IDs from the favorites
    $eventIds = array_map(function($favorite) {
        return $favorite->getEvents() ;
    }, $favorites);
    
    // Retrieve the events associated with the favorites
    $events = $entityManager->getRepository(Evenement::class)->findById($eventIds);
    
    // Serialize the events to JSON using the Serializer component
    $data = $serializer->serialize($events, 'json', [
        'attributes' => [
            'id',
            'titreEvent',
            'DateDebutEvent',
            'dateFinEvent',
            'placeEvent',
            'DescriptionEvent',
            'imageEvenement',
            'type' => [
                'typeName',
            ],
        ],
        'format' => 'json',
    ]);

    // Return the serialized data as a JSON response
    return new JsonResponse($data, 200, [], true);
}



}