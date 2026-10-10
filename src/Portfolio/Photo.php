<?php

namespace App\Portfolio;

/**
 * Photo du portfolio. En attendant les vraies images, une photo est affichée
 * comme un cadre de couleur numéroté aux proportions de l'image.
 */
final readonly class Photo
{
    public function __construct(
        public int $number,
        public string $color,
        public int $width,
        public int $height,
        public string $alt,
    ) {
    }

    public function label(): string
    {
        return 'Photo '.$this->number;
    }
}
