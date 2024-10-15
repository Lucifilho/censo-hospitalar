<div class="pacientes-pagina bloco-pagina fadeInUp">

    <div class="bloco-pacientes bloco-conteudo">

        <div class="cabecalho">
            <h1>Lista de Pacientes</h1>
            <span>Busque por seus pacientes</span>
        </div>

        <div class="bloco-pesquisa">

            <input wire:model.live="search" class="pesquisa-texto" type="search" placeholder="Encontre seu paciente, pelo nome código ou guia ">

            <a class="btn-cadastrar" href="/novo-paciente">Registrar Paciente</a>

        </div>

        <div class="lista-pacientes">

            <div class="cabecalho">
                <span>Código</span>
                <span>Nome</span>
                <span>Entrada</span>
                <span>Saída</span>
            </div>

            @foreach($pacientes as $paciente)
                <a href="/paciente-{{$paciente -> id}}/{{ str_replace("-", "", str_replace( ".", "",  $paciente -> codigo))}}" class="paciente">
                    <span>{{$paciente -> codigo}}</span>
                    <span>{{$paciente -> nome}}</span>
                    <span>{{ Carbon\Carbon::parse( $paciente -> entrada)->format('d/m/Y') }}</span>
                    <span>{{ Carbon\Carbon::parse( $paciente -> saida)->format('d/m/Y') }}</span>
                </a>
            @endforeach

        </div>

    </div>

</div>

