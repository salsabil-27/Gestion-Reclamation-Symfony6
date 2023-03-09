<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\TypeEvenement;
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

class ApiTypeEvenementController extends AbstractController
{
    #[Route('/api/type/evenement', name: 'app_api_type_evenement')]
    public function index(): Response
    {
        return $this->render('api_type_evenement/index.html.twig', [
            'controller_name' => 'ApiTypeEvenementController',
        ]);
    }
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
  

    
    /**
     * @Route("/api/listT", name="list_Type", methods={"GET"})
     */
    public function afficher(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
    {
        $repository = $doctrine->getRepository(TypeEvenement::class);
        $evenements = $repository->findAll();
    
        // Serialize the data to JSON using the Serializer component
        $data = $serializer->serialize($evenements, 'json', [
            'attributes' => [
                'id',
                'typeName',
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
    $evenement = $em->getRepository(TypeEvenement::class)->find($id);

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
                'placeEvent ',
                'DescriptionEvent ',
                'imageEvenement',
                ],
            'format' => 'json',
        ]);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    return new JsonResponse(['error' => 'Invalid data'], 400);
}

#[Route('/ajouterT', name: 'ajouterT')]
public function ajouterReclamation(Request $request,EntityManagerInterface $em )
{  
    $evenement = new TypeEvenement();
    $id= $request->query->get("id");
    $TypeName= $request->query->get("typeName");
    
   // $type = $request->query->get("type");
    $this->entityManager->persist( $evenement);
    $this->entityManager->flush();

   $evenement ->setTypeName (  $TypeName );
  

     $em->persist( $evenement);
     $em->flush();
     $formatted = [
        'TypeName' =>  $evenement->getTypeName(),
      
        
    ];

    return new JsonResponse($formatted);
}

}
