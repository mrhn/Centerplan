<?php

namespace App\Http\Controllers;

use App\Services\ModelServiceContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use League\Fractal\TransformerAbstract;

abstract class RestController extends Controller
{
    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    /**
     * @var ModelServiceContract
     */
    protected $service;

    /**
     * RestController constructor.
     *
     * @param \League\Fractal\TransformerAbstract $transformer
     * @param \App\Services\ModelServiceContract  $service
     */
    public function __construct(TransformerAbstract $transformer, ModelServiceContract $service)
    {
        $this->transformer = $transformer;
        $this->service = $service;
    }

    /**
     * Generic method for transforming data with fractal.
     *
     * @param mixed $data
     * @param int   $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data, int $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        return fractal($data, $this->transformer)
            ->respond($statusCode)
        ;
    }

    /**
     * Respond with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseNoContent(): JsonResponse
    {
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Parameters for PATCH / PUT is different.
     * A generic way of getting them.
     * Improvement could a macro for the request object, but type hinting would be awkward.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return array
     */
    protected function getUpdateParameters(FormRequest $request)
    {
        $parameters = $request->validated();

        if ('PATCH' === $request->getMethod()) {
            foreach ($parameters as $key => $value) {
                if (!$request->json()->has($key)) {
                    unset($parameters[$key]);
                }
            }
        }

        return $parameters;
    }
}
