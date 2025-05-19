<?php

declare(strict_types=1);

namespace App\services\Weather\Domain\Exception;

use RuntimeException;

final class WeatherNotFoundException extends RuntimeException
{
}