# Censo Hospitalar

Este repositório contém a aplicação **Censo Hospitalar**, desenvolvida para gerenciar informações de pacientes e internações. A aplicação está hospedada em [clinica.lucielfilho.com.br](https://clinica.lucielfilho.com.br/).

## Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Pré-requisitos](#pré-requisitos)
- [Instalação e Configuração](#instalação-e-configuração)
  
## Sobre o Projeto

O **Censo Hospitalar** é uma aplicação web desenvolvida em Laravel para facilitar o gerenciamento de pacientes e suas internações. Ele oferece um painel administrativo para o cadastro de pacientes e acompanhamento das internações.

## Tecnologias Utilizadas

- PHP 8.x
- Laravel 11.x
- MySQL
- Composer
- JavaScript
- HTML/CSS

## Pré-requisitos

Antes de começar, você precisará ter as seguintes ferramentas instaladas em sua máquina:

- [PHP 8.x](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/downloads/)
- [Git](https://git-scm.com/)

## Instalação e Configuração/Rodar a Aplicação

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/seu-usuario/censo-hospitalar.git
   cd app

2. **Instale as dependências do Composer:**

   ```bash
    composer install

3. **Edite o arquivo .env para configurar o banco de dados:**

    ```makefile
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=seu_banco_de_dados
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha

4. **Gere a chave da aplicação:**

    ```bash
    php artisan key:generate
   
5. **Inicie o servidor local:**
   
    ```bash
   php artisan serve

