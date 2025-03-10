<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter
{
    public function handle($query, Closure $next)
    {
        if (request()->filled('category')) {
            $query->whereHas('categories', function (Builder $query) {
                return $query->where("categories.id", strtolower(request('category')));
            });
        }

        return $next($query);
    }
}
