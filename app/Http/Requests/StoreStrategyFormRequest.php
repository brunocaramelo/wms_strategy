<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\StrategyWmsHourPriority;

use Carbon\Carbon;

use Illuminate\Support\Collection;
class StoreStrategyFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dsEstrategia' => 'required|in:RETIRA,ENVIA,TRAJETO',
            'nrPrioridade' => 'required|integer',

            'horarios' => 'required',
            'horarios.*.dsHorarioInicio' => 'required|string',
            'horarios.*.dsHorarioFinal' => 'required|string',
            'horarios.*.nrPrioridade' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email é requerido',
            'email.email' => 'Email deve ser válido',
            'password.required' => 'Senha é requerida',
            'password.min' => 'Senha deve conter 5 caracteres',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if(count($validator->errors()) > 0) {
                return;
            }

            $sortedCollection = collect($validator->safe()->horarios)->map(function ($item) {
                $item['dsHorarioInicioTimezone'] = Carbon::createFromFormat('H:i', $item['dsHorarioInicio']);
                return $item;
            })->sortBy(function ($item) {
                return $item['dsHorarioInicioTimezone'];
            })->toArray();

            $prevItens = collect([]);

            foreach ($sortedCollection as $indiceReg => $registro) {

                $dataInicio = Carbon::parse($registro['dsHorarioInicio']);
                $dataFim = Carbon::parse($registro['dsHorarioFinal']);

                if($indiceReg > 0) {
                    $prevItens->add($sortedCollection[$indiceReg -1]);
                }

                if($this->verificaColisao($prevItens, $dataInicio, $dataFim)) {
                    $validator->errors()->add('horarios.'.$indiceReg.'.conflito', 'Conflito de range '.$registro['dsHorarioInicio'].' ate '.$registro['dsHorarioFinal'].' na lista');
                }


            }

            return $validator;
        });

    }

    private function verificaColisao(Collection $horarios, Carbon $inicioNovo, Carbon $fimNovo)
    {
            foreach ($horarios as $horario) {

                $dataInicioCarbon = Carbon::parse($horario['dsHorarioInicio']);
                $dataFimCarbon = Carbon::parse($horario['dsHorarioFinal']);

                if (
                    $inicioNovo->between($dataInicioCarbon, $dataFimCarbon) ||
                    $fimNovo->between($dataInicioCarbon, $dataFimCarbon) ||
                    $dataInicioCarbon->between($inicioNovo, $fimNovo) ||
                    $dataFimCarbon->between($inicioNovo, $fimNovo)
                ) {
                    return true;
                }
            }

            return false;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422));
    }

}
