<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\CreateAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Transformers\AccountTransformer;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->transformer->showAccountBalance((bool) $request->query('balance', '0'));

        return $this->response($this->service->all());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Account      $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Account $account): JsonResponse
    {
        $this->transformer->showAccountBalance((bool) $request->query('balance', '1'));

        return $this->response($account);
    }

    /**
     * @param \App\Http\Requests\Account\CreateAccountRequest $request
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
     * @param \App\Http\Requests\Account\UpdateAccountRequest $request
     * @param \App\Models\Account                             $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAccountRequest $request, Account $account): JsonResponse
    {
        $params = $request->validated();

        $this->service->update($account, $params);

        return $this->response($account);
    }

    /**
     * @param \App\Models\Account $account
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Account $account): JsonResponse
    {
        $this->service->delete($account);

        return $this->responseNoContent();
    }
}
