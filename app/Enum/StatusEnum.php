<?php

declare(strict_types=1);

namespace App\Enum;

enum ConnectionStatusTypes
{
    case INTERNET_CONNECTION_FAILED;
    case INTERNET_CONNECTION_TIMEOUT;
    case INTERNET_CONNECTION_BLOCKED;
    case SOCKET_EXTENSION_NOT_LOADED;
    case SOCKET_CONNECTION_FAILED;
}
