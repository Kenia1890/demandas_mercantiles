<?php

namespace App\Http\Requests;

use App\Models\Banco;
use App\Models\TipoImporte;
use App\Models\TipoPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class UpdateDemandaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        Validator::extend('clabe_banco', function ($attribute, $value, $parameters, $validator) {
            $banco_id = $validator->getData()['banco_id'];
            $banco = Banco::find($banco_id);
            return str_starts_with($value, $banco->clabe_banco);
        }, 'La clabe debe iniciar con la clave del banco.');

        return [
            'demanda_id' => 'required|exists:demandas,id',
            'fecha' => 'required|date|before_or_equal:today',
            'oficio' => ['required', Rule::unique('demandas')->ignore($this->demanda_id)],
            'monto_descontar' => [
                'required',
                'numeric',
                Rule::when(
                    $this->tipo_importe_id == TipoImporte::where('tipo', 'Porcentaje')->value('id'),
                    'max:100'
                )
            ],
            'tipo_importe_id' => 'required|exists:tipo_importe,id',
            'tipo_pago_id' => 'required|exists:tipo_pago,id',
            'banco_id' => 'required|exists:bancos,id',
            'clabe' => [
                'required_if:tipo_pago_id,' . TipoPago::where('pago', 'Banco')->value('id'),
                'nullable',
                'digits:18',
                'clabe_banco'
            ],
        ];
    }
    public function messages()
    {
        return [
            'fecha.before_or_equal' => 'La fecha de sentencia no puede ser a futuro.',
            'oficio.unique' => 'El número de oficio ya existe.',
            'monto_descontar.max' => 'El importe no debe ser mayor a 100 si es porcentaje.',
            'monto_descontar.numeric' => 'El monto a descontar debe ser un número.',
            'monto_descontar.required' => 'El monto a descontar es obligatorio.',
            'clabe.required_if' => 'La clabe es requerida si el tipo de pago es banco.',
            'clabe.digits' => 'La clabe debe tener 18 dígitos.',
            'clabe.clabe_banco' => 'La clabe debe iniciar con la clave del banco correspondiente.',
            'tipo_importe_id.exists' => 'El tipo de importe seleccionado no es válido.',
            'tipo_pago_id.exists' => 'El tipo de pago seleccionado no es válido.',
            'banco_id.exists' => 'El banco seleccionado no es válido.',
        ];
    }

}
