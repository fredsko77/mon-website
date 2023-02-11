<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Utilisateur;
use Faker\Generator;
use App\Entity\Stack;
use App\Entity\Project;
use App\Entity\FileType;
use App\Utils\FakerTrait;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    use FakerTrait;
    private Slugify $slugger;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder
    ) {
        $this->slugger = new Slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $usersList = [];
        $stacks = [];

        foreach ($this->getStacks() as $key => $name) {
            $stack = new Stack;
            $stack->setName($name);
            $stacks[$key] = $stack;

            $manager->persist($stack);
        }

        # Role Super Admin
        $superAdmin = $this->generateUser($faker, Utilisateur::ROLES['Super Administrateur']);
        $manager->persist($superAdmin);
        $usersList['super_admin'] = $superAdmin;

        # Role Admin
        for ($i = 0; $i < random_int(1, 4); $i++) {
            $admin = $this->generateUser($faker, Utilisateur::ROLES['Administrateur']);
            $manager->persist($admin);
            $usersList['admin'][$i] = $admin;
        }

        # Role Manager
        for ($i = 0; $i < random_int(4, 10); $i++) {
            $uManager = $this->generateUser($faker, Utilisateur::ROLES['Gestionnaire']);
            $manager->persist($uManager);
            $usersList['manager'][$i] = $uManager;
        }

        # Role Editeur
        for ($i = 0; $i < random_int(10, 20); $i++) {
            $editor = $this->generateUser($faker, Utilisateur::ROLES['Editeur']);
            $manager->persist($editor);
            $usersList['editor'][$i] = $uManager;
        }

        # Role Utilisateur
        for ($i = 0; $i < random_int(75, 150); $i++) {
            $user = $this->generateUser($faker, Utilisateur::ROLES['Utilisateur']);
            $manager->persist($user);
            $usersList['user'][$i] = $user;
        }

        # Projects
        for ($i = 0; $i < random_int(150, 400); $i++) {
            $project = new Project;
            $users = array_merge($usersList['editor'], $usersList['manager'], $usersList['user']);
            $project->setName($faker->words(random_int(1, 3), true))
                ->setDescription($this->surroundTag($faker->paragraphs(random_int(1, 3))))
                ->setLink($faker->url())
                ->setSlug($this->slugger->slugify($project->getName()))
                ->setVisibility(array_rand(Project::VISIBILITIES))
                ->setState(array_rand(Project::STATES))
                ->setUser($this->randomElement($users))
                ->setCreatedAt($this->setDateTimeAfter($project->getUser()->getRegisteredAt()));

            $manager->persist($project);
        }

        # FileTypes
        foreach ($this->getFileTypes() as $k => $ft) {
            $type = new FileType;

            $type->setIcon($ft['icon'])
                ->setName($ft['name'])
                ->setFileExtensions($ft['extensions']);

            $manager->persist($type);
        }

        $manager->flush();
    }

    /**
     * @param Generator $generator
     * @param array $roles
     * 
     * @return Utilisateur
     */
    private function generateUser(Generator $generator, array $roles = ['ROLE_ADMIN']): Utilisateur
    {
        $rand = random_int(1, 9);
        $user = new Utilisateur;
        $user->setFirstname($generator->firstName())
            ->setLastname($generator->lastName())
            ->setUsername($generator->userName())
            ->setEmail($generator->email())
            ->setPassword($this->passwordEncoder->hashPassword($user, 'password'))
            ->setRoles($roles)
            ->setRegisteredAt($this->setDateTimeBetween('-5 years', '-6 months'))
            ->setUpdatedAt($rand / 3 === 0 ? null : $this->setDateTimeAfter($user->getRegisteredAt()));

        return $user;
    }

    /**
     * Return an array of file types
     *
     * @return array
     */
    private function getFileTypes(): array
    {
        return [
            // Tableur
            [
                'name' => 'Fichier tableur',
                'icon' => 'mdi-microsoft-excel',
                'extensions' => ['ods', 'xls', 'xlsx',],
            ],
            // Texte
            [
                'name' => 'Fichier texte',
                'icon' => 'mdi-text',
                'extensions' => ['odt', 'doc', 'docx', 'txt',]
            ],
            // Code
            [
                'name' => 'Fichier de code',
                'icon' => 'mdi-code-json',
                'extensions' => [
                    'log', 'xml', 'twig', 'html', 'php', 'js', 'md', 'yaml', 'yml', 'json', 'env', 'css', 'scss', 'sass', 'sql', 'sh', 'py', 'htaccess', 'conf',
                ],
            ],
            // Image
            [
                'name' => 'Images',
                'icon' => 'mdi-file-image-outline',
                'extensions' => [
                    'jpg', 'jpeg', 'png', 'svg', 'gif',
                ],
            ],
            // Pdf
            [
                'name' => 'Fichier pdf',
                'icon' => 'mdi-file-pdf-box',
                'extensions' => [
                    'pdf',
                ],
            ],
            // Archive
            [
                'name' => 'Fichier archive',
                'icon' => 'mdi-zip-box-outline',
                'extensions' => [
                    'zip',
                ],
            ],
            // Présentations
            [
                'name' => 'Support slides',
                'icon' => 'mdi-file-powerpoint-outline',
                'extensions' => [
                    'odp', 'ppt', 'pptx',
                ],
            ],
            // Vidéos
            [
                'name' => 'Vidéos',
                'icon' => 'mdi-file-video-outline',
                'extensions' => [
                    'avi', 'mp4', 'mkv', 'flv',
                ],
            ],
            // Audios
            [
                'name' => 'Audios',
                'icon' => 'mdi-music',
                'extensions' => [
                    'mp3', 'aac', 'flac', 'ogg',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function getStacks(): array
    {
        # Stacks
        return [
            'PHP',
            'JavaScript',
            'CSS',
            'HTML',
            'Twig',
            'Symfony',
            'React js',
            'Bash',
            'Python',
            'WordPress',
            'Drupal',
            'Vue js',
            'Slim Framework',
            'Flask',
            'Laravel',
            'MySql',
        ];
    }
}
