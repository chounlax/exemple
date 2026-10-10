<?php

namespace App\Portfolio;

final readonly class Gallery
{
    /**
     * @param list<Photo> $photos
     */
    public function __construct(
        public string $slug,
        public string $title,
        public string $summary,
        public string $description,
        public array $photos,
    ) {
    }

    public function cover(): Photo
    {
        return $this->photos[0];
    }

    public function count(): int
    {
        return \count($this->photos);
    }
}
