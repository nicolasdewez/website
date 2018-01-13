<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    public function getActual(): string
    {
        if (null === $this->actual) {
            $this->actual = '';
        }

        return $this->actual;
    }

    public function setActual(string $actual): void
    {
        $this->actual = $actual;
    }

    public function getNew(): ?string
    {
        return $this->new;
    }

    public function setNew(string $new): void
    {
        $this->new = $new;
    }

    /**
     * @Assert\Callback
     */
    public function newShouldBeDifferentToActual(ExecutionContextInterface $context, $payload): void
    {
        if ($this->new === $this->actual) {
            $context->buildViolation('Le nouveau mot de passe doit être différent de l\'actuel.')
                ->atPath('new')
                ->addViolation();
        }
    }
}
