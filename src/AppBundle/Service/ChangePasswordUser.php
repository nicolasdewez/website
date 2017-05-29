<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Exception\ActualPasswordNotValid;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ChangePasswordUser.
 */
class ChangePasswordUser
{
    /** @var EntityManager */
    protected $manager;

    /** @var UserPasswordEncoderInterface */
    protected $passwordEncoder;

    /**
     * @param EntityManager                $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManager $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User   $user
     * @param string $actual
     * @param string $new
     *
     * @throws ActualPasswordNotValid
     */
    public function process(User $user, string $actual, string $new)
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $actual)) {
            throw new ActualPasswordNotValid();
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $new));

        $this->manager->flush();
    }
}
