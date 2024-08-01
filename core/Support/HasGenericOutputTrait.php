<?php

namespace Core\Support;

use Core\Generics\Outputs\GenericOutputInterface;

trait HasGenericOutputTrait
{
    private GenericOutputInterface $output;

    public function getOutput(): GenericOutputInterface
    {
        return $this->output;
    }
}