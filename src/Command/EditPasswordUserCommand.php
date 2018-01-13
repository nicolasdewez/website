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

class EditPasswordUserCommand extends Command
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
            ->setName('app:user:password')
            ->setDescription('Edit password')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'New password (not encoded)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var User $user */
        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $input->getArgument('username')]);
        if (null === $user) {
            $output->writeln('<error>User does not exist.</error>');

            return;
        }

        $user->setPassword(
            $this->encoder->encodePassword($user, $input->getArgument('password'))
        );

        $errors = $this->validator->validate($user);
        if (count($errors)) {
            $output->writeln('<error>User is not valid.</error>');
            $output->writeln((string) $errors);

            return;
        }

        $this->manager->flush();

        $output->writeln(sprintf('<info>Password of user %s edited.</info>', $user->getUsername()));
    }
}
