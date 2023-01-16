<?php
namespace App\Service;

use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Entity\Utilisateur;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

final class UtilisateurService 
{

    use ServiceTrait;

    private mixed $slugger;
    private mixed $normalizer;
    private mixed $session;

    public function __construct(
        private EntityManagerInterface $manager,
        private UtilisateurRepository $repository,
        private UserPasswordHasherInterface $encoder, 
        private SerializerInterface $serializer
    )
    {
        $this->normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        $this->slugger = new Slugify;
        $this->session = new Session;
    }

    public function store(?Utilisateur $user = null, ?array $data = null):void {
        $user = $user ?? new Utilisateur;
        is_array($data) ? $this->normalizer->denormalize(
            $data, 
            Utilisateur::class, 
            'array', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        ) : null ;
        $token = $this->generateToken();

        $user->setPassword($this->encoder->hashPassword($user, $user->getPassword()))
            ->setRegisteredAt(new DateTimeImmutable)
            ->setRoles($user->getRoles() ?? Utilisateur::ROLES['Utilisateur'])
            ->setRegisteredAt(new DateTimeImmutable)
            ->setToken($token)
        ;

        $this->manager->persist($user);
        $this->manager->flush();
    }
    
    /**
     * confirmUser
     *
     * @param  mixed $request
     * @return bool
     */
    public function confirmUser(Request $request):bool 
    {
        $token = $request->query->get('token', '');
        $user = $this->repository->findOneBy(['token' => $token]);

        if ($user instanceof Utilisateur) {
            $user->setConfirm(true)
                ->setToken(null)
                ->setUpdatedAt(new DateTimeImmutable)
            ;

            $this->manager->persist($user);
            $this->manager->flush();

            $this->session->getFlashBag()->add('info', 'Votre e-mail a été validé avec succès');

            return true;
        }

        $this->session->getFlashBag()->add('danger', 'Oops, nous n\'avons pas pu reconnaître votre adresse e-mail !');
        return false;
    }

}