<?php

namespace Core\Support;

use Core\Generics\Outputs\GenericOutput;

trait HasGenericOutputTrait
{
    private GenericOutput $output;

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}