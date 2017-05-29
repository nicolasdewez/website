<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 */
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

    /**
     * @param string|null $object
     *
     * @return Contact
     */
    public function setObject(string $object): Contact
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        if (null === $this->object) {
            $this->object = '';
        }

        return $this->object;
    }

    /**
     * @param string|null $firstName
     *
     * @return Contact
     */
    public function setFirstName(string $firstName): Contact
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        if (null === $this->firstName) {
            $this->firstName = '';
        }

        return $this->firstName;
    }

    /**
     * @param string|null $lastName
     *
     * @return Contact
     */
    public function setLastName(string $lastName): Contact
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        if (null === $this->lastName) {
            $this->lastName = '';
        }

        return $this->lastName;
    }

    /**
     * @param string|null $email
     *
     * @return Contact
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        if (null === $this->email) {
            $this->email = '';
        }

        return $this->email;
    }

    /**
     * @param string|null $message
     *
     * @return Contact
     */
    public function setMessage(string $message): Contact
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (null === $this->message) {
            $this->message = '';
        }

        return $this->message;
    }
}
