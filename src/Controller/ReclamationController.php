<?php


namespace App\Controller;
use App\Service\MailerService;
use App\Entity\Reclamation;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use App\Repository\ReclamationRepository;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface ;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\DBAL\Driver\Connection;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart as ChartsPieChart;



class ReclamationController extends AbstractController
{  
     private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } 
    

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

    public function ajouter(Request $request , EntityManagerInterface $em,MailerInterface $mailer )
    {   
        $Reclamation= new Reclamation();
        $form=$this->createForm(ReclamationType::class,$Reclamation);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);
    

        if( $form->isSubmitted() && $form->isValid())
        { 
          
            $email = (new Email())
            ->from('Client.Xshape@example.com')
            ->to('salsabil.zaabar@esprit.tn')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject('New Reclamation!')
            ->text('Sending emails is fun again!')
            ->html('Une nouvelle reclamation est ajouté check List Relamation!');
            $mailer->send($email);
         $em->persist($Reclamation);
         $em->flush();

        
         return $this->redirectToRoute('AddSuccess');
        
        
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
    #[Route('/AddSuccess', name: 'AddSuccess')]
    public function ajouteSuccess()
{
    return $this->render('FrontReclamation/AddSuccess.html.twig', [
        'message' => 'L\'Votre Reclamation est envouyée avec succès !',
    ]);
}

#[Route('/stat', name: 'stat')]
public function typeReclamationPlusReclamee(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les réclamations dans la base de données
        $reclamations =$entityManager->getRepository(Reclamation::class)->findAll();
      

        // Analyser les types de réclamation et compter leur fréquence
        foreach ($reclamations as $reclamation) {
            $type = $reclamation->getObjetReclamation();
            if (!isset($typeCounts[$type])) {
                $typeCounts[(string)$type] = 1;
            } else {
                $typeCounts[$type]++;
            }
        }

        // Trier les types de réclamation par ordre décroissant de fréquence d'apparition
        arsort($typeCounts);

        // Obtenir le type de réclamation le plus réclamé
        $mostFrequentType = array_key_first($typeCounts);

        // Transmettre les données à la vue
        return $this->render('reclamation/stats.html.twig', [
            'mostFrequentType' => $mostFrequentType,
            'typeCounts' => $typeCounts
        ]);
    }
}



