<?php

namespace App\Services;

use App\Interfaces\StrategyWmsInterface;

use App\Utils\ArrayKeysTransform;

use App\Exceptions\PrioriyNotFoundException;

class StrategyWmsService
{
    private $strategyWmsRepository;
    private $arrayTransformer;

    public function __construct(StrategyWmsInterface $strategyWms)
    {
        $this->strategyWmsRepository = $strategyWms;
        $this->arrayTransformer = new ArrayKeysTransform();
    }

    public function findByHourInstant(int $codeStrategy, string $hour, string $instant)
    {
        $returnData = $this->strategyWmsRepository->findByHourInstant($codeStrategy, $hour, $instant);

        if (empty($returnData[0]->nr_prioridade)) {
            throw new PrioriyNotFoundException('Estratégia não encontrada');
        }

        return [
            'nrPrioridade' => $returnData[0]->horariosPrioridade[0]->nr_prioridade ??
                              $returnData[0]->nr_prioridade,
        ];
    }

    public function searchAndPaginate(array $data)
    {
        $responseData = $this->strategyWmsRepository->searchAndPaginate($data)->toArray();

        $responseData['data'] = $this->arrayTransformer->transformUndescoreToCamelCase(
            $responseData['data']
        );

        return $responseData;
    }

    public function store(array $params) :array
    {
        return \DB::transaction(function() use ($params) {

            $transform = $this->arrayTransformer->transformCamelCaseToUndescore($params);
            $transform['ds_estrategia_wms'] = $transform['ds_estrategia'];

            $response = $this->strategyWmsRepository->create($transform);

            $response['ds_estrategia'] = $response['ds_estrategia_wms'];
            $response['cd_estrategia'] = $response['cd_estrategia_wms'];

            unset($response['ds_estrategia_wms'], $response['cd_estrategia_wms']);

            return [
                'status' => 'success',
                'message' => 'Estratégia adicionada com sucesso',
                'data' => $this->arrayTransformer->transformUndescoreToCamelCase($response->toArray()),
            ];

        });

    }

}
