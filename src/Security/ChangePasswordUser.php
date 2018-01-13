<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\ActualPasswordNotValid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordUser
{
    /** @var EntityManagerInterface */
    protected $manager;

    /** @var UserPasswordEncoderInterface */
    protected $passwordEncoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws ActualPasswordNotValid
     */
    public function process(User $user, string $actual, string $new): void
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $actual)) {
            throw new ActualPasswordNotValid();
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $new));

        $this->manager->flush();
    }
}
