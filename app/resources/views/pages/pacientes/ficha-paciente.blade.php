@extends('layouts.main')
@section('content')

    <div class="ficha-paciente">

        <div class="bloco-paciente">

            <h2>Ficha do Paciente</h2>

            <form action="/atualizar-paciente" method="post" class="formulario">
                @csrf
                @method('PUT')

                <input type="hidden" value="{{$paciente -> id}}" name="id">

                <div class="grupo">

                    <label for="nome">Nome</label>
                    <input class="input" type="text" id="nome" value="{{$paciente -> nome}}" name="nome">
                    @error('nome') <span class="error">{{ $message }}</span> @enderror

                </div>

                <div class="grupo">

                    <label for="nascimento">Data de Nascimento</label>
                    <input class="input" type="date" id="nome" value="{{$paciente -> nascimento}}" name="nascimento">
                    @error('nascimento') <span class="error">{{ $message }}</span> @enderror

                </div>

                <div class="linha">

                    <div class="grupo">

                        <label for="codigo">Código</label>
                        <input class="input" disabled type="text" id="codigo" value="{{$paciente -> codigo}}" name="codigo">
                        @error('codigo') <span class="error">{{ $message }}</span> @enderror

                    </div>

                    <div class="grupo">

                        <label for="guia">Guia</label>
                        <input class="input" disabled type="text" id="guia" value="{{$paciente -> guia}}" name="guia">
                        @error('guia') <span class="error">{{ $message }}</span> @enderror

                    </div>

                </div>

                <div class="linha">

                    <div class="grupo">

                        <label for="entrada">Entrada</label>
                        <input class="input" type="date" id="entrada" value="{{$paciente -> entrada}}" name="entrada">
                        @error('entrada') <span class="error">{{ $message }}</span> @enderror

                    </div>

                    <div class="grupo">

                        <label for="saida">Saída</label>
                        <input class="input" type="date" id="saida" value="{{$paciente -> saida}}" name="saida">
                        @error('saida') <span class="error">{{ $message }}</span> @enderror

                    </div>

                </div>

                <button type="submit">Atualizar</button>

            </form>

            <div class="qnt-internacoes">
                <h3>Internações</h3>

                <div class="infos">

                    <span>Guia</span>
                    <span>Entrada</span>
                    <span>Saida</span>

                </div>
                    <div class="dados">
                        @foreach($internacoes as $internacao)

                        <span class="dado">{{ $internacao->guia }}</span>
                        <span class="dado">{{ Carbon\Carbon::parse( $internacao -> entrada)->format('d/m/Y') }}</span>
                        <span class="dado"> {{ Carbon\Carbon::parse( $internacao -> entrada)->format('d/m/Y') }}</span>

                        @endforeach

                    </div>
            </div>


        </div>

    </div>

@endsection
