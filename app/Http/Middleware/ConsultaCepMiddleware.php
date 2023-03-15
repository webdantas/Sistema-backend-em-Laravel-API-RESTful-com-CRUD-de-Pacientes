<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class ConsultaCepMiddleware
{
    public function handle($request, Closure $next)
    {
        // verifica se o CEP foi enviado na requisição
        $cep = $request->input('cep');
        if ($cep) {
            // verifica se o endereço já está armazenado no cache
            $endereco = Redis::get('cep_' . $cep);
            if (!$endereco) {
                // faz a consulta na API do serviço de consulta de CEP
                $client = new Client();
                $response = $client->request('GET', 'https://exemplo.com/consultacep/' . $cep);
                $endereco = $response->getBody();

                // armazena o endereço no cache por 1 hora
                Redis::setex('cep_' . $cep, 3600, $endereco);
            }

            // adiciona os dados do endereço na requisição
            $endereco = json_decode($endereco, true);
            $request->merge([
                'endereco' => [
                    'logradouro' => $endereco['logradouro'],
                    'numero' => '',
                    'complemento' => '',
                    'bairro' => $endereco['bairro'],
                    'cidade' => $endereco['cidade'],
                    'estado' => $endereco['estado'],
                    'cep' => $cep,
                ],
            ]);
        }

        return $next($request);
    }
}
