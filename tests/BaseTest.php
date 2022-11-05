<?php

namespace UtxoOne\LndPhp\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    protected string $host;
    protected string $port;
    protected string $macaroonPath;
    protected string $tlsCertificatePath;

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();

        if (!isset($_ENV['LND_HOST'], $_ENV['LND_PORT'], $_ENV['LND_MACAROON_PATH'], $_ENV['LND_TLS_CERT_PATH'])) {
            throw new \Exception('Missing .env variables');
        }
    }

    protected function setUp(): void
    {
        $this->host = $_ENV['LND_HOST'];
        $this->port = $_ENV['LND_PORT'];
        $this->macaroonPath = $_ENV['LND_MACAROON_PATH'];
        $this->tlsCertificatePath = $_ENV['LND_TLS_CERT_PATH'];
    }

    public function testItSetsAllTheEnvironmentVariables(): void
    {
        $this->assertIsString($this->host);
        $this->assertIsString($this->port);
        $this->assertIsString($this->macaroonPath);
        $this->assertIsString($this->tlsCertificatePath);

        $this->assertNotEmpty($this->host);
        $this->assertNotEmpty($this->port);
        $this->assertNotEmpty($this->macaroonPath);
        $this->assertNotEmpty($this->tlsCertificatePath);
    }

    public function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
}