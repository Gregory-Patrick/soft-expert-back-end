<?php
// src/Controllers/ProductController.php

namespace App\Controllers;

use App\Models\Product;

class ProductController {

    public function getAll() {
        // Recupera todos os produtos do banco de dados
        $products = Product::all($a = null);
        
        // Define o cabeçalho de resposta como JSON
        header('Content-Type: application/json');
        
        // Retorna os produtos em formato JSON
        echo json_encode($products);
    }

    public function create() {


        http_response_code(201);
        echo json_encode($product);


        // Obtém o corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Verifica se os dados foram fornecidos
        if (!isset($data['name']) || !isset($data['code']) || !isset($data['price'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        // Cria um novo produto e salva no banco de dados
        $product = new Product();
        $product->name = $data['name'];
        $product->code = $data['code'];
        $product->price = $data['price'];
        $product->save();

        // Define o cabeçalho de resposta como JSON
        header('Content-Type: application/json');
        
        // Retorna o produto criado com sucesso
        http_response_code(201);
        echo json_encode($product);
    }

    public function getById() {
        // Obtém o ID do produto da URI
        $id = $_GET['id'];

        // Recupera o produto do banco de dados pelo ID
        $product = Product::find($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
            return;
        }

        // Define o cabeçalho de resposta como JSON
        header('Content-Type: application/json');
        
        // Retorna o produto encontrado
        echo json_encode($product);
    }

    public function update() {
        // Obtém o ID do produto da URI
        $id = $_GET['id'];
        
        // Obtém o corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Verifica se os dados foram fornecidos
        if (!isset($data['name']) || !isset($data['code']) || !isset($data['price'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        // Recupera o produto do banco de dados pelo ID
        $product = Product::find($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
            return;
        }

        // Atualiza os dados do produto
        $product->name = $data['name'];
        $product->code = $data['code'];
        $product->price = $data['price'];
        $product->save();

        // Define o cabeçalho de resposta como JSON
        header('Content-Type: application/json');
        
        // Retorna o produto atualizado
        echo json_encode($product);
    }

    public function delete() {
        // Obtém o ID do produto da URI
        $id = $_GET['id'];

        // Recupera o produto do banco de dados pelo ID
        $product = Product::find($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
            return;
        }

        // Deleta o produto
        $product->delete();

        // Define o cabeçalho de resposta como JSON
        header('Content-Type: application/json');
        
        // Retorna uma mensagem de sucesso
        http_response_code(204);
        echo json_encode(['message' => 'Produto deletado com sucesso']);
    }
}
