<?php

namespace App\Http\Controllers;

use App\Http\Transformers\TransactionTransformer;
use App\Services\TransactionService;

class TransactionController extends RestController
{
    public function __construct(TransactionTransformer $transformer, TransactionService $service)
    {
        parent::__construct($transformer, $service);
    }
}
