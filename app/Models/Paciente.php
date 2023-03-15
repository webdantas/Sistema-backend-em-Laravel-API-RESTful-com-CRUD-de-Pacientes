<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'cpf', 'cns', 'rg',
        'nascimento', 'nome_mae', 'foto'
    ];

    public function endereco()
    {
        return $this->hasOne(Endereco::class);
    }
}
