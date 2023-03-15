<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'password'    => $this->password,
            'CPF'         => $this->CPF,
            'CNS'         => $this->CNS,
            'RG'          => $this->RG,
            'cep'         => $this->cep,
            'nascimento'  => $this->nascimento,
            'nome_mae'    => $this->nome_mae,
            'foto'        => $this->foto,

            'endereco'    => $this->endereco,
            'numero'      => $this->numero,
            'complemento' => $this->complemento,
            'bairro'      => $this->bairro,
            'cidade'      => $this->cidade,
            'estado'      => $this->estado,

            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
