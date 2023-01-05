<?php

$macaroonHex = bin2hex(file_get_contents('/home/barry/Projects/lnd-php/admin.testnet.macaroon'));

if (!ctype_xdigit($macaroonHex)) {
    throw new Exception("Macaroon hex is not valid");
}

echo $macaroonHex;
