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

            try {

            $pacientes = Paciente::where(function ($query) use ($search) {

                $query->Where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('guia', 'like', '%' . $this->search . '%');


            })
                ->orderBy('created_at')
                ->paginate(5);
            }catch (\Exception $e) {

                dd($e);

            }

        }else{

            $pacientes = Paciente::paginate(5);

        }


        return view('livewire.pacientes.lista-pacientes', compact('pacientes'));
    }
}
