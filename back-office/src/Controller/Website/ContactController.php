<?php 
namespace App\Controller\Website;

use App\Entity\Contact;
use App\Form\Website\ContactType;
use App\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController {
    
    public function __construct(
        private ContactService $service
    ){}

    #[Route('me-contacter', name: 'website_contact_index', methods: ['GET', 'POST'])]
    public function contact(Request $request):Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($contact, $form);

            return $this->redirectToRoute('website_contact_index');
        }
        
        return $this->renderForm('website/contact.html.twig', compact('form', 'contact'));
    }

}