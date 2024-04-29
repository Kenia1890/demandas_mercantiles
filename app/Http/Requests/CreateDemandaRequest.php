<?php

namespace App\Http\Requests;

use App\Models\Banco;
use App\Models\TipoImporte;
use App\Models\TipoPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CreateDemandaRequest extends FormRequest
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
        // Extensión personalizada para validar la clabe con el banco
        Validator::extend('clabe_banco', function ($attribute, $value, $parameters, $validator) {
            $banco_id = $validator->getData()['id_banco'];
            $banco = Banco::find($banco_id);
            return $banco && str_starts_with($value, $banco->clabe_banco);
        }, 'La clabe debe iniciar con la clave del banco.');

        return [
            'fecha' => 'required|date|before_or_equal:today',
            'oficio' => 'required|unique:demandas',
            'id_trabajador' => 'required|exists:trabajador,id',
            'acreedor' => 'required|string|max:255',
            'monto_descontar' => [
                'required',
                'numeric',
                Rule::when(
                    $this->id_tipo_importe == TipoImporte::where('tipo', 'Porcentaje')->value('id'),
                    'max:100',
                    'numeric'
                )
            ],
            'id_tipo_importe' => 'required|exists:tipo_importe,id',
            'id_tipo_pago' => 'required|exists:tipo_pago,id',
            'id_banco' => 'required|exists:bancos,id',
            'clabe' => [
                'required_if:id_tipo_pago,' . TipoPago::where('pago', 'Banco')->value('id'),
                'nullable',
                'digits:18',
                'clabe_banco'
            ],
        ];
    }
    public function messages()
    {
        return [
            'fecha.required' => 'La fecha de sentencia es obligatoria.',
            'fecha.before_or_equal' => 'La fecha de sentencia no puede ser a futuro.',
            'oficio.required' => 'El campo oficio es obligatorio.',
            'oficio.unique' => 'El número de oficio ya existe.',
            'id_trabajador.required' => 'Es necesario seleccionar un trabajador.',
            'id_trabajador.exists' => 'El trabajador seleccionado no existe.',
            'acreedor.required' => 'El campo acreedor es obligatorio.',
            'monto_descontar.required' => 'El monto a descontar es obligatorio.',
            'monto_descontar.numeric' => 'El monto a descontar debe ser un número.',
            'monto_descontar.max' => 'El importe no debe ser mayor a 100 si es porcentaje.',
            'id_tipo_importe.required' => 'Es necesario seleccionar un tipo de importe.',
            'id_tipo_importe.exists' => 'El tipo de importe seleccionado no es válido.',
            'id_tipo_pago.required' => 'Es necesario seleccionar un tipo de pago.',
            'id_tipo_pago.exists' => 'El tipo de pago seleccionado no es válido.',
            'id_banco.required' => 'Es necesario seleccionar un banco.',
            'id_banco.exists' => 'El banco seleccionado no es válido.',
            'clabe.required_if' => 'La clabe es requerida si el tipo de pago es banco.',
            'clabe.digits' => 'La clabe debe tener 18 dígitos.',
            'clabe.clabe_banco' => 'La clabe debe iniciar con la clave del banco correspondiente.'
        ];
    }

}
