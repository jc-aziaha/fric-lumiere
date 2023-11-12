<?php

namespace App\Controller\Visitor\Welcome;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'visitor.welcome.index', methods:['GET', 'POST'])]
    // #[Route('/#contact_section', name: 'visitor.welcome.index.contact_section', methods:['POST'])]
    public function index(Request $request, EntityManagerInterface $em, SendEmailService $sendEmailService): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $em->persist($contact);
            $em->flush();

            // Envoi de l'email
            $sendEmailService->send([
                "sender_email" => "friclumiere@gmail.com",
                "sender_name"  => "Nestor Gavi",
                "recipient_email" => "friclumiere@gmail.com",
                "subject" => "Un message reçu votre site 'fric-lumiere.com'",
                "html_template" => "emails/contact.html.twig",
                "context"   => [
                    "contact_first_name"    => $contact->getFirstName(),
                    "contact_last_name"     => $contact->getLastName(),
                    "contact_email"         => $contact->getEmail(),
                    "contact_phone"         => $contact->getPhone(),
                    "contact_message"       => $contact->getMessage(),
                ]
            ]);

            $this->addFlash('success', "Votre message a été bien envoyé. Nous vous recontacterons dans les plus brefs délais.");

            return $this->redirectToRoute('visitor.welcome.index');
        }

        return $this->render('pages/visitor/welcome/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
