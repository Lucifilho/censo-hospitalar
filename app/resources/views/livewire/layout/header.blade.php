<div>

    <header>

        <nav class="navbar navbar-expand-lg">

            <div class="container-fluid">

                <a class="navbar-brand" href="/">
                    <img class="logo" src="/imagens/logo.svg">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">

                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>

                        @auth

                            <li class="nav-item dropdown">

                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pacientes
                                </a>

                                <ul class="dropdown-menu">

                                    <li><a class="dropdown-item" href="/lista-pacientes">Meus Pacientes</a></li>
                                    <li><a class="dropdown-item" href="/novo-paciente">Novo Paciente</a></li>

                                </ul>

                            </li>

                            <li class="nav-item dropdown">

                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Olá, {{$user -> name }}
                                </a>

                                <ul class="dropdown-menu">

                                    <li><a class="dropdown-item" href="/user/profile">Minha Conta</a></li>

                                    <li>
                                        <form action="/logout" method="POST">
                                            @csrf
                                            <a class="dropdown-item"
                                               href="/logout"
                                               onclick="event.preventDefault();
                            this.closest('form').submit();"
                                            >Sair</a>
                                        </form>
                                    </li>
                                </ul>

                            </li>

                        @elseguest

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/suporte">Suporte</a>
                            </li>

                        @endauth


                    </ul>

                </div>

            </div>

        </nav>

    </header>

</div>
