<?php

namespace App\StaticSite;

use App\Portfolio\GalleryCatalog;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Génère le site en fichiers HTML statiques (pour GitHub Pages) : chaque page est
 * rendue par le kernel Symfony, comme pour une vraie requête, sous l'URL publique.
 */
final class StaticSiteExporter
{
    private const NOT_FOUND_PATH = '/__page-introuvable__';

    public function __construct(
        private readonly HttpKernelInterface $httpKernel,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly GalleryCatalog $catalog,
        private readonly Filesystem $filesystem,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    /**
     * @param string $baseUrl URL publique du site, ex. https://pseudo.github.io/depot
     *
     * @return list<string> fichiers écrits, relatifs à $outputDir
     */
    public function exportPages(string $outputDir, string $baseUrl): array
    {
        $this->filesystem->remove($outputDir);
        $this->filesystem->mkdir($outputDir);

        $written = [];
        foreach ($this->paths() as $path) {
            $file = $this->fileFor($path);
            $this->filesystem->dumpFile($outputDir.'/'.$file, $this->render($baseUrl, $path, 200));
            $written[] = $file;
        }

        // GitHub Pages affiche 404.html pour toute adresse inconnue.
        $this->filesystem->dumpFile($outputDir.'/404.html', $this->render($baseUrl, self::NOT_FOUND_PATH, 404));
        $written[] = '404.html';

        // Désactive Jekyll, qui ignorerait certains fichiers.
        $this->filesystem->touch($outputDir.'/.nojekyll');

        return $written;
    }

    /**
     * Copie les assets compilés par asset-map:compile (CSS, JS versionnés).
     */
    public function copyAssets(string $outputDir): void
    {
        $assets = $this->projectDir.'/public/assets';
        if (!is_file($assets.'/manifest.json')) {
            throw new \RuntimeException('Assets non compilés : lancez d\'abord "php bin/console asset-map:compile".');
        }

        $this->filesystem->mirror($assets, $outputDir.'/assets');
    }

    /**
     * @return list<string>
     */
    private function paths(): array
    {
        $paths = [
            $this->urlGenerator->generate('app_home'),
            $this->urlGenerator->generate('app_gallery_index'),
        ];
        foreach ($this->catalog->all() as $gallery) {
            $paths[] = $this->urlGenerator->generate('app_gallery_show', ['slug' => $gallery->slug]);
        }

        return [
            ...$paths,
            $this->urlGenerator->generate('app_about'),
            $this->urlGenerator->generate('app_contact'),
            $this->urlGenerator->generate('app_legal'),
            $this->urlGenerator->generate('app_sitemap'),
            $this->urlGenerator->generate('app_robots'),
        ];
    }

    /**
     * /galeries/mariages devient galeries/mariages.html : GitHub Pages sert ce
     * fichier à l'adresse sans extension.
     */
    private function fileFor(string $path): string
    {
        $file = trim($path, '/');
        if ('' === $file) {
            return 'index.html';
        }

        return str_contains(basename($file), '.') ? $file : $file.'.html';
    }

    private function render(string $baseUrl, string $path, int $expectedStatus): string
    {
        $url = parse_url($baseUrl);
        if (!isset($url['scheme'], $url['host'])) {
            throw new \InvalidArgumentException(\sprintf('URL de base invalide : "%s".', $baseUrl));
        }
        $basePath = rtrim($url['path'] ?? '', '/');
        $host = $url['host'].(isset($url['port']) ? ':'.$url['port'] : '');

        // SCRIPT_NAME sous le chemin de base : Symfony préfixe alors tous les liens
        // et assets par ce chemin (ex. /depot/galeries).
        $request = Request::create($url['scheme'].'://'.$host.$basePath.$path, server: [
            'SCRIPT_NAME' => $basePath.'/index.php',
            'SCRIPT_FILENAME' => $this->projectDir.'/public/index.php',
        ]);

        $response = $this->httpKernel->handle($request);
        if ($this->httpKernel instanceof TerminableInterface) {
            $this->httpKernel->terminate($request, $response);
        }

        if ($response->getStatusCode() !== $expectedStatus) {
            throw new \RuntimeException(\sprintf('"%s" a répondu %d au lieu de %d.', $path, $response->getStatusCode(), $expectedStatus));
        }

        $content = (string) $response->getContent();

        // En ligne de commande, Symfony rend les erreurs en texte brut, sauf en mode web.
        if (404 === $expectedStatus && !str_contains($content, '<html')) {
            throw new \RuntimeException('La page 404 n\'est pas du HTML : lancez la commande avec APP_RUNTIME_MODE=web=1.');
        }

        return $content;
    }
}
