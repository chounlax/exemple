<?php

namespace App\Contact;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Données du formulaire de contact. Propriétés modifiables : le composant Form
 * les remplit à la soumission.
 */
final class ContactRequest
{
    public const SERVICES = [
        'Mariage' => 'mariage',
        'Portrait' => 'portrait',
        'Entreprise' => 'entreprise',
        'Tirage d\'art' => 'tirage',
        'Autre demande' => 'autre',
    ];

    #[Assert\NotBlank(message: 'Indiquez votre nom.')]
    #[Assert\Length(max: 100)]
    public ?string $name = null;

    #[Assert\NotBlank(message: 'Indiquez votre adresse email.')]
    #[Assert\Email(message: 'Cette adresse email n\'est pas valide.')]
    #[Assert\Length(max: 180)]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'Choisissez le type de prestation.')]
    #[Assert\Choice(choices: self::SERVICES)]
    public ?string $service = null;

    #[Assert\GreaterThanOrEqual('today', message: 'La date doit être dans le futur.')]
    public ?\DateTimeImmutable $date = null;

    #[Assert\NotBlank(message: 'Écrivez votre message.')]
    #[Assert\Length(min: 20, max: 3000, minMessage: 'Votre message doit faire au moins {{ limit }} caractères.')]
    public ?string $message = null;

    public function serviceLabel(): string
    {
        return (string) array_search($this->service, self::SERVICES, true);
    }
}
