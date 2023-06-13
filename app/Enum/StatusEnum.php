<?php
namespace App\Enum;

enum InternetStatusTypes {
    case INTERNET_CONNECTION_FAILED;
    case INTERNET_CONNECTION_TIMEOUT;
    case INTERNET_CONNECTION_BLOCKED;
}
