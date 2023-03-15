<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class PacienteTest extends TestCase
{
    use RefreshDatabase;

    public function testPacienteIndex()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/pacientes');

        $response->assertOk();
    }

    public function testPacienteStore()
    {
        $user = User::factory()->create();

        $pacienteData = [
            'name' => 'João da Silva',
            'email' => 'joao.silva@gmail.com',
            'phone' => '1199999999',
            'address' => 'Rua Teste, 123'
        ];

        $response = $this->actingAs($user)->postJson('/api/pacientes', $pacienteData);

        $response->assertCreated();
    }

    public function testPacienteUpdate()
    {
        $user = User::factory()->create();

        $paciente = $user->pacientes()->create([
            'name' => 'João da Silva',
            'email' => 'joao.silva@gmail.com',
            'phone' => '1199999999',
            'address' => 'Rua Teste, 123'
        ]);

        $newData = [
            'name' => 'João Silva',
            'email' => 'joao.silva2@gmail.com',
            'phone' => '1188888888',
            'address' => 'Rua Teste, 456'
        ];

        $response = $this->actingAs($user)->putJson("/api/pacientes/{$paciente->id}", $newData);

        $response->assertOk();
    }

    public function testPacienteDelete()
    {
        $user = User::factory()->create();

        $paciente = $user->pacientes()->create([
            'name' => 'João da Silva',
            'email' => 'joao.silva@gmail.com',
            'phone' => '1199999999',
            'address' => 'Rua Teste, 123'
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/pacientes/{$paciente->id}");

        $response->assertOk();
    }

    public function testPacienteImportar()
    {
        $user = User::factory()->create();

        $file = storage_path('app/public/test.csv');

        $response = $this->actingAs($user)->postJson('/api/pacientes/importar', [
            'planilha' => new \Illuminate\Http\UploadedFile($file, 'test.csv', null, null, true)
        ]);

        $response->assertOk();
    }

    public function testPacienteImportarSemArquivo()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/pacientes/importar');

        $response->assertStatus(400);
    }

    public function testImportPlanilhaDeveRetornarMensagemDeSucesso()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->createWithContent('pacientes.csv', "
            name,email,phone,address
            João,john@example.com,99999999,Rua A
            Maria,maria@example.com,88888888,Rua B
            Pedro,pedro@example.com,77777777,Rua C
        ");

        $response = $this->postJson('/api/pacientes/importar', [
            'planilha' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'A planilha está sendo processada.'
            ]);
    }
}
