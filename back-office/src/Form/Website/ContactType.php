<?php

namespace App\Form\Website;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Nom Prénom',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => false,
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('is_company', CheckboxType::class, [
                'label' => 'Je suis une entreprise',
                'required' => false,
                'mapped' => false,
            ])
            ->add('about', TextType::class, [
                'label' => 'Objet',
                'required' => false,
            ])
            ->add('companyName', TextType::class, [
                'required' => false,
                'label' => 'Raison sociale',
                'row_attr' => [
                    'style' => 'display:none;',
                ],
            ])
            ->add('uploadedFile', FileType::class, [
                'label' => false,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the image file
                // every time you edit the Product details
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Ce fichier est trop volumineux .',
                        'mimeTypes' => $this->acceptedFiles(),
                        'mimeTypesMessage' => 'Ce type de fichier n\'est pas valide. Seuls les fichiers .png, .jpg, .jpeg, .doc, .docx, .pdf, .ppt, .pptx, .odp et .odt sont acceptés.',
                    ]),
                ],
                'attr' => [
                    'accept' => join(', ', $this->acceptedFiles()),
                ],
                'row_attr' => [
                    'style' => 'display:none;',
                ],
            ])
            ->add('fileSize', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary mb-3',
                    'row' => 4,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'csrf_protection' => false,
        ]);
    }
    
    /**
     * acceptedFiles
     * @return array
     */
    private function acceptedFiles(): array 
    {
        return [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-powerpoint',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.oasis.opendocument.text',
            'image/png',
            'image/jpeg',
            'image/jpg',
        ];
    }
}
