<?php

namespace Core\Adapters\App;

class AppAdapter implements AppInterface
{
    public function isDevelopeMode(): bool
    {
        return app()->isLocal();
    }
}
