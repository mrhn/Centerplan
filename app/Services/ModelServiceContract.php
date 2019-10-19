<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ModelServiceContract
{
    public function all(): Collection;

    public function create(array $parameters): Model;

    public function update(Model $model, array $parameters): void;

    public function delete(Model $model): void;
}
