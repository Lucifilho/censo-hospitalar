<?php

namespace App\Livewire\Pacientes;
use App\Models\Paciente;

use Livewire\Component;
use Auth;

class ListaPacientes extends Component
{

    public $search;
    public $statusPaciente = '';

    protected $listeners = ['atualizarListaPacientes' => 'render'];
    public function render()
    {

        $search = $this->search;

        $statusPaciente = $this->statusPaciente;

        $user = Auth::user();

        if ($search) {

            $pacientes = Paciente::where(function ($query) use ($search) {

                $query->Where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('guia', 'like', '%' . $this->search . '%');


            })
                ->orderBy('created_at')
                ->paginate(5);
        }elseif ($statusPaciente){

            if ($statusPaciente === 'internado') {

                $pacientes = Paciente::where(function ($query) use ($search) {

                    $query->Where('status', 'internado');

                })
                    ->orderBy('nome')
                    ->orderBy('created_at')
                    ->paginate(5);
            } elseif($statusPaciente === 'alta'){

                $pacientes = Paciente::where(function ($query) use ($search) {

                    $query->Where('status', 'alta');

                })
                    ->orderBy('nome')
                    ->orderBy('created_at')
                    ->paginate(5);
            }

        }else{

            $pacientes = Paciente::paginate(5);

        }


        return view('livewire.pacientes.lista-pacientes', compact('pacientes'));
    }
}
