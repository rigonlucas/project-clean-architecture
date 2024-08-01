<?php

namespace Core\Generics\Outputs;

use Core\Generics\Presenters\ToArrayPresenterInterface;

interface GenericOutputInterface
{
    public function getMessage(): string;

    public function getStatus(): OutputStatus;

    public function getPresenter(): ToArrayPresenterInterface;
}
