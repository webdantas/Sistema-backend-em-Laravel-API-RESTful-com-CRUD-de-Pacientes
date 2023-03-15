<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PacienteResource;
use App\Jobs\ImportarPlanilhaDePacientes;
use App\Jobs\ImportPacientesJob;
use App\Models\Endereco;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Jobs\ProcessCsvImport;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = Paciente::query();

    if ($request->has('nome')) {
        $query->where('name', 'LIKE', '%' . $request->input('nome') . '%');
    }

    if ($request->has('cpf')) {
        $query->where('cpf', $request->input('cpf'));
    }

    $pacientes = $query->get();

    return response()->json($pacientes);
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados do paciente
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:pacientes',
            'password' => 'required',
            'CPF' => 'required|unique:pacientes',
            'CNS' => 'required|unique:pacientes',
            'RG' => 'required|unique:pacientes',
            'nascimento' => 'required',
            'nome_mae' => 'required',
            'foto' => 'nullable|image|max:2048',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'complemento' => 'nullable',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ]);

        // Cria o registro de endereço
        $endereco = Endereco::create([
            'cep' => $validatedData['cep'],
            'endereco' => $validatedData['endereco'],
            'numero' => $validatedData['numero'],
            'complemento' => $validatedData['complemento'],
            'bairro' => $validatedData['bairro'],
            'cidade' => $validatedData['cidade'],
            'estado' => $validatedData['estado'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $// Cria o paciente
        $paciente = Paciente::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'CPF' => $validatedData['CPF'],
            'CNS' => $validatedData['CNS'],
            'RG' => $validatedData['RG'],
            'nascimento' => $validatedData['nascimento'],
            'nome_mae' => $validatedData['nome_mae'],
            'foto' => '',
            'endereco_id' => $endereco->id,
        ]);
        $endereco = Endereco::create([
            'cep' => $request->cep,
            'endereco' => $request->endereco,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
        ]);

        return response()->json(['message' => 'Paciente criado com sucesso', 'paciente' => $paciente]);
    }

    /**PacienteResourcethe specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return response()->json(['data' => new PacienteResource($paciente), 'message' => 'Paciente fetched.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pacientes')->ignore($paciente->id),
            ],
            'password'    => 'nullable|string|min:8',
            'cpf'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('pacientes')->ignore($paciente->id),
            ],
            'cns'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('pacientes')->ignore($paciente->id),
            ],
            'rg'          => 'required|string|max:255',
            'nascimento'  => 'required|string|max:255',
            'nome_mae'    => 'required|string|max:255',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'cep'         => 'required|string|max:10',
            'endereco'    => 'required|string|max:255',
            'numero'      => 'required|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro'      => 'required|string|max:255',
            'cidade'      => 'required|string|max:255',
            'estado'      => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $paciente->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => $request->password ? Hash::make($request->password) : $paciente->password,
            'cpf'         => $request->cpf,
            'cns'         => $request->cns,
            'rg'          => $request->rg,
            'cep'         => $request->cep,
            'nascimento'  => $request->nascimento,
            'nome_mae'    => $request->nome_mae,
            'foto'        => $request->foto ? $request->foto->store('public/fotos') : $paciente->foto,
        ]);

        $endereco = $paciente->endereco;

        if (!$endereco) {
            $endereco = new Endereco();
        }

        $endereco->fill([
            'cep'         => $request->cep,
            'endereco'    => $request->endereco,
            'numero'      => $request->numero,
            'complemento' => $request->complemento,
            'bairro'      => $request->bairro,
            'cidade'      => $request->cidade,
            'estado'      => $request->estado
        ]);

        $paciente->endereco()->save($endereco);

        return response()->json(new PacienteResource($paciente), 'Paciente updated.');
    }


    /**PacienteResourcehe specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return response()->json(['message' => 'Paciente deleted.']);
    }

    public function importarPlanilha(Request $request)
    {
        $file = $request->file('planilha');

        if (!$file) {
            return response()->json(['message' => 'Por favor, selecione um arquivo.'], 400);
        }

        if (!in_array($file->extension(), ['csv', 'xls', 'xlsx'])) {
            return response()->json(['message' => 'O arquivo deve ser uma planilha em formato CSV, XLS ou XLSX.'], 400);
        }

        $path = $file->store('planilhas');

        ImportarPlanilhaDePacientes::dispatch($path);

        return response()->json(['message' => 'A planilha está sendo processada.'], 200);
    }



    public function importarPacientes(Request $request)
    {
        return view('auth.importar-pacientes');
    }

}
