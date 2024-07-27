<?php

namespace App\Http\Controllers;

use App\Services\StrategyWmsService;

use App\Http\Requests\StoreStrategyFormRequest;

use App\Exceptions\PrioriyNotFoundException;

class StrategyWmsController extends Controller
{
    private $strategyService;
    public function __construct(StrategyWmsService $strategyService)
    {
        $this->strategyService = $strategyService;
    }
    public function storeStrategy(StoreStrategyFormRequest $request)
    {
        $responseLogin = $this->strategyService->store($request->validated());

        return response()->json( $responseLogin
            , $responseLogin['status'] == 'success' ? 200 : 400);
    }

    public function findByIdentityAndHourInstant($codeStrategy, $hour, $instant)
    {
        try {
        $responseData = $this->strategyService->findByHourInstant($codeStrategy, $hour, $instant);

        return response()->json( $responseData, 200);

        } catch (PrioriyNotFoundException $error) {
            return response()->json( ['errors' => [$error->getMessage()]], 404);
        }
    }

}
