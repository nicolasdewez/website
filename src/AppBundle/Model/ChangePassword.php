<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class ChangePassword.
 */
class ChangePassword
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     */
    private $actual;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\NotEqualTo(value="password", message="Le mot de passe doit être différent de 'password'")
     */
    private $new;

    /**
     * @return string
     */
    public function getActual(): string
    {
        if (null === $this->actual) {
            $this->actual = '';
        }

        return $this->actual;
    }

    /**
     * @param string $actual
     *
     * @return ChangePassword
     */
    public function setActual(string $actual): ChangePassword
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * @return string
     */
    public function getNew(): string
    {
        if (null === $this->new) {
            $this->new = '';
        }

        return $this->new;
    }

    /**
     * @param string $new
     *
     * @return ChangePassword
     */
    public function setNew(string $new): ChangePassword
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function newShouldBeDifferentToActual(ExecutionContextInterface $context, $payload)
    {
        if ($this->new === $this->actual) {
            $context->buildViolation('Le nouveau mot de passe doit être différent de l\'actuel.')
                ->atPath('new')
                ->addViolation();
        }
    }
}
