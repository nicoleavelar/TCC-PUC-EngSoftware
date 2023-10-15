<?php
session_start();

include('cadastro.html');

// Conexão com o banco de dados (substitua as informações de acordo com seu banco)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Coleta os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se os campos obrigatórios não estão vazios
if (empty($nome) || empty($email) || empty($senha)) {
    echo "Por favor, preencha todos os campos obrigatórios.";
} else {
    // Verifica se o email já existe no banco de dados
    $verifica_email = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($verifica_email);

     if ($result->num_rows > 0) {
        echo "<script>
            document.getElementById('erro-msg').innerHTML = 'Este e-mail já está sendo utilizado! Por favor, utilize outro.';
          </script>";
          
    } 
    
    
    else {
        // Determine o tipo com base no link acessado
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];

            if (strpos($referer, 'cadastro.php?tipo=cliente') !== false) {
                $_SESSION['tipo'] = 'cliente';
            } elseif (strpos($referer, 'cadastro.php?tipo=groomer') !== false) {
                $_SESSION['tipo'] = 'groomer';
            } else {
                $_SESSION['tipo'] = 'indefinido';
            }
        } else {
            $_SESSION['tipo'] = 'indefinido';
        }

        $senha = ($senha); // Hash da senha para segurança

        // Insere os dados no banco de dados com o tipo apropriado
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '" . $_SESSION['tipo'] . "')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
            document.getElementById('erro-msg').innerHTML = 'Cadastro realizado com sucesso!';
          </script>";
        } else {
            echo "Erro ao cadastrar o usuário: " . $conn->error;
        }
    }
}

$conn->close();
?>
