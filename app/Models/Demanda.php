<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Demanda
 *
 * @property $id
 * @property $fecha
 * @property $oficio
 * @property $id_trabajador 
 * @property $acreedor
 * @property $id_tipo_importe 
 * @property $id_tipo_pago
 * @property $monto_descontar
 * @property $id_banco 
 * @property $clabe
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Demanda extends Model
{
    protected $table = 'demandas';
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['fecha', 'oficio','id_tipo_pago', 'id_trabajador','id_tipo_importe', 'acreedor', 'tipo', 'monto_descontar', 'id_banco', 'clabe'];
    
    public function trabajador()
    {
        return $this->belongsTo('App\Models\Trabajador', 'id_trabajador');
    }

    public function tipoImporte()
    {
        return $this->belongsTo('App\Models\TipoImporte', 'id_tipo_importe');
    }

    public function tipoPago()
    {
        return $this->belongsTo('App\Models\TipoPago', 'id_tipo_pago');
    }

    public function banco()
    {
        return $this->belongsTo('App\Models\Banco', 'id_banco');
    }

}
