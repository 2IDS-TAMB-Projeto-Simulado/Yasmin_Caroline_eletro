<?php
require_once "../model/model_usuario.php";

if (isset($_POST["email"]) && isset($_POST["senha"])) {
    $usuario = new Usuario();
    $resultado = $usuario->buscar_usuario($_POST["email"], $_POST["senha"]);

    session_start();

    if ($resultado) {
        // Guarda os dados do usuário na sessão
        $_SESSION['usuario'] = $resultado;

        // Redireciona para a tela inicial
        header("Location: ../view/inicial.php");
        exit();
    } else {
        // Caso o login falhe, salva mensagem de erro
        $_SESSION['erro_login'] = "Email ou senha inválidos!";

        // Registra tentativa de login com falha
        $usuario->cadastrar_log("Tentativa de login falhou para o email: " . $_POST["email"], date('Y-m-d'));

        // Redireciona de volta para o login
        header("Location: ../view/login.php");
        exit();
    }
}
?>
