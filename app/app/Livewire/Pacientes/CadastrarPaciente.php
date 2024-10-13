<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class CadastrarPaciente extends Component
{
    use WithFileUploads;

    public $nome;
    public $dataNascimento;
    public $guia;
    public $dataEntrada;
    public $dataSaida;
    public $status;
    public $planilha;
    public $dadosPlanilha = [];

    protected $rules = [
        'nome' => 'required|string|max:255',
        'dataNascimento' => 'required|date',
        'guia' => 'required|unique:pacientes,guia',
        'dataEntrada' => 'required|date',
        'dataSaida' => 'nullable|date',
        'status' => 'required|in:internado,alta',
    ];

    public function cadUsuario()
    {
        $this->validate();

        $codigo = Carbon::now()->format('Ymdis');

        $status = $this->dataSaida ? 'alta' : 'internado';

        Paciente::create([
            'nome' => $this->nome,
            'nascimento' => $this->dataNascimento,
            'codigo' => $codigo,
            'guia' => $this->guia,
            'entrada' => $this->dataEntrada,
            'saida' => $this->dataSaida,
            'status' => $status,
        ]);

        $this->reset(['nome', 'dataNascimento', 'guia', 'dataEntrada', 'dataSaida', 'status']);
        session()->flash('message', 'Paciente cadastrado com sucesso!');
    }

    public function previaPlanilha()
    {
        $this->validate(['planilha' => 'required|file|mimes:csv,txt']);

        if (($handle = fopen($this->planilha->getRealPath(), 'r')) !== false) {
            $this->dadosPlanilha = [];
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $row = array_combine($header, $row);
                    $row['codigo'] = $row['codigo'] ?? Carbon::now()->format('Ymdis');
                    $this->dadosPlanilha[] = $row;
                }
            }
            fclose($handle);
        }
    }

    public function salvarUsuarios()
    {
        foreach ($this->dadosPlanilha as $data) {
            Paciente::create([
                'nome' => $data['nome'],
                'nascimento' => Carbon::createFromFormat('d/m/Y', $data['nascimento'])->format('Y-m-d'),
                'codigo' => $data['codigo'],
                'guia' => $data['guia'],
                'entrada' => Carbon::createFromFormat('d/m/Y', $data['entrada'])->format('Y-m-d'),
                'saida' => $data['saida'] ? Carbon::createFromFormat('d/m/Y', $data['saida'])->format('Y-m-d') : null,
                'status' => $data['saida'] ? 'alta' : 'internado',
            ]);
        }

        $this->dadosPlanilha = [];
        session()->flash('message', 'Todos os pacientes foram salvos com sucesso!');
    }

    public function render()
    {
        return view('livewire.pacientes.cadastrar-paciente');
    }
}
