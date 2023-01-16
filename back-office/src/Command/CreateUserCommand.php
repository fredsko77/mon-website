<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Service\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{

    public function __construct(
        private UtilisateurService $service
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::OPTIONAL, 'Admin username')
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Admin password')
            ->addOption(
                'role',
                null,
                InputOption::VALUE_OPTIONAL,
                'Which colors do you like?',
                'user'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);
        $roles = $input->getOption('role') === 'user' ? ['ROLE_USER'] : ['ROLE_ADMIN'];

        $username = $input->getArgument('username');

        if (!$username) {
            $question = new Question('Nom d\'utilisateur : ');
            $username = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('Adresse e-mail : ');
            $email = $helper->ask($input, $output, $question);
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Mot de passe : ');
            $password = $helper->ask($input, $output, $question);
        }

        $this->service->store(null, compact('roles', 'username', 'email', 'password'));

        $io->success('A new admin user has been created ! 🚀');

        return Command::SUCCESS;
    }
}
