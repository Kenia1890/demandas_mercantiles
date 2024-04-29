<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Demanda
 *
 * @property $id
 * @property $rfc
 * @property $apaterno
 * @property $amaterno
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Trabajador extends Model
{
    use HasFactory;
    protected $table = 'trabajador';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['rfc', 'apaterno', 'amaterno', 'nombre'];
}
