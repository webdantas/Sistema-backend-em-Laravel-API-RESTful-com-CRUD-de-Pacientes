<?php

namespace Database\Factories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    protected $model = Paciente::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'CPF' => $this->faker->unique()->numerify('###########'),
            'CNS' => $this->faker->unique()->numerify('###########'),
            'RG' => $this->faker->unique()->numerify('##########'),
            'nascimento' => $this->faker->date(),
            'nome_mae' => $this->faker->name,
            'cep' => $this->faker->numerify('#####-###'),
            'endereco' => $this->faker->streetName,
            'numero' => $this->faker->buildingNumber,
            'complemento' => $this->faker->sentence,
            'bairro' => $this->faker->citySuffix,
            'cidade' => $this->faker->city,
            'estado' => $this->faker->stateAbbr
        ];
    }
}
