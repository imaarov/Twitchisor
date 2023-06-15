<?php

declare(strict_types=1);

namespace App\Service;

class EnvService
{
    private static string $env_path;

    public static function get(string $key): string|bool
    {
        if(self::checkFile() && isset(self::$env_path)) {
            return self::loadFile()[$key] ?? false;
        }
        return false;
    }

    public static function checkFile(): bool
    {
        return (bool)(
            is_file(self::$env_path)     ||
            is_readable(self::$env_path) ||
            is_writable(self::$env_path)
        );
    }

    public static function loadFile(): array
    {
        $lines = file(self::$env_path);
        $env_arr = [];
        foreach ($lines as $line) {
            $env_data = explode('=', $line);
            $env_arr[$env_data[0]] = $env_data[1];
        }
        return $env_arr;
    }

    public static function set(...$args): void
    {
        foreach ($args as $key => $value) {
            self::${$key} = $value;
        }
    }
}
