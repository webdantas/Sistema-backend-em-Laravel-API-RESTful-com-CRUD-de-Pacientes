<?php

namespace App\Services;

use App\Jobs\ImportPacientesJob;
use Illuminate\Support\Facades\Storage;

class ImportPacientesService
{
    /**
     * Import patients from CSV file
     *
     * @param string $filePath
     * @return void
     */
    public function importFromFile(string $filePath)
    {
        $fileContent = Storage::get($filePath);

        $patientsData = $this->parseCsvToArray($fileContent);

        foreach ($patientsData as $patientData) {
            // criar paciente no banco de dados
        }
    }

    /**
     * Parse CSV file content to array
     *
     * @param string $fileContent
     * @return array
     */
    protected function parseCsvToArray(string $fileContent)
    {
        // l
