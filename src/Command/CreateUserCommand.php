<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Create user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password (not encoded)')
            ->addArgument('firstName', InputArgument::OPTIONAL, 'First name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Last name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $user = new User();
        $user->setUsername($input->getArgument('username'));
        $user->setPassword($input->getArgument('password'));
        $user->setFirstName($input->getArgument('firstName') ?: '');
        $user->setLastName($input->getArgument('lastName') ?: '');

        $user->setPassword(
            $this->encoder->encodePassword($user, $user->getPassword())
        );

        $errors = $this->validator->validate($user);
        if (count($errors)) {
            $output->writeln('<error>User is not valid.</error>');
            $output->writeln((string) $errors);

            return;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $output->writeln('<info>User created.</info>');
    }
}
