<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportarPlanilhaDePacientes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = Storage::get($this->path);

        $rows = explode("\n", $file);
        $header = str_getcsv(array_shift($rows));

        foreach ($rows as $row) {
            if (empty(trim($row))) {
                continue;
            }

            $data = array_combine($header, str_getcsv($row));

            // Insere o paciente na tabela
            DB::table('pacientes')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'cns' => $data['cns'],
                'rg' => $data['rg'],
                'nascimento' => $data['nascimento'],
                'nome_mae' => $data['nome_mae'],
                'foto' => $data['foto'],
            ]);
        }

        // Remove o arquivo da pasta de uploads
        Storage::delete($this->path);
    }
}
