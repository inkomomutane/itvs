<?php

namespace App\Data;
/**
 * @typescript
 */
class KeyValueDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $key,
        public string $value,
    )
    {
    }
}
