<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class Signup
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 16)]
    #[Assert\Regex('/^[a-z0-9_]+$/', message: 'Only lowercase letters, digits and underscores.')]
    public ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 12, minMessage: 'Use at least 12 characters.')]
    public ?string $password = null;

    #[Assert\IsTrue(message: 'You must accept the terms.')]
    public bool $acceptTerms = false;
}
