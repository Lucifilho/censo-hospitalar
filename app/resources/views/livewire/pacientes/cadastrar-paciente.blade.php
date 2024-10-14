<div>

    <div class="bloco-formulario-paciente" wire:ignore.self style="display: none">

        <div class="nav-form">

            <a class="item-menu" onclick="document.querySelector('.cad-unico').style.display = 'flex';  document.querySelector('.cad-planilha').style.display = 'none'; ">Cadastro individual</a>
            <a class="item-menu" onclick="document.querySelector('.cad-planilha').style.display = 'flex'; document.querySelector('.cad-unico').style.display = 'none';">Cadastro em massa</a>

        </div>

        <!-- Formulário para cadastrar um paciente único -->
        <form wire:submit.prevent="cadUsuario" style="display:none;" class="formulario cad-unico">
            <h2>Cadastrar Paciente</h2>
            <div class="linha">
                <div class="grupo">
                    <label for="nome">Nome</label>
                    <input class="input" type="text" id="nome" wire:model="nome">
                    @error('nome') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="grupo">
                    <label for="nascimento">Data de Nascimento</label>
                    <input class="input" type="date" id="nascimento" wire:model="nascimento">
                    @error('nascimento') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="linha">
                <div class="grupo">
                    <label for="guia">Guia</label>
                    <input class="input" type="text" id="guia" wire:model="guia">
                    @error('guia') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="linha">
                <div class="grupo">
                    <label for="entrada">Entrada</label>
                    <input class="input" type="date" id="entrada" wire:model="entrada">
                    @error('entrada') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="grupo">
                    <label for="saida">Saída</label>
                    <input class="input" type="date" id="saida" wire:model="saida">
                    @error('saida') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit">Registrar</button>
            <a class="btn-cancelar" onclick="document.querySelector('.bloco-formulario-paciente').style.display = 'none';">Cancelar</a>
        </form>

        <!-- Formulário para importar pacientes em massa via planilha -->
        <form wire:submit.prevent="previaPlanilha" class="formulario cad-planilha">
            <h2>Cadastre através de uma planilha</h2>
            <div class="linha">
                <div class="grupo">
                    <label for="planilha">Adicionar arquivo</label>
                    <input class="input" type="file" id="planilha" wire:model="planilha">
                    @error('planilha') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit">Visualizar Dados</button>
            <a class="btn-cancelar" onclick="document.querySelector('.bloco-formulario-paciente').style.display = 'none';">Cancelar</a>
        </form>

        @if (!empty($dadosPlanilha))
            <div class="visualizar-dados">
                <h3>Pré-visualização dos Dados</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>Código</th>
                        <th>Guia</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($dadosPlanilha as $colunaPlanilha)
                        <tr>
                            <td>{{ $colunaPlanilha['nome'] }}</td>
                            <td>{{ $colunaPlanilha['nascimento'] }}</td>
                            <td>{{ $colunaPlanilha['codigo'] }}</td>
                            <td>{{ $colunaPlanilha['guia'] }}</td>
                            <td>{{ $colunaPlanilha['entrada'] }}</td>
                            <td>{{ $colunaPlanilha['saida'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <button class="btn-cadastrar" wire:click="salvarUsuarios">Salvar Todos</button>

            </div>
        @endif

    </div>
</div>
