# Projeto de API para Gerenciamento de Produtos e Vendas

Este projeto é uma API desenvolvida em PHP para gerenciar produtos, tipos de produtos, impostos sobre produtos e vendas. A API oferece operações de CRUD (Create, Read, Update, Delete) para esses recursos e é configurada para permitir solicitações de um frontend hospedado em `http://localhost:3000`.

## Instalação

1. Clone o repositório:
    ```
    git clone <https://github.com/Gregory-Patrick/soft-expert-back-end?tab=readme-ov-file>
    ```
2. Navegue até o diretório do projeto:
    ```
    cd project-root
    ```
3. Instale as dependências do Composer:
    ```
    composer install
    ```

## Configuração

Certifique-se de configurar a conexão com o banco de dados no arquivo `BaseModel.php` dentro da pasta `Core`.

## Endpoints da API

### Produtos

- **GET /api/products**: Obtém todos os produtos.
- **POST /api/products**: Cria um novo produto.
- **GET /api/products/{id}**: Obtém um produto específico pelo ID.
- **PUT /api/products/{id}**: Atualiza um produto específico pelo ID.
- **DELETE /api/products/{id}**: Exclui um produto específico pelo ID.

### Tipos de Produtos

- **GET /api/types**: Obtém todos os tipos de produtos.
- **POST /api/types**: Cria um novo tipo de produto.

### Impostos sobre Produtos

- **POST /api/tax**: Cria um novo imposto sobre produto.
- **GET /api/tax/{id}**: Obtém um imposto sobre produto específico pelo ID.

### Vendas

- **GET /api/sale**: Obtém todas as vendas.
- **POST /api/sale**: Cria uma nova venda.

## CORS

A API está configurada para permitir requisições do frontend hospedado em `http://localhost:3000`. Os cabeçalhos CORS são definidos no arquivo `index.php`.

## Testes

Os testes unitários estão localizados na pasta `tests` e utilizam o PHPUnit. Para rodar os testes, execute:
vendor/bin/phpunit

## Contato

Para mais informações, entre em contato com [gregorypattrick@gmail.com](mailto:gregorypattrick@gmail.com).

---

Este é um breve resumo da estrutura e funcionamento da API. Para mais detalhes, consulte o código-fonte e os comentários em cada arquivo.