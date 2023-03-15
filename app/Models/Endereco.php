<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';
    protected $fillable = [
        'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep'
    ];


    public static function buscarEnderecoPorCep($cep) {
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->getStatusCode() == 200) {
            $endereco = json_decode($response->getBody());

            // Se o CEP não for encontrado, retorna null
            if (property_exists($endereco, 'erro')) {
                return null;
            }

            // Retorna um array com os dados do endereço
            return [
                'endereco' => $endereco->logradouro,
                'numero' => '',
                'complemento' => $endereco->complemento ?? '',
                'bairro' => $endereco->bairro,
                'cidade' => $endereco->localidade,
                'estado' => $endereco->uf,
            ];
        }

        // Em caso de erro na requisição, retorna null
        return null;
    }

    public static function criarEndereco($dados) {
        // Busca os dados do endereço a partir do CEP informado
        $enderecoPorCep = self::buscarEnderecoPorCep($dados['cep']);

        // Cria o endereço com os dados informados e os dados obtidos a partir do CEP
        $endereco = new Endereco();
        $endereco->cep = $dados['cep'];
        $endereco->endereco = $dados['endereco'] ?? $enderecoPorCep['endereco'];
        $endereco->numero = $dados['numero'] ?? $enderecoPorCep['numero'];
        $endereco->complemento = $dados['complemento'] ?? $enderecoPorCep['complemento'];
        $endereco->bairro = $dados['bairro'] ?? $enderecoPorCep['bairro'];
        $endereco->cidade = $dados['cidade'] ?? $enderecoPorCep['cidade'];
        $endereco->estado = $dados['estado'] ?? $enderecoPorCep['estado'];
        $endereco->save();

        return $endereco;
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
