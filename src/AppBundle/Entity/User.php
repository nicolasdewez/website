<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User.
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="user_username", columns={"username"})})
 * @ORM\Entity
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=100)
     *
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(length=100)
     *
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(length=30, unique=true)
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Regex(pattern="/^[0-9_a-z]+$/i", message="Cette valeur n'est pas valide")
     * @Assert\Length(
     *     min=4,
     *     max=30,
     *     minMessage="Cette chaine est trop courte. Elle doit avoir au minimum 4 caractères.",
     *     maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 30 caractères."
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\NotEqualTo(value="password", message="Le mot de passe doit être différent de 'password'")
     */
    private $password;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
