<?php

namespace UtxoOne\LndPhp\Services;

use Exception;
use UtxoOne\LndPhp\Helpers\Validator;
use UtxoOne\LndPhp\Models\NodeInfo;

class Lnd
{
    /**
     * Host
     *
     * @var string
     */
    private string $host;

    /**
     * Port
     *
     * @var int
     */
    private int $port;

    /**
     * Path to Macaroon file
     *
     * @var string
     */
    private string $macaroonPath;

    /**
     * TLS Certificate Path
     *
     * @var string
     */
    private string $tlsCertificate;

    /**
     * LND Api Version
     *
     * @var string
     */
    private string $apiVersion;

    /**
     * Bin2Hex Macaroon
     *
     * @var string
     */
    private string $macaroonHex;


    /**
     * Use SSL
     *
     * @var bool
     */
    private bool $useSsl;

    /**
     * Validator Class
     *
     * @var Validator $validator
     */
    private Validator $validator;

    /**
     * Constructor
     *
     * @param string $host
     * @param string $macaroonPath
     * @param string $tlsCertificatePath
     * @param string $apiVersion
     *
     * @throws Exception
     */
    public function __construct(
        string $host,
        int $port,
        string $macaroonHex,
        string $tlsCertificate,
        bool $useSsl = true,
    ) {
        $this->validator = new Validator();
        $this->host = $this->validator->validateHost($host);
        $this->port = $port;
        $this->macaroonHex = $macaroonHex;
        $this->tlsCertificate = $tlsCertificate;
        $this->useSsl = $useSsl;
    }

    private function createTlsCertFromString(): string
    {
        // Create a temporary file in memory
        $tempFile = tmpfile();

        // Write the certificate string to the temporary file
        fwrite($tempFile, $this->tlsCertificate);

        // Get the path to the temporary file
        $metaData = stream_get_meta_data($tempFile);
        return  $metaData['uri'];
    }

    /**
     * Call the LND API and return the response
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     *
     * @return array
     *
     * @throws Exception
     */
    public function call(string $method, string $endpoint, ?array $data = null): array
    {
        $url = $this->host . '/' . $endpoint;

        $headers = [
            'Grpc-Metadata-macaroon: ' . $this->macaroonHex,
            'Content-Type: application/json',
        ];

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_CAPATH, $this->createTlsCertFromString());
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

        match ($method) {
            'GET' => curl_setopt($curlHandle, CURLOPT_HTTPGET, true),
            'POST' => curl_setopt($curlHandle, CURLOPT_POST, true),
            'PUT' => curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT'),
            'DELETE' => curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE'),
            default => throw new Exception('Invalid HTTP Method'),
        };

        if ($data) {
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = json_decode(curl_exec($curlHandle));
        curl_close($curlHandle);

        if (!$response) {
            throw new Exception('Error: ' . curl_error($curlHandle));
        }

        return json_decode(json_encode($response), true);
    }

    public function getMacaroonHex(): string
    {
        return $this->macaroonHex;
    }
}
