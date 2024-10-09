<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

final class BuilderHelper
{
    public static function overlap(
        Builder $baseBuilder,
        string $firstCollum,
        string $secondCollum,
        mixed $firstValue,
        mixed $secondValue
    ): Builder {
        return $baseBuilder
            ->where(fn(Builder $builder) => $builder
                ->whereBetween($firstCollum, [$firstValue, $secondValue])
                ->orWhereBetween($secondCollum, [$firstValue, $secondValue])
                ->orWhere(
                    fn(Builder $builder) => $builder
                        ->where($firstCollum, '<=', $firstValue)
                        ->where($secondCollum, '>=', $secondValue)
                )
            );
    }
}
