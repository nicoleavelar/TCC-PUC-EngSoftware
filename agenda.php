<?php
session_start(); // Inicia a sessão

// Configurar a conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "usuarios_db";

$con = new mysqli($host, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}

// Verifica se o campo 'id' e 'email' estão presentes na sessão antes de usá-los
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    $id_do_usuario_logado = $_SESSION['id'];
    $email = $_SESSION['email'];
} else {
    // Define valores padrão caso o id e o email não estejam disponíveis na sessão
    $id_do_usuario_logado = 0;
    $email = '';
}

//Consulta SQL para obter todos os registros do dia atual no horário da manhã
$sql_manha = "SELECT a.id_usuario, q.nome, DATE_FORMAT(a.data, '%H:%i') AS hora 
FROM agendamento AS a 
JOIN estabelecimento AS e ON a.id_estabelecimento = e.id_groomer 
JOIN usuarios AS u ON e.id_groomer = u.id 
JOIN usuarios AS q ON a.id_usuario = q.id  -- Modificação aqui para juntar com a tabela 'usuarios' usando o id_usuario
JOIN usuarios AS l ON u.id = l.id 
WHERE DATE(a.data) = CURDATE() AND l.id = 6 AND TIME(a.data) BETWEEN '08:00:00' AND '12:00:00' 
ORDER BY hora ASC";


$result_manha = $con->query($sql_manha);

$registros_manha = array();

while ($row = $result_manha->fetch_assoc()) {
    $registros_manha[] = array(
        'id_usuario' => $row['id_usuario'],
        'hora' => $row['hora'],
        'nome' => $row['nome']  // Adicione o campo 'nome' à matriz
    );
}

// Consulta SQL para obter todos os registros do dia atual no horário da tarde
$sql_tarde = "SELECT a.id_usuario, q.nome, DATE_FORMAT(a.data, '%H:%i') AS hora 
FROM agendamento AS a 
JOIN estabelecimento AS e ON a.id_estabelecimento = e.id_groomer 
JOIN usuarios AS u ON e.id_groomer = u.id 
JOIN usuarios AS q ON a.id_usuario = q.id  -- Modificação aqui para juntar com a tabela 'usuarios' usando o id_usuario
JOIN usuarios AS l ON u.id = l.id 
AND TIME(a.data) BETWEEN '13:00:00' AND '17:00:00'
ORDER BY hora ASC";



$result_tarde = $con->query($sql_tarde);

$registros_tarde = array();

while ($row = $result_tarde->fetch_assoc()) {
    $registros_tarde[] = array(
        'id_usuario' => $row['id_usuario'],
        'hora' => $row['hora'],
        'nome' => $row['nome']  // Adicione o campo 'nome' à matriz
    );
}

// Feche a conexão com o banco de dados
$con->close();

// Incluir o arquivo HTML que exibirá os resultados
include('agenda.html');
