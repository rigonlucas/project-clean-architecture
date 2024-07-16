<?php

namespace Core\Generics\Outputs;

interface GenericOutput
{
    public function getMessages(): string;

    public function getStatus(): OutputStatus;
}
