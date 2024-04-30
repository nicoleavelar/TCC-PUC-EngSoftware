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
    echo '<div style="margin-left:500px;color:#f4ede8;font-family: Roboto Slab;font-size: 18px; font-weight: 400;">Selecione data e hora.</div>';
    exit();
}

// Combine a data e a hora em uma única string
$dataHora = $date . ' ' . $hora;

// Verifique se a data e hora já estão agendadas por qualquer usuário
$sqlVerificar = "SELECT * FROM agendamento WHERE data = '$dataHora'";
$resultVerificar = $conn->query($sqlVerificar);

if ($resultVerificar->num_rows > 0) {
    echo '<div style="margin-left:400px;color:#f4ede8;font-family: Roboto Slab;font-size: 18px; font-weight: 400;">A data e hora já estão agendadas por outro usuário.</div>';
} else {
    // Obtenha o ID do usuário da sessão
    $idUsuario = $_SESSION['id'];
    
    // Obtenha o id_groomer do estabelecimento do usuário
    $sqlGroomer = "SELECT id_groomer FROM estabelecimento WHERE id = $idUsuario";
    $resultGroomer = $conn->query($sqlGroomer);
    
    if ($resultGroomer->num_rows > 0) {
        $row = $resultGroomer->fetch_assoc();
        $idGroomer = $row['id_groomer'];
        
        // Insira o novo agendamento para o usuário atual com o id_estabelecimento correto
        $sqlInserir = "INSERT INTO agendamento (data, id_usuario, id_estabelecimento) VALUES ('$dataHora', $idUsuario, $idGroomer)";
        
        if ($conn->query($sqlInserir) === TRUE) {
            echo '<div style="margin-left:450px;color:#f4ede8;font-family: Roboto Slab;font-size: 18px; font-weight: 400;">Data e hora agendadas com sucesso!</div>';
        } else {
            echo "Erro ao agendar a data e hora: " . $conn->error;
        }
    } else {
        echo "Erro ao obter o id_groomer do estabelecimento.";
    }
}

$conn->close();
?>
