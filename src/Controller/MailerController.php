<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{   #[Route('/email', name: 'email')]
    public function sendEmail(MailerInterface $mailer ):Response
    {   
        $email = (new Email())
            ->from('salsabil.noreply@example.com')
            ->to('salsabil.zaabar@esprit.tn')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject('New Reclamation!')
            ->text('Sending emails is fun again!')
            ->html('See Twig integration for better HTML integration!');
            $mailer->send($email);
            return $this->render('reclamation/registration.html.twig');
      
        
    }
}
