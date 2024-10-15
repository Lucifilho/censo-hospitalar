<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pages.pacientes.lista-pacientes');
    }

    public function novoPaciente()
    {

        $dadosPlanilha = []; // Inicializa como um array vazio
        return view('pages.pacientes.novo-paciente', compact('dadosPlanilha'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function importarPlanilha(Request $request)
    {
        $request->validate([
            'planilha' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        $path = $request->file('planilha')->getRealPath();
        $dadosPlanilha = [];
        $pacientes = Paciente::all();

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Ignora cabeçalho

            $nome = $row[0] ?? null;
            $nascimento = $row[1] ?? null;
            $codigo = $row[2] ?? null;
            $guia = $row[3] ?? null;
            $entrada = $row[4] ?? null;
            $saida = $row[5] ?? null;
            $alerta = null;

            $dadosPlanilha[] = [
                'nome' => $nome,
                'nascimento' => $nascimento,
                'codigo' => $codigo,
                'guia' => $guia,
                'entrada' => $entrada,
                'saida' => $saida,
                'alerta' => $alerta,
            ];
        }

        return view('pages.pacientes.novo-paciente', compact('dadosPlanilha', 'pacientes'));
    }

    public function salvarDadosPlanilha(Request $request)
    {
        foreach ($request->dadosPlanilha as $linha) {
            if (!$linha['alerta']) {

                try {

                    Paciente::create([
                        'nome' => $linha['nome'],
                        'nascimento' => Carbon::createFromFormat('d/m/Y', $linha['nascimento'])->format('Y-m-d'),
                        'codigo' => $linha['codigo'],
                        'guia' => $linha['guia'],
                        'entrada' => Carbon::createFromFormat('d/m/Y', $linha['entrada'])->format('Y-m-d'),
                        'saida' => Carbon::createFromFormat('d/m/Y', $linha['saida'])->format('Y-m-d'),
                    ]);
                }catch (\Exception $e) {

                    if ($e -> getCode() == '23000'){

                        foreach ($e as $mensagem){

                            return redirect('/novo-paciente')-> with('message', $mensagem[2] );

                        }


                    }else{

                        return redirect('/novo-paciente')-> with('message', 'Erro ao salvar paciente:'. $e->getMessage());

                    }
                }
            }
        }

        return redirect('/pacientes')->with('message', 'Pacientes importados com sucesso!');

    }
    /**
     * Store a newly created resource in storage.
     */
    public function cadastrarPaciente(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'nascimento' => 'required|date',
            'guia' => 'required|unique:pacientes,guia',
            'entrada' => 'required|date',
            'saida' => 'nullable|date',
        ]);

        // Verifica se já existe um paciente com o mesmo nome e data de nascimento, mas com um código diferente.
        $pacienteExistente = Paciente::where('nome', $request->nome)
            ->where('nascimento', $request->nascimento)
            ->where('guia', '!=', $request->guia)
            ->first();

        if ($pacienteExistente) {
            return redirect()->back()->with('message', 'Paciente já possui cadastro com este nome e data de nascimento. Verifique o código do paciente.');
        }

        $codigo = Carbon::now()->format('Ymdis');

        Paciente::create([
            'nome' => $request->nome,
            'nascimento' => $request->nascimento,
            'codigo' => $codigo,
            'guia' => $request->guia,
            'entrada' => $request->entrada,
            'saida' => $request->saida,
        ]);

        return redirect()->back()->with('message', 'Paciente cadastrado com sucesso!');
    }
    /**
     * Store patients from an uploaded spreadsheet.
     */


    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
