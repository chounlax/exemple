<?php

namespace App\Portfolio;

/**
 * Galeries du portfolio, définies dans le code tant qu'il n'y a pas de base de
 * données. Le reste de l'application ne passe que par cette classe : elle pourra
 * être remplacée par un repository Doctrine sans toucher aux contrôleurs.
 */
final class GalleryCatalog
{
    /** Dimensions en pixels, en alternant paysage, portrait et carré. */
    private const SIZES = [[3000, 2000], [2000, 3000], [2400, 3000], [3000, 2000], [2400, 2400], [2000, 3000]];

    private const GALLERIES = [
        [
            'slug' => 'mariages',
            'title' => 'Mariages',
            'summary' => 'Des reportages discrets, de la préparation à la soirée.',
            'description' => 'Je raconte votre journée telle qu\'elle s\'est vécue : les regards, les rires, les moments que l\'on ne voit pas passer. Un reportage naturel, sans poses figées.',
            'count' => 12,
            'palette' => ['#c9a39a', '#a8786c', '#d8bfa8', '#8e6a5f', '#b9928a', '#7a5a52'],
        ],
        [
            'slug' => 'portraits',
            'title' => 'Portraits',
            'summary' => 'Des portraits sincères, en studio ou en lumière naturelle.',
            'description' => 'Portraits individuels, de famille ou de couple. Une séance détendue pour obtenir des images qui vous ressemblent vraiment.',
            'count' => 9,
            'palette' => ['#9c6b4e', '#c08a63', '#6e4a36', '#d3a984', '#845a42', '#b07b57'],
        ],
        [
            'slug' => 'entreprises',
            'title' => 'Entreprises',
            'summary' => 'Portraits d\'équipe, locaux et événements professionnels.',
            'description' => 'Des images professionnelles pour votre site, vos réseaux et votre presse : portraits corporate, photos d\'équipe, savoir-faire et événements.',
            'count' => 8,
            'palette' => ['#4f6a85', '#6d88a3', '#3a5168', '#8aa1b8', '#56708a', '#2f4356'],
        ],
        [
            'slug' => 'paysages',
            'title' => 'Paysages',
            'summary' => 'Lumières de saison, côtes et montagnes.',
            'description' => 'Un travail personnel au fil des saisons. Ces images sont disponibles en tirages d\'art sur demande.',
            'count' => 10,
            'palette' => ['#5d7a5a', '#3f6b6b', '#7e9a6e', '#2f5550', '#6f8f86', '#4a6347'],
        ],
    ];

    /** @var list<Gallery>|null */
    private ?array $galleries = null;

    /**
     * @return list<Gallery>
     */
    public function all(): array
    {
        return $this->galleries ??= array_map($this->build(...), self::GALLERIES);
    }

    public function find(string $slug): ?Gallery
    {
        foreach ($this->all() as $gallery) {
            if ($gallery->slug === $slug) {
                return $gallery;
            }
        }

        return null;
    }

    /**
     * @param array{slug: string, title: string, summary: string, description: string, count: int, palette: list<string>} $data
     */
    private function build(array $data): Gallery
    {
        $photos = [];
        for ($number = 1; $number <= $data['count']; ++$number) {
            [$width, $height] = self::SIZES[($number - 1) % \count(self::SIZES)];
            $photos[] = new Photo(
                number: $number,
                color: $data['palette'][($number - 1) % \count($data['palette'])],
                width: $width,
                height: $height,
                alt: \sprintf('%s – photo %d', $data['title'], $number),
            );
        }

        return new Gallery($data['slug'], $data['title'], $data['summary'], $data['description'], $photos);
    }
}
