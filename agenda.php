<?php
// Configurar a conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "usuarios_db";

$con = new mysqli($host, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}

// Consulta SQL para obter todos os registros do dia atual em ordem crescente de horário
$sql = "SELECT id_usuario, DATE_FORMAT(data, '%H:%i') AS hora 
        FROM agendamento 
        WHERE DATE(data) = CURDATE()
        ORDER BY hora ASC"; // Ordenar por ordem crescente de hora

$result = $con->query($sql);

$registros = array();

while ($row = $result->fetch_assoc()) {
    $registros[] = array(
        'id_usuario' => $row['id_usuario'],
        'hora' => $row['hora']
    );
}

// Feche a conexão com o banco de dados
$con->close();

// Incluir o arquivo HTML que exibirá os resultados
include('agenda.html');
?>
