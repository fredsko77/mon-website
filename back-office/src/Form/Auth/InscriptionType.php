<?php

namespace App\Form\Auth;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'col-6 mt-0'
                ],
                'attr' => [
                    'autofocus' => true,
                    'autocomplete' => 'off'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => 'Nom de famille',
                'row_attr' => [
                    'class' => 'col-6 mt-0'
                ],
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passes doivent être identiques !',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer votre mot de passe'],
                'constraints' => [
                    new Regex(
                        '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#',
                        'Le mot de passe doit contenir au moins une masjuscule, une minuscule et un chiffre avec au moins 8 caractères !',
                        null,
                        true
                    ),
                    new NotBlank([], 'Ce champ est obligatoire !')
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'M\'inscrire',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}