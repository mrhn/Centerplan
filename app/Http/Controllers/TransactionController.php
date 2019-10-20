<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\DeleteTransactionRequest;
use App\Http\Requests\Transaction\ShowTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Account;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

/**
 * Class TransactionController.
 *
 * @property \App\Http\Transformers\TransactionTransformer $transformer
 * @property \App\Services\TransactionService              $service
 */
class TransactionController extends RestController
{
    public function __construct(
        TransactionTransformer $transformer,
        TransactionService $service
    ) {
        parent::__construct($transformer, $service);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->response($this->service->all());
    }

    /**
     * @param \App\Http\Requests\Transaction\ShowTransactionRequest $request
     * @param \App\Models\Account                                   $account
     * @param \App\Models\Transaction                               $transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowTransactionRequest $request, Account $account, Transaction $transaction): JsonResponse
    {
        return $this->response($transaction);
    }

    /**
     * @param \App\Http\Requests\Transaction\CreateTransactionRequest $request
     * @param \App\Models\Account                                     $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTransactionRequest $request, Account $account): JsonResponse
    {
        $params = $request->validated();

        return $this->response(
            $this->service->create($params, $account),
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @param \App\Http\Requests\Transaction\UpdateTransactionRequest $request
     * @param \App\Models\Account                                     $account
     * @param \App\Models\Transaction                                 $transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTransactionRequest $request, Account $account, Transaction $transaction): JsonResponse
    {
        $params = $request->validated();

        $this->service->update($transaction, $params);

        return $this->response($transaction);
    }

    /**
     * @param \App\Http\Requests\Transaction\DeleteTransactionRequest $request
     * @param \App\Models\Account                                     $account
     * @param \App\Models\Transaction                                 $transaction
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteTransactionRequest $request, Account $account, Transaction $transaction): JsonResponse
    {
        $this->service->delete($transaction);

        return $this->responseNoContent();
    }
}
