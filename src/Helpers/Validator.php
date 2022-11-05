<?php

namespace UtxoOne\LndPhp\Helpers;

use Exception;

class Validator
{
    /**
     * Validate Host String
     * 
     * Validates that the host follows the format of host:port
     * 
     * @param string $host
     * @return string
     * 
     * @throws Exception
     */
    public static function validateHost(string $host): string
    {
        // Validate host format
        $regex = "/[^a-z_\-0-9]/i";

        if (!preg_match($regex, $host)) {
            throw new Exception("Invalid host format");
        }

        return $host;
    }

    /**
     * Validate Macaroon Path
     * 
     * Validates that the macaroon path is a valid file
     * 
     * @param string $macaroonPath
     * @return string
     * 
     * @throws Exception
     */
    public static function validateMacaroonPath(string $macaroonPath): string
    {
        if (!file_exists($macaroonPath)) {
            throw new Exception("Macaroon file does not exist at location: " . $macaroonPath);
        }

        return $macaroonPath;
    }

    /**
     * Validate Macaroon Hex
     * 
     * Validates that the macaroon hex is a valid hex string
     * 
     * @param string $macaroonHex
     * @return string
     * 
     * @throws Exception
     */
    public static function validateMacaroonHex(string $macaroonPath): string
    {
        $macaroonHex = bin2hex(file_get_contents($macaroonPath));
        
        if (!ctype_xdigit($macaroonHex)) {
            throw new Exception("Macaroon hex is not valid");
        }

        return $macaroonHex;
    }

    /**
     * Validate TLS Certificate Path
     * 
     * Validates that the TLS certificate path is a valid file
     * 
     * @param string $tlsCertificatePath
     * @return string
     * 
     * @throws Exception
     */
    public static function validateTlsCertificatePath(string $tlsCertificatePath): string
    {
        if (!file_exists($tlsCertificatePath)) {
            throw new Exception("TLS Certificate file does not exist");
        }

        return $tlsCertificatePath;
    }
}