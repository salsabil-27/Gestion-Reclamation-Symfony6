<?php

namespace App\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\MakerBundle\Maker\MakeSerializerNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\CategorieReclamation;


class ApiReclamationController extends AbstractController
{  private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/api/reclamation', name: 'app_api_reclamation')]
    public function index(): Response
    {
        return $this->render('api_reclamation/index.html.twig', [
            'controller_name' => 'ApiReclamationController',
        ]);
    }
    

  #[Route('/ajouterRec', name: 'ajouterRec')]
public function ajouterReclamation(Request $request,EntityManagerInterface $em , ManagerRegistry $doctrine)
{  
    $reclamation = new Reclamation();
    $emailReclamation = $request->query->get("emailReclamation");
    $ObjetReclamation = $request->query->get("ObjetReclamation");
    $ContenueReclamation = $request->query->get("ContenueReclamation");
    $type= $request->query->get("type");
    $this->entityManager->persist($reclamation);
    $this->entityManager->flush();


    $reclamation->setemailReclamation($emailReclamation);
    $reclamation->setObjetReclamation($ObjetReclamation);
    $reclamation->setContenueReclamation($ContenueReclamation);
    $reclamation->settype($type);

     $em->persist($reclamation);
     $em->flush();
     $formatted = [
        'emailReclamation' => $reclamation->getemailReclamation(),
        'ObjetReclamation' =>  $reclamation->getObjetReclamation(),
        'ContenueReclamation' => $reclamation->getContenueReclamation(),
        'type' => $reclamation->gettype(),
    ];

    return new JsonResponse($formatted);
}

#[Route('/upRec/{id}', name: 'modifierRec')]
public function modiferReclamation(Request $request, $id, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse


{
    $repository = $doctrine->getRepository(Reclamation::class); 
    $reclamation = $repository->find($id);
    
    $reclamation->setemailReclamation($request->get("emailReclamation"));
    $reclamation->setObjetReclamation($request->get("ObjetReclamation"));
    $reclamation->setContenueReclamation($request->get("ContenueReclamationt"));
    $reclamation->settype($request->get("type"));
    
$em->persist($reclamation);
     $em->flush();

     $formatted = [
        'emailReclamation' => $reclamation->getemailReclamation(),
        'ObjetReclamation' =>  $reclamation->getObjetReclamation(),
        'ContenueReclamation' => $reclamation->getContenueReclamation(),
        'type' => $reclamation->gettype(),
    ];

    return new JsonResponse($formatted);


}


  /**
     * @Route("/api/supp/{id}", name="delete_rec_api", methods={"DELETE"})
     */
public function supprimerApi($id, EntityManagerInterface $em): JsonResponse
{
    $reclamation = $em->getRepository(Reclamation::class)->find($id);

    if (!$reclamation) {
        return new JsonResponse(['error' => 'La\'reclamation n\'existe pas.'], 404);
    }

    $em->remove($reclamation);
    $em->flush();

    return new JsonResponse(['success' => 'La\'reclamation a été supprimé.'], 200);
}



#[Route('/displayReclamation', name: 'display_Reclamation')]
public function afficher(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
{
    $repository = $doctrine->getRepository(Reclamation::class);
    $evenements = $repository->findAll();

    // Serialize the data to JSON using the Serializer component
    $data = $serializer->serialize($evenements, 'json', [
        'attributes' => [
            'id',
            'emailReclamation',
            'ObjetReclamation',
            'ContenueReclamation',
            
            
        ],
        'format' => 'json',
    ]);

    // Return the serialized data as a JSON response
    return new JsonResponse($data, 200, [], true);
}


//categorie 
#[Route('/ajoutercat', name: 'ajoutercat')]
public function ajouterCategorie(Request $request,EntityManagerInterface $em , ManagerRegistry $doctrine)
{  
    $catreclamation = new CategorieReclamation();
    $libelle = $request->query->get("libelle");
   
    $this->entityManager->persist($catreclamation);
    $this->entityManager->flush();


    $catreclamation->setLibelle($libelle);
   

     $em->persist($catreclamation);
     $em->flush();
     $formatted = [
        'libelle' => $catreclamation->getlibelle(),
       
        
    ];

    return new JsonResponse($formatted);
}

#[Route('/upCat/{id}', name: 'modifierRec')]
public function modiferCatReclamation(Request $request, $id, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse


{
    $repository = $doctrine->getRepository(CategorieReclamation::class); 
    $catreclamation = $repository->find($id);
    
    $catreclamation->setlibelle($request->get("libelle"));
    
    
$em->persist($catreclamation);
     $em->flush();

     $formatted = [
        'libelle' => $catreclamation->getlibelle(),
       
    ];

    return new JsonResponse($formatted);


}

#[Route('/displayCategorie', name: 'display_categorie')]
public function affichercategorie(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
{
    $repository = $doctrine->getRepository(CategorieReclamation::class);
    $evenements = $repository->findAll();

    // Serialize the data to JSON using the Serializer component
    $data = $serializer->serialize($evenements, 'json', [
        'attributes' => [
            'id',
            'libelle',
            
            
            
        ],
        'format' => 'json',
    ]);

    // Return the serialized data as a JSON response
    return new JsonResponse($data, 200, [], true);
}

  /**
     * @Route("/api/suppcat/{id}", name="delete_rec_api", methods={"DELETE"})
     */
    public function supprimerApiCategorie($id, EntityManagerInterface $em): JsonResponse
    {
        $catreclamation = $em->getRepository(CategorieReclamation::class)->find($id);
    
        if (!$catreclamation) {
            return new JsonResponse(['error' => 'La\'categorie n\'existe pas.'], 404);
        }
    
        $em->remove($catreclamation);
        $em->flush();
    
        return new JsonResponse(['success' => 'La\'categorie a été supprimé.'], 200);
    }
    

    





}