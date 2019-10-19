<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Transformers\AccountTransformer;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

/**
 * Class AccountController.
 *
 * @property \App\Http\Transformers\AccountTransformer $transformer
 * @property \App\Services\AccountService              $service
 */
class AccountController extends RestController
{
    public function __construct(
        AccountTransformer $transformer,
        AccountService $service
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
     * @param \App\Models\Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Account $account): JsonResponse
    {
        return $this->response($account);
    }

    /**
     * @param \App\Http\Requests\CreateAccountRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAccountRequest $request): JsonResponse
    {
        $params = $request->validated();

        return $this->response(
            $this->service->create($params),
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @param \App\Http\Requests\UpdateAccountRequest $request
     * @param \App\Models\Account                     $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAccountRequest $request, Account $account): JsonResponse
    {
        $params = $this->getUpdateParameters($request);

        $this->service->update($account, $params);

        return $this->response($account);
    }

    /**
     * @param \App\Models\Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Account $account): JsonResponse
    {
        $this->service->delete($account);

        return $this->responseNoContent();
    }
}
