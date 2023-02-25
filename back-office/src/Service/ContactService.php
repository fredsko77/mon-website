<?php 
namespace App\Service;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Entity\Document;
use App\Entity\FileType;
use App\Repository\ContactRepository;
use App\Repository\FileTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ContactService 
{
    private mixed $session;

    public function __construct(
        private EntityManagerInterface $manager,
        private ContactRepository $repository,
        private ParameterBagInterface $parameterBag,
        private Filesystem $filesystem,
        private FileTypeRepository $fileTypeRepository
    ){
        $this->session = new Session;
    }
    
    /**
     * store
     *
     * @param  mixed $contact
     * @param  mixed $form
     * @return void
     */
    public function store(Contact $contact, FormInterface $form): void
    {
        $now = new DateTimeImmutable;
        $contact->setCreatedAt($now)
            ->setState(Contact::STATE_NEW);
        $file = $form->get('uploadedFile')->getData();

        if($form->get('is_company')->getData()) {
            if ($file instanceof UploadedFile) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                    
                $file->move(
                    $this->parameterBag->get('contact_directory'),
                    $filename
                );
                
                $document = new Document;
                $document->setOriginalName($file->getClientOriginalName())
                    ->setPath('/uploads/contact/' . $filename)
                    ->setSize((string) $form->get('fileSize')->getData())
                    ->setUploadedAt($now)
                    ->setFileType($this->getFileType($file->guessClientExtension()))
                ;

                $contact->addDocument($document);
            }
        }
        
        try {
            $this->session->getFlashBag()->add(
                'info',
                'Votre demande a bien été pris en compte.'
            );

            $this->manager->persist($contact);
            $this->manager->flush();
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add(
                'danger',
                $e->getMessage()
            );
        }

        return;
    }
    
    /**
     * getFileType
     *
     * @param  mixed $extension
     * @return mixed
     */
    public function getFileType(string $extension = ''):mixed
    {
        $fileTypes = $this->fileTypeRepository->findAll();
        
        foreach ($fileTypes as $key => $fileType) {
            if (in_array($extension, $fileType->getFileExtensions())) {
                return $fileType;
            }
        }
        
        return null;
    }
    
}