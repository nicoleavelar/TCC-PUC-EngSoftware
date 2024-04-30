<?php

// Inicie a sessão se ela ainda não estiver iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verifique se o usuário está autenticado
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Se o usuário não estiver autenticado, redirecione para a página de login
    header("Location: login.php");
    exit();
}
// Habilitar mensagens de erro do PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Passo 1: Configurar a conexão com o banco de dados
$conexao = mysqli_connect('localhost', 'root', '', 'usuarios_db');

if (!$conexao) {
    die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
}


// Consulta para obter o id_groomer do usuário logado
$consulta_id_groomer = "SELECT id_groomer FROM estabelecimento WHERE id = {$_SESSION['id']}";
$resultado_id_groomer = mysqli_query($conexao, $consulta_id_groomer);

if ($resultado_id_groomer && mysqli_num_rows($resultado_id_groomer) > 0) {
    $dados_groomer = mysqli_fetch_assoc($resultado_id_groomer);
    $_SESSION['id_groomer'] = $dados_groomer['id_groomer'];
} else {
    // Caso não encontre o id_groomer, faça algo para lidar com essa situação
}

// Passo 2: Executar uma consulta SQL para buscar todos os registros
$consulta = "SELECT id, nome, descricao, img, id_groomer FROM estabelecimento";
$resultado = mysqli_query($conexao, $consulta);

if (!$resultado) {
    die('Erro na consulta SQL: ' . mysqli_error($conexao));
}

// Passo 3: Obter todos os dados dos resultados da consulta
$dados = array();

if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        // Codificar a imagem em base64
        $linha['imagem_base64'] = base64_encode($linha['img']);
        $dados[] = $linha;
    }
}

// Passo 4: Executar uma consulta SQL para buscar o email do usuário
$consulta_usuario = "SELECT email FROM usuarios"; // Substitua '1' pelo ID do usuário desejado
$resultado_usuario = mysqli_query($conexao, $consulta_usuario);

if ($resultado_usuario) {
    $usuario = mysqli_fetch_assoc($resultado_usuario);
    $email = $usuario['email'];
} else {
    $email = 'Não encontrado'; // Trate o caso de erro
}

// Passo 4: Incluir o arquivo de template HTML

include 'dashboard.html';

// Passo 5: Fechar a conexão com o banco de dados
mysqli_close($conexao);
?>
