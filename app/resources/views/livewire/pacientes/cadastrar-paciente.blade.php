<div>

    <div class="formulario-paciente" style="display: none;" wire:ignore.self>
        <h2>Cadastrar Paciente</h2>

        <form wire:submit.prevent="submit">

            <div class="linha">

                <div class="grupo">

                    <label for="nome">Nome</label>
                    <input type="text" id="nome" wire:model="nome">
                    @error('nome') <span class="error">{{ $message }}</span> @enderror

                </div>

                <div class="grupo">

                    <label for="nascimento">Data de Nascimento</label>
                    <input type="date" id="nome" wire:model="nascimento">
                    @error('nascimento') <span class="error">{{ $message }}</span> @enderror

                </div>

            </div>

            <div class="linha">

                <div class="grupo">

                    <label for="guia">Guia</label>
                    <input type="text" id="guia" wire:model="guia">
                    @error('guia') <span class="error">{{ $message }}</span> @enderror

                </div>

            </div>

            <div class="linha">

                <div class="grupo">

                    <label for="entrada">Entrada</label>
                    <input type="date" id="entrada" wire:model="entrada">
                    @error('entrada') <span class="error">{{ $message }}</span> @enderror

                </div>

                <div class="grupo">

                    <label for="saida">Saída</label>
                    <input type="date" id="saida" wire:model="saida">
                    @error('saida') <span class="error">{{ $message }}</span> @enderror

                </div>

            </div>

            <button type="submit">Registrar</button>

        </form>

    </div>

</div>
