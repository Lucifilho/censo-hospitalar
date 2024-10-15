@extends('layouts.main')
@section('content')

    <div class="novo-paciente">

        <div class="bloco-formulario-paciente">

            <div class="cad-planilha">

                <form action="/importar-planilha" method="post" enctype="multipart/form-data" class="formulario">
                    @csrf
                    <h2>Cadastre através de uma planilha</h2>

                    <div class="linha">
                        <div class="grupo">
                            <label for="planilha">Adicionar arquivo</label>
                            <input class="input" required type="file" id="planilha" name="planilha">
                            @error('planilha') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit">Visualizar Dados</button>
                </form>

                @if($dadosPlanilha != null)

                    <form action="/salvar-planilha" method="post" enctype="multipart/form-data" class="formulario visualizar-dados">
                        @csrf

                        <div class="info-planilha">

                            <span>Nome</span>
                            <span>Nascimento</span>
                            <span>Código</span>
                            <span>Guia</span>
                            <span>Entrada</span>
                            <span>Saída</span>
                            <span>Alerta</span>

                        </div>

                            @php
                                $combinacoes = [];
                                $guiaVerificada = [];
                                $codigoDivergente = false;
                                $guiasExistentes = [];
                                $temAlerta = false; // Variável para verificar se existe algum alerta
                            @endphp

                            @foreach ($dadosPlanilha as $index => $linha)

                                @php
                                    $combinacao = $linha['nome'] . '|' . $linha['nascimento'];
                                    $alerta = null;

                                    // Verifica o usuário e o nome
                                    if (isset($combinacoes[$combinacao])) {
                                        // Verifica se o código é igual
                                        if ($combinacoes[$combinacao] !== $linha['codigo']) {
                                            $alerta = 'Código divergente';
                                            $codigoDivergente = true;
                                        }
                                    } else {
                                        $combinacoes[$combinacao] = $linha['codigo'];
                                    }

                                    // Verifica a guia
                                    if (!$codigoDivergente) {
                                        // Verifica se a guia já foi verificada
                                        if (isset($guiaVerificada[$linha['guia']])) {
                                            $alerta = 'Guia repetida';
                                        } elseif (in_array($linha['guia'], $guiasExistentes)) {
                                            $alerta = 'Guia já existe no banco';
                                        } else {
                                            $guiaVerificada[$linha['guia']] = true;
                                        }
                                    }

                                    // Verifica se a data de saída é maior que a data de entrada
                                    $dataEntrada = \Carbon\Carbon::createFromFormat('d/m/Y', $linha['entrada']);
                                    $dataSaida = \Carbon\Carbon::createFromFormat('d/m/Y', $linha['saida']);
                                    if ($dataSaida->lt($dataEntrada)) {
                                        $alerta = 'Data incompatível: saída antes da entrada';
                                    }

                                    // Verifica se a data de entrada é anterior à data de nascimento
                                    $dataNascimento = \Carbon\Carbon::createFromFormat('d/m/Y', $linha['nascimento']);
                                    if ($dataEntrada->lt($dataNascimento)) {
                                        $alerta = 'Data incompatível: entrada antes do nascimento';
                                    }

                                    // Verifica se a guia já está cadastrada no banco
                                    foreach ($pacientes as $paciente) {
                                        if ($paciente->guia == $linha['guia']) {
                                            $alerta = 'Guia cadastrado';
                                        }

                                        // Verificação de conflitos de internação no banco de dados
                                        if ($paciente->codigo == $linha['codigo']) {
                                            $pacienteEntrada = \Carbon\Carbon::parse($paciente->entrada);
                                            $pacienteSaida = $paciente->saida ? \Carbon\Carbon::parse($paciente->saida) : null;

                                            // Se a internação atual não tem alta (paciente ainda internado)
                                            if (!$pacienteSaida) {
                                                // Bloqueia novas internações com data de entrada após a internação ainda ativa
                                                if ($dataEntrada->gt($pacienteEntrada)) {
                                                    $alerta = 'Conflito de internação: paciente ainda internado';
                                                }
                                            } else {
                                                // Verifica se os períodos de internação conflitam
                                                if (
                                                    ($dataEntrada->lt($pacienteSaida) && $dataEntrada->gte($pacienteEntrada)) ||
                                                    ($dataSaida->lte($pacienteSaida) && $dataSaida->gt($pacienteEntrada)) ||
                                                    ($dataEntrada->lt($pacienteEntrada) && $dataSaida->gt($pacienteSaida))
                                                ) {
                                                    $alerta = 'Conflito de internação: período sobreposto';
                                                }
                                            }
                                        }
                                    }

                                    // Verificação de conflitos de internação entre as linhas da própria planilha
                                    foreach ($dadosPlanilha as $outroIndex => $outraLinha) {
                                        if ($index !== $outroIndex && $outraLinha['codigo'] == $linha['codigo']) {
                                            $outraDataEntrada = \Carbon\Carbon::createFromFormat('d/m/Y', $outraLinha['entrada']);
                                            $outraDataSaida = \Carbon\Carbon::createFromFormat('d/m/Y', $outraLinha['saida']);

                                            // Verifica se os períodos de internação conflitam na planilha
                                            if (
                                                ($dataEntrada->lt($outraDataSaida) && $dataEntrada->gte($outraDataEntrada)) ||
                                                ($dataSaida->lte($outraDataSaida) && $dataSaida->gt($outraDataEntrada)) ||
                                                ($dataEntrada->lt($outraDataEntrada) && $dataSaida->gt($outraDataSaida))
                                            ) {
                                                $alerta = 'Conflito de internação na planilha: período sobreposto';
                                            }
                                        }
                                    }

                                    // Marca que existe um alerta, se houver
                                    if ($alerta) {
                                        $temAlerta = true;
                                    }
                                @endphp

                                <div class="dados @if($alerta) incorreto @endif">
                                    <span>{{ $linha['nome'] }}</span>
                                    <span>{{ $linha['nascimento'] }}</span>
                                    <span>{{ $linha['codigo'] }}</span>
                                    <span>{{ $linha['guia'] }}</span>
                                    <span>{{ $linha['entrada'] }}</span>
                                    <span>{{ $linha['saida'] }}</span>
                                    <span>
                                        @if ($alerta)
                                            <span class="alerta">{{ $alerta }}</span>
                                        @endif
                                    </span>

                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][nome]" value="{{ $linha['nome'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][nascimento]" value="{{ $linha['nascimento'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][codigo]" value="{{ $linha['codigo'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][guia]" value="{{ $linha['guia'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][entrada]" value="{{ $linha['entrada'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][saida]" value="{{ $linha['saida'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][alerta]" value="{{ $alerta }}">
                                </div>

                            @endforeach

                        <button type="submit" @if($temAlerta || $codigoDivergente) disabled @endif>
                            Confirmar Importação
                        </button>

                    </form>

                @endif

            </div>

            @if (session()->has('message'))

                <p class="msg">{{session('message')}}</p>

            @endif

        </div>

    </div>

@endsection
