<?php

namespace App\Livewire\Pacientes;
use App\Models\Paciente;

use Request;
use Livewire\Component;
use Auth;


class ListaPacientes extends Component
{

    public $search;


    public function render()
    {

        $search = $this->search;

        if ($search) {

            $pacientes = Paciente::where(function ($query) use ($search) {

                $query->Where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('guia', 'like', '%' . $this->search . '%');


            })
                ->orderBy('created_at')
                ->get();

        }else{

            $pacientes = Paciente::all();

        }


        return view('livewire.pacientes.lista-pacientes', compact('pacientes'));
    }
}
