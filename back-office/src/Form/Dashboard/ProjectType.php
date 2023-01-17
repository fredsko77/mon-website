<?php

namespace App\Form\Dashboard;

use App\Entity\Stack;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('link', UrlType::class, [
                'label' => 'Url',
                'required' => false
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'État',
                'multiple' => false,
                'expanded' => false,
                'choices' => $this->reverseKeysValues(Project::states()),
            ])
            ->add('slug', TextType::class, [
                'label' => 'Permalien',
                'required' => false,
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibilité',
                'multiple' => false,
                'expanded' => false,
                'choices' => $this->reverseKeysValues(Project::visibilities()),
            ])
            ->add('stack', EntityType::class, [
                'required' => false,
                'label' => 'Stack',
                'class' => Stack::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
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
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Cette image n\'est pas valide !',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary w-100 mb-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }

    private function reverseKeysValues(?array $array = []):array
    {
        $data = [];

        foreach ($array as $key => $value) {
            $data[$value] = $key;
        }

        return $data;
    }
}