<?php

namespace App\Services\NativePHP;

final class NativePlatform
{
    public static function platform(): ?string
    {
        $platform = getenv('NATIVEPHP_PLATFORM') ?: ($_SERVER['NATIVEPHP_PLATFORM'] ?? null);

        return is_string($platform) ? $platform : null;
    }

    public static function isMobile(): bool
    {
        return in_array(self::platform(), ['android', 'ios'], true);
    }
}
