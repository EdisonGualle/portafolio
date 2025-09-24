<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesQueryFilters
{
    protected function parseListInput(Request $request, string $key, ?string $alternate = null): array
    {
        $value = $request->query($key);

        if ($value === null && $alternate !== null) {
            $value = $request->query($alternate);
        }

        if ($value === null) {
            return [];
        }

        if (is_array($value)) {
            $items = $value;
        } else {
            $items = explode(',', (string) $value);
        }

        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter(fn ($item) => $item !== '')
            ->values()
            ->all();
    }

    protected function requestedIncludes(Request $request, array $allowed): array
    {
        $requested = $this->parseListInput($request, 'include');

        return array_values(array_intersect($allowed, $requested));
    }

    protected function resolvePerPage(Request $request, int $default = 15, int $max = 50): int
    {
        $perPage = (int) ($request->query('per_page') ?? $default);

        if ($perPage < 1) {
            return $default;
        }

        return min($perPage, $max);
    }

    protected function resolveStatuses(Request $request, array $allowed, array $default): array
    {
        $values = $this->parseListInput($request, 'status');

        $statuses = collect($values)
            ->map(fn ($status) => strtolower($status))
            ->filter(fn ($status) => in_array($status, $allowed, true))
            ->unique()
            ->values()
            ->all();

        return !empty($statuses) ? $statuses : $default;
    }

    protected function booleanFilter(Request $request, string $key): ?bool
    {
        if (! $request->has($key)) {
            return null;
        }

        $value = $request->query($key);

        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value) || is_numeric($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        return null;
    }

    protected function resolveSort(?string $sort, array $allowed, array $fallback): array
    {
        if ($sort === null || trim($sort) === '') {
            return [$fallback[0], $fallback[1], $fallback[2] ?? null];
        }

        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $key = ltrim($sort, '-');

        if (! array_key_exists($key, $allowed)) {
            return [$fallback[0], $fallback[1], $fallback[2] ?? null];
        }

        return [$allowed[$key], $direction, $key];
    }
}
