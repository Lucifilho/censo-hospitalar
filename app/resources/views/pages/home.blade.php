@extends('layouts.main')
@section('content')

    <div class="home bloco-pagina">

        <div class="acesso-rapido bloco-conteudo">

            <a class="item" href="/pacientes">

                <i class="fa fa-users"></i>

                Lista de Pacientes

            </a>

            <a class="item" href="/novo-paciente">

                <i class="fa fa-user-plus"></i>

                Novo Paciente

            </a>

            <a class="item" href="/suporte">

                <i class="fa fa-question"></i>

                Suporte

            </a>

        </div>

    </div>

@endsection
