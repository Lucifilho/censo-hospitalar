@extends('layouts.main')
@section('content')

    <div class="novo-paciente">

        <div class="bloco-formulario-paciente">

            <div class="nav-form">

                <a class="item-menu" onclick="document.querySelector('.cad-unico').style.display = 'flex';  document.querySelector('.cad-planilha').style.display = 'none'; ">Cadastro individual</a>
                <a class="item-menu" onclick="document.querySelector('.cad-planilha').style.display = 'flex'; document.querySelector('.cad-unico').style.display = 'none';">Cadastro em massa</a>

            </div>

            <form action="/cadastrar" method="post" style="display: none" class="formulario cad-unico">
                @csrf

                <h2>Cadastrar Paciente</h2>

                <div class="linha">
                    <div class="grupo">
                        <label for="nome">Nome</label>
                        <input class="input" type="text" name="nome" id="nome">
                        @error('nome') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grupo">
                        <label for="nascimento">Data de Nascimento</label>
                        <input class="input" type="date" name="nascimento" id="nascimento">
                        @error('nascimento') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="linha">
                    <div class="grupo">
                        <label for="guia">Guia</label>
                        <input class="input" type="text" name="guia" id="guia">
                        @error('guia') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="linha">
                    <div class="grupo">
                        <label for="entrada">Entrada</label>
                        <input class="input" type="date" name="entrada" id="entrada">
                        @error('entrada') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grupo">
                        <label for="saida">Saída</label>
                        <input class="input" type="date" name="saida" id="saida">
                        @error('saida') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit">Registrar</button>
            </form>

            <div class="cad-planilha">

                <form action="/importar-planilha" method="post" enctype="multipart/form-data" class="formulario">
                    @csrf
                    <h2>Cadastre através de uma planilha</h2>

                    <div class="linha">
                        <div class="grupo">
                            <label for="planilha">Adicionar arquivo</label>
                            <input class="input" type="file" id="planilha" name="planilha">
                            @error('planilha') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit">Visualizar Dados</button>
                </form>

                @if($dadosPlanilha != null)

                    <form action="/salvar-planilha" method="post" enctype="multipart/form-data" class="formulario cad-planilha">
                        @csrf
                        <table>
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Código</th>
                                <th>Guia</th>
                                <th>Entrada</th>
                                <th>Saída</th>
                                <th>Alerta</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $combinacoes = [];
                                $guiaVerificada = [];
                                $codigoDivergente = false;
                                $guiasExistentes = [];
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


                                    foreach ($pacientes as $paciente){

                                        if($paciente -> guia ==  $linha['guia']){

                                            $alerta = 'Guia cadastrado';
                                        }
                                    }

                                    @endphp



                                <tr class="@if($alerta) incorreto @endif">
                                    <td>{{ $linha['nome'] }}</td>
                                    <td>{{ $linha['nascimento'] }}</td>
                                    <td>{{ $linha['codigo'] }}</td>
                                    <td>{{ $linha['guia'] }}</td>
                                    <td>{{ $linha['entrada'] }}</td>
                                    <td>{{ $linha['saida'] }}</td>
                                    <td>
                                        @if ($alerta)
                                            <span class="alerta">{{ $alerta }}</span>
                                        @endif
                                    </td>

                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][nome]" value="{{ $linha['nome'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][nascimento]" value="{{ $linha['nascimento'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][codigo]" value="{{ $linha['codigo'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][guia]" value="{{ $linha['guia'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][entrada]" value="{{ $linha['entrada'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][saida]" value="{{ $linha['saida'] }}">
                                    <input type="hidden" name="dadosPlanilha[{{ $index }}][alerta]" value="{{ $alerta }}">
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        <button type="submit" @if($codigoDivergente || $alerta) disabled @endif>
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
