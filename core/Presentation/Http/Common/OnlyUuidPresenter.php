<?php

namespace Core\Presentation\Http\Common;

use Core\Support\Presentation\PresentationBase;
use Ramsey\Uuid\UuidInterface;

class OnlyUuidPresenter extends PresentationBase
{
    public function __construct(UuidInterface $uuid)
    {
        $this->data = [
            'uuid' => $uuid->toString()
        ];
    }
}
