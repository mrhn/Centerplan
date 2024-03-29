<?php

namespace App\Http\Controllers;

use App\Services\ModelService;
use Illuminate\Http\JsonResponse;
use League\Fractal\TransformerAbstract;

abstract class RestController extends Controller
{
    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    /**
     * @var \App\Services\ModelService
     */
    protected $service;

    /**
     * RestController constructor.
     *
     * @param \League\Fractal\TransformerAbstract $transformer
     * @param \App\Services\ModelService          $service
     */
    public function __construct(TransformerAbstract $transformer, ModelService $service)
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
}
