<?php

namespace App\Tests\StaticSite;

use App\StaticSite\StaticSiteExporter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

final class StaticSiteExporterTest extends KernelTestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        // Comme dans la GitHub Action : rendu des erreurs en HTML.
        $_SERVER['APP_RUNTIME_MODE'] = $_ENV['APP_RUNTIME_MODE'] = 'web=1';
        $this->outputDir = sys_get_temp_dir().'/static-export-'.bin2hex(random_bytes(4));
    }

    protected function tearDown(): void
    {
        unset($_SERVER['APP_RUNTIME_MODE'], $_ENV['APP_RUNTIME_MODE']);
        (new Filesystem())->remove($this->outputDir);
        parent::tearDown();
    }

    public function testExportsEveryPageUnderTheRepositoryPath(): void
    {
        $exporter = static::getContainer()->get(StaticSiteExporter::class);
        $files = $exporter->exportPages($this->outputDir, 'https://pseudo.github.io/depot');

        self::assertContains('index.html', $files);
        self::assertContains('galeries/mariages.html', $files);
        self::assertContains('sitemap.xml', $files);
        self::assertContains('404.html', $files);
        self::assertFileExists($this->outputDir.'/.nojekyll');

        $home = (string) file_get_contents($this->outputDir.'/index.html');
        self::assertStringContainsString('href="/depot/galeries"', $home);
        self::assertStringContainsString('<link rel="canonical" href="https://pseudo.github.io/depot/">', $home);

        $sitemap = (string) file_get_contents($this->outputDir.'/sitemap.xml');
        self::assertStringContainsString('<loc>https://pseudo.github.io/depot/galeries/paysages</loc>', $sitemap);
    }
}
