<?php

namespace App\Data;

use App\Enum\AlertStatus;

/** @typescript */
class AlertDto
{
    public function __construct(
        public AlertStatus $type,
        public string $message,
    ) {
    }

    public static function success(string $message): AlertDto
    {
        return new self(AlertStatus::Success, $message);
    }

    public static function error(string $message): AlertDto
    {
        return new self(AlertStatus::Error, $message);
    }

    public static function info(string $message): AlertDto
    {
        return new self(AlertStatus::Info, $message);
    }

    public static function warning(string $message): AlertDto
    {
        return new self(AlertStatus::Warning, $message);
    }
}
