<?php
namespace App\Service;

use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

final class UtilisateurService 
{

    private mixed $slugger;
    private mixed $normalizer;

    public function __construct(
        private EntityManagerInterface $manager,
        private UtilisateurRepository $repository,
        private UserPasswordHasherInterface $encoder, 
        private SerializerInterface $serializer,
    )
    {
        $this->normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        $this->slugger = new Slugify;
    }

    public function store(?Utilisateur $user = null, ?array $data = null):void {
        $user = $user ?? new Utilisateur;
        is_array($data) ? $this->normalizer->denormalize(
            $data, 
            Utilisateur::class, 
            'array', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        ) : null ;

        $user->setPassword($this->encoder->hashPassword($user, $user->getPassword()))
            ->setRegisteredAt(new \DateTimeImmutable)
            ->setRoles($user->getRoles() ?? Utilisateur::ROLES['Utilisateur'])
            ->setRegisteredAt(new DateTimeImmutable)
            ;

        $this->manager->persist($user);
        $this->manager->flush();
    }

}