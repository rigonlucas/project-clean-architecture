<?php

namespace Core\Generics\Outputs;

use Core\Generics\Presenters\GenericPresenter;

interface GenericOutput
{
    public function getMessages(): string;

    public function getStatus(): OutputStatus;

    public function getPresenter(): GenericPresenter;
}
