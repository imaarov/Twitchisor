<?php

declare(strict_types=1);

namespace App\Service;

class AppService
{
    public function __construct(
        private string $username,
        private string $oauth
    ) {
    }
}
