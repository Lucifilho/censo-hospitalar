<div class="pacientes-pagina fadeInUp">

    {{-- Formulário para cadastrar novo paciente --}}
    <div wire:ignore>
        @livewire('pacientes.cadastrar-paciente')
    </div>

    <div class="bloco-pacientes">
        <div class="cabecalho">
            <h1>Lista de Pacientes</h1>
            <span>Busque por seus pacientes</span>
        </div>

        <div class="bloco-pesquisa">
            <input wire:model.live="search" class="pesquisa-texto" type="search" placeholder="Encontre seu paciente, pelo nome código ou guia ">
            <select wire:model.live="statusPaciente" class="statusPaciente">
                <option value="">Ver Tudo</option>
                <option value="internado">Internado</option>
                <option value="alta">Alta</option>
            </select>

            <a class="btn-cadastrar" href="#" onclick="document.querySelector('.formulario-paciente').style.display = 'block';">Registrar Paciente</a>
        </div>

        <div class="lista-pacientes">
            @foreach($pacientes as $paciente)
                <a href="/paciente-{{$paciente -> id}}/{{ str_replace("-", "", str_replace( ".", "",  $paciente -> codigo))}}" class="paciente">
                    <span>{{$paciente -> codigo}}</span>
                    <span>{{$paciente -> nome}}</span>
                    <span>{{$paciente -> status }}</span>
                    <span>{{$paciente -> entrada }}</span>
                </a>
            @endforeach
        </div>

        <div class="links">
            {{$pacientes -> links()}}
        </div>
    </div>

</div>

<script>
    window.addEventListener('pacienteCadastrado', () => {
        document.querySelector('.formulario-paciente').style.display = 'none';
        Livewire.emit('atualizarListaPacientes');
    });
</script>
