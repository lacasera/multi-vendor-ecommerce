<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;

class PriceFilter
{
    public function handle($query, Closure $next)
    {
        if (request()->filled('min_price') && request()->filled('max_price')) {
            $query->whereBetween('price', [request('min_price')  * 100, request('max_price') * 100]);
            return $next($query);
        }

        if (request()->filled('min_price')) {
            $query->where('price', '>=' , request('min_price')  * 100);
        }

        if (request()->filled('max_price')) {
            $query->where('price', '<=' , request('max_price') * 100);
        }

        return $next($query);
    }
}
