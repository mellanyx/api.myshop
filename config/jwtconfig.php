<?php

declare(strict_types=1);

$issuedAt = new DateTimeImmutable();

return [
    'key' => 'DJK23!#Eklewe#io23213#vgfdsdiiHDVGUEWDWN',
    'iss' => 'myshop.test',
    'aud' => 'myshop.test',
    'iat' => $issuedAt->getTimestamp(),
    'nbf' => $issuedAt->getTimestamp(),
    'exp' => $issuedAt->modify('+10 minutes')->getTimestamp()
];
