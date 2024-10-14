<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CadastrarPaciente extends Component
{
    use WithFileUploads;

    public $nome;
    public $nascimento;
    public $guia;
    public $entrada;
    public $saida;
    public $status;
    public $planilha;
    public $dadosPlanilha = [];
    public $previaDadosPlanilha = []; // Adicione esta linha

    protected $rules = [
        'nome' => 'required|string|max:255',
        'nascimento' => 'required|date',
        'guia' => 'required|unique:pacientes,guia',
        'entrada' => 'required|date',
        'saida' => 'nullable|date',
        'status' => 'required|in:internado,alta',
    ];

    public function cadUsuario()
    {

            // Verifica se já existe um paciente com o mesmo nome e nascimento, mas com código diferente
            $pacienteExistente = Paciente::where('nome', $this->nome)
                ->where('nascimento', $this->nascimento)
                ->where('guia', '!=', $this->guia)
                ->first();

            if ($pacienteExistente) {
                session()->flash('erro', 'Paciente já possui cadastro com este nome e data de nascimento. Verifique o código do paciente.');
                return;
            }

            $hoje =strtotime(Carbon::now()->format('Y-m-d'));

            if ($hoje < strtotime($this -> saida)){

                $status = 'internado';

            }else{

                $status = 'alta';

            }

            $codigo = Carbon::now()->format('Ymdis');

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
            session()->flash('msg', 'Paciente cadastrado com sucesso!');

    }

    public function previaPlanilha()
    {
        $this->validate(['planilha' => 'required|file|mimes:csv,txt']);

        if (($handle = fopen($this->planilha->getRealPath(), 'r')) !== false) {
            $this->dadosPlanilha = [];
            $this->previaDadosPlanilha = []; // Certifique-se de esvaziar aqui também.
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $row = array_combine($header, $row);
                    $row['codigo'] = $row['codigo'] ?? Carbon::now()->format('Ymdis');
                    $this->dadosPlanilha[] = $row;
                    $this->previaDadosPlanilha[] = $row; // Adicione esta linha.
                }
            }
            fclose($handle);
        }
    }

    public function salvarUsuarios()
    {
        foreach ($this->previaDadosPlanilha as $data) {
            try {
                $pacienteExistente = Paciente::where('nome', $data['nome'])
                    ->where('nascimento', Carbon::createFromFormat('d/m/Y', $data['nascimento'])->format('Y-m-d'))
                    ->where('guia', '!=', $data['guia'])
                    ->first();

                if ($pacienteExistente) {
                    Log::warning('Conflito encontrado para o paciente: ', $data);
                    session()->flash('message', 'Paciente com nome ' . $data['nome'] . ' e nascimento ' . $data['nascimento'] . ' já possui cadastro. Verifique o código.');
                    continue; // Continue ao próximo registro ao invés de retornar.
                }

                $hoje =strtotime(Carbon::now()->format('Y-m-d'));


                if ($hoje < strtotime($data['saida'])){

                    $status = 'internado';

                }else{

                    $status = 'alta';

                }

                Paciente::create([
                    'nome' => $data['nome'],
                    'nascimento' => Carbon::createFromFormat('d/m/Y', $data['nascimento'])->format('Y-m-d'),
                    'codigo' => $data['codigo'],
                    'guia' => $data['guia'],
                    'entrada' => Carbon::createFromFormat('d/m/Y', $data['entrada'])->format('Y-m-d'),
                    'saida' => $data['saida'] ? Carbon::createFromFormat('d/m/Y', $data['saida'])->format('Y-m-d') : null,
                    'status' => $status,
                ]);

                Log::info('Paciente salvo com sucesso: ', $data);
            } catch (\Exception $e) {
                Log::error('Erro ao salvar paciente: ' . $e->getMessage(), ['dados' => $data]);
                session()->flash('message', 'Erro ao salvar paciente: ' . $e->getMessage());
                continue; // Continue ao próximo registro ao invés de retornar.
            }
        }

        $this->previaDadosPlanilha = [];
        session()->flash('message', 'Todos os pacientes foram salvos com sucesso!');
    }

    public function render()
    {
        return view('livewire.pacientes.cadastrar-paciente');
    }
}
