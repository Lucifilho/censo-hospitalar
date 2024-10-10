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
                    ->orWhere('data_inicio', 'like', '%' . $this->search . '%');

            })
                ->orderBy('created_at')
                ->paginate(10);

        }elseif ($statusPaciente){

            if ($statusPaciente === 'Todos') {

                $pacientes = Paciente::where(function ($query) use ($search) {

                    $query->Where('categoria', 'Todos');

                })
                    ->orderBy('nome')
                    ->orderBy('created_at')
                    ->paginate(10);

            } elseif($statusPaciente === 'LGBTQIA+'){

                $pacientes = Paciente::where(function ($query) use ($search) {

                    $query->Where('categoria', 'LGBTQIA+');

                })
                    ->orderBy('nome')
                    ->orderBy('created_at')
                    ->paginate(10);
            }elseif($statusPaciente === 'Heterossexual'){

                $pacientes = Paciente::where(function ($query) use ($search) {

                    $query->Where('categoria',  'Heterossexual');

                })
                    ->orderBy('nome')
                    ->orderBy('created_at')
                    ->paginate(10);
            }

        }else{

            $pacientes = Paciente::paginate(10);

        }


        return view('livewire.pacientes.lista-pacientes', compact('pacientes'));
    }
}
