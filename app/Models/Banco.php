<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Demanda
 *
 * @property $id
 * @property $banco
 * @property $clabe_banco
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */



class Banco extends Model
{
   
    use HasFactory;
    protected $table = 'bancos';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['banco', 'clabe_banco'];

}
