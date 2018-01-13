<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="L'objet est obligatoire")
     * @Assert\Length(
     *     min=5,
     *     max=300,
     *     minMessage="L'objet doit comporter au moins 5 caractères",
     *     maxMessage="L'objet doit comporter moins de 300 caractères"
     * )
     */
    private $object;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(
     *     min=2,
     *     max=100,
     *     minMessage="Le prénom doit comporter au moins 2 caractères",
     *     maxMessage="Le prénom doit comporter moins de 100 caractères"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(
     *     min=2,
     *     max=100,
     *     minMessage="Le nom doit comporter au moins 2 caractères",
     *     maxMessage="Le nom doit comporter moins de 100 caractères"
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="L'email doit être valide")
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le message est obligatoire")
     * @Assert\Length(
     *     min=50,
     *     max=1000,
     *     minMessage="Le message doit comporter au moins 50 caractères",
     *     maxMessage="Le message doit comporter moins de 1000 caractères"
     * )
     */
    private $message;

    public function setObject(?string $object): void
    {
        $this->object = $object;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
