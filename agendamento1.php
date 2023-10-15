<?php
include('agendamento.html');
include ('protect.php');

// Conecte-se ao banco de dados (substitua com suas configurações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber a data e a hora do formulário
$date = $_POST['date'];
$hora = $_POST['hora'];

// Combine a data e a hora em uma única string
$dataHora = $date . ' ' . $hora;


// Preparar a consulta SQL para inserir a data e a hora no banco de dados
$sql = "INSERT INTO agendamento (data ) VALUES ('$dataHora')";

if ($conn->query($sql) === TRUE) {
    echo "Data e hora agendadas com sucesso!";
} else {
    echo "Erro ao agendar a data e hora: " . $conn->error;
}

$conn->close();
?>
