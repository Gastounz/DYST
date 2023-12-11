<?php

namespace App\Entity;

use symfony\component\Validator\Constraints as Assert;

class Contact
{
    #[Assert\NotBlank(message: "Veuillez saisir votre prénom")]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $firstname = null;

    #[Assert\NotBlank(message: "Veuillez saisir votre nom")]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $lastname = null;

    #[Assert\NotBlank(message: "Veuillez saisir votre e-mail")]
    #[Assert\Email()]
    private ?string $email = null;

    #[Assert\NotBlank(message: "Veuillez saisir votre message")]
    #[Assert\Length(min: 10)]
    private ?string $message = null;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): Contact
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): Contact
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}
