<?php

declare(strict_types=1);

namespace App\Domain\Strava\Rewind\FindMovingTimePerGear;

use App\Infrastructure\CQRS\Query\Query;
use App\Infrastructure\ValueObject\Time\Years;

/**
 * @implements Query<\App\Domain\Strava\Rewind\FindMovingTimePerGear\FindMovingTimePerGearResponse>
 */
final readonly class FindMovingTimePerGear implements Query
{
    public function __construct(
        private Years $years,
    ) {
    }

    public function getYears(): Years
    {
        return $this->years;
    }
}
