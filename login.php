<?php
include('db_connection.php');
include('login.html');

if (isset($_POST['email']) && isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['email'] = $usuario['email'];

            // Agora, vamos obter o valor do campo "tipo" do banco de dados.
            $tipo = $usuario['tipo'];

            // Redirecione com base no valor de "tipo".
            if ($tipo === 'cliente') {
                header("Location: dashboard.php");
            } elseif ($tipo === 'groomer') {
                header("Location: agenda.php");
            } else {
                // Lógica para outros tipos, se necessário.
            }
        } else {
            echo "<script>
                document.getElementById('erro-msg').innerHTML = 'E-mail ou senha incorretos!';
              </script>";
        }
    }
}
?>
