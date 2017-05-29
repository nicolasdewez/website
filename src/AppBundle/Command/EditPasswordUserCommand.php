<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditPasswordUserCommand.
 */
class EditPasswordUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:user:password')
            ->setDescription('Edit password')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'New password (not encoded)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(User::class)->findOneBy(['username' => $input->getArgument('username')]);
        if (null === $user) {
            $output->writeln('<error>User does not exist.</error>');

            return;
        }

        $user->setPassword(
            $this->getContainer()->get('security.password_encoder')->encodePassword($user, $input->getArgument('password'))
        );

        $errors = $this->getContainer()->get('validator')->validate($user);
        if (count($errors)) {
            $output->writeln('<error>User is not valid.</error>');
            $output->writeln((string) $errors);

            return;
        }

        $em->flush();

        $output->writeln(sprintf('<info>Password of user %s edited.</info>', $user->getUsername()));
    }
}
