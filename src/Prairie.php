<?php

namespace Prairie;

class Prairie
{
    public function __construct(
        protected array $bootstrap = [],
    ) {}

    public static function hello(): string
    {
        return 'Hello!';
    }

    /**
     * JSON object fetched from the APP_KEY bootstrap URL.
     */
    public function bootstrap(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->bootstrap;
        }

        return data_get($this->bootstrap, $key, $default);
    }

    public function hasBootstrap(): bool
    {
        return $this->bootstrap !== [];
    }
}