<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Carbon\Carbon;

class CadastrarPaciente extends Component
{

    public $nome;
    public $nascimento;
    public $guia;
    public $entrada;
    public $saida;
    public $status;

    protected $rules = [
        'nome' => 'required|string|max:255',
        'nascimento' => 'required|date',
        'guia' => 'required|unique:pacientes,guia',
        'entrada' => 'required|date',
        'saida' => 'nullable|date',
        'status' => 'required|in:internado,alta',
    ];

    public function submit()
    {
        $this->validate();

        $codigo = Carbon::now()->format('Ymdis');

        if($this -> saida != null){

            $status = 'alta';

        }else{

            $status = 'internado';
        }

        Paciente::create([
            'nome' => $this->nome,
            'nascimento' => $this->nascimento,
            'codigo' => $codigo,
            'guia' => $this->guia,
            'entrada' => $this->entrada,
            'saida' => $this->saida,
            'status' => $status,
        ]);

        $this->reset(['nome', 'nascimento', 'guia', 'entrada', 'saida', 'status']);

    }
    public function render()
    {
        return view('livewire.pacientes.cadastrar-paciente');
    }
}
