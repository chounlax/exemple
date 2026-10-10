<?php

namespace App\Command;

use App\StaticSite\StaticSiteExporter;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export-static',
    description: 'Génère le site en HTML statique (pour GitHub Pages).',
)]
final class ExportStaticCommand
{
    public function __construct(
        private readonly StaticSiteExporter $exporter,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('URL publique du site, ex. https://pseudo.github.io/depot')]
        string $url,
        #[Option('Dossier de sortie')]
        string $output = 'build',
        #[Option('Ne pas copier les assets compilés (tests)')]
        bool $noAssets = false,
    ): int {
        $files = $this->exporter->exportPages($output, $url);
        if (!$noAssets) {
            $this->exporter->copyAssets($output);
        }

        $io->listing($files);
        $io->success(\sprintf('%d fichiers générés dans "%s" pour %s.', \count($files), $output, $url));

        return Command::SUCCESS;
    }
}
