<?php
namespace App\Service;

class AppService {
    public function __construct(
        private string $username,
        private string $oauth
    )
    {}
}
