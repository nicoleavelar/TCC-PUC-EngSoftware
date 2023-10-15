<?php
if (!isset($_SESSION)) {
    session_start();
}

// Verifique se o usuário está autenticado
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Se o usuário não estiver autenticado, redirecione para a página de login
    header("Location: login.html");
    exit();
}

include('agendamento.html');

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

// Verifique se 'date' e 'hora' estão definidos no array $_POST
if (isset($_POST['date']) && isset($_POST['hora'])) {
    $date = $_POST['date'];
    $hora = $_POST['hora'];
} else {
    echo "A data ou a hora não foram enviadas no formulário.";
    exit();
}

// Combine a data e a hora em uma única string
$dataHora = $date . ' ' . $hora;

// Verifique se a data e hora já estão agendadas por qualquer usuário
$sqlVerificar = "SELECT * FROM agendamento WHERE data = '$dataHora'";
$resultVerificar = $conn->query($sqlVerificar);

if ($resultVerificar->num_rows > 0) {
    echo "A data e hora já estão agendadas por outro usuário.";
} else {
    // Obtenha o ID do usuário da sessão
    $idUsuario = $_SESSION['id'];

    // Insira o novo agendamento para o usuário atual
    $sqlInserir = "INSERT INTO agendamento (data, id_usuario) VALUES ('$dataHora', $idUsuario)";
    
    if ($conn->query($sqlInserir) === TRUE) {
        echo "Data e hora agendadas com sucesso!";
    } else {
        echo "Erro ao agendar a data e hora: " . $conn->error;
    }
}

$conn->close();
?>
