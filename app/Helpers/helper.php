<?php

if (! function_exists('apiSession')) {
    function apiSession(?string $key = null, mixed $default = null): mixed
    {
        $payload = session('api.auth', []);

        return $key === null ? $payload : data_get($payload, $key, $default);
    }
}

if (! function_exists('authUser')) {
    function authUser(): ?array
    {
        return apiSession('user');
    }
}

if (! function_exists('employee')) {
    function employee(): ?array
    {
        return apiSession('employee') ?: data_get(authUser(), 'employee');
    }
}

if (! function_exists('branch')) {
    function branch(): mixed
    {
        return apiSession('branch') ?: data_get(employee(), 'branch');
    }
}

if (! function_exists('roles')) {
    function roles(): array
    {
        return (array) apiSession('roles', []);
    }
}

if (! function_exists('authUserId')) {
    function authUserId(): int|string|null
    {
        return data_get(authUser(), 'id') ?? data_get(employee(), 'user_id');
    }
}

if (! function_exists('employeeName')) {
    function employeeName(): string
    {
        return data_get(employee(), 'full_name')
            ?? data_get(employee(), 'name')
            ?? data_get(authUser(), 'name')
            ?? 'Employee';
    }
}

if (! function_exists('branchName')) {
    function branchName(): string
    {
        return data_get(branch(), 'title')
            ?? data_get(branch(), 'name')
            ?? (is_string(branch()) ? branch() : '-');
    }
}

if (! function_exists('token')) {
    function token(): ?string
    {
        return apiSession('token');
    }
}

if (! function_exists('profileImage')) {
    function profileImage(): ?string
    {
        foreach ([
            apiSession('profile_image'),
            data_get(employee(), 'profile_image'),
            data_get(employee(), 'profile_photo'),
            data_get(employee(), 'image'),
            data_get(authUser(), 'profile_image'),
            data_get(authUser(), 'image'),
            data_get(authUser(), 'avatar'),
        ] as $candidate) {
            $image = imageValue($candidate);

            if ($image !== null) {
                return $image;
            }
        }

        return null;
    }
}

if (! function_exists('imageValue')) {
    function imageValue(mixed $value): ?string
    {
        if (is_string($value) && trim($value) !== '') {
            return absoluteApiUrl($value);
        }

        if (! is_array($value)) {
            return null;
        }

        foreach (['url', 'full_url', 'original_url', 'preview_url', 'thumbnail', 'path', 'src', 'image', 'profile_image', 'avatar'] as $key) {
            $candidate = data_get($value, $key);

            if (is_string($candidate) && trim($candidate) !== '') {
                return absoluteApiUrl($candidate);
            }
        }

        foreach ($value as $candidate) {
            if (is_string($candidate) && trim($candidate) !== '') {
                return absoluteApiUrl($candidate);
            }
        }

        return null;
    }
}

if (! function_exists('absoluteApiUrl')) {
    function absoluteApiUrl(string $value): string
    {
        $value = trim($value);

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }

        $baseUrl = rtrim((string) config('app.url'), '/');

        if (str_contains((string) config('services.eemot_api.base_url'), 'new.eemotclocking.in')) {
            $baseUrl = 'https://new.eemotclocking.in';
        }

        return $baseUrl.'/'.ltrim($value, '/');
    }
}
