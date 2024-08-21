<?php

namespace Core\Support\Presentation;

class PresentationBase
{
    protected array $data;

    public function withDataAttribute(): self
    {
        $this->data['data'] = $this->data;
        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
