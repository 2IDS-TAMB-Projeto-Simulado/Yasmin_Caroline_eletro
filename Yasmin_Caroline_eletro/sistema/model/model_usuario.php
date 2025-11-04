<?php
require_once "../config/db.php";
require_once "model_logs.php";

class Usuario {

    // Buscar usuário no banco para autenticação
    public function buscar_usuario($email, $senha) {
        $conn = Database::getConnection();
        $select = $conn->prepare("SELECT * FROM USUARIO WHERE USU_EMAIL = ? AND USU_SENHA = ?");
        $select->bind_param("ss", $email, $senha);
        $select->execute();
        $resultado = $select->get_result();
        $usuario = $resultado->fetch_assoc();
        $select->close();

        // Registrar log da tentativa de login
        $logs = new Logs();
        if ($usuario) {
            $logs->cadastrar_logs("LOGIN realizado com sucesso pelo usuário: " . $email);
        } else {
            $logs->cadastrar_logs("TENTATIVA DE LOGIN falhou para o email: " . $email);
        }

        return $usuario;
    }

    // Cadastrar novo usuário (opcional, útil para ampliar o sistema)
    public function cadastrar_usuario($nome, $email, $senha) {
        $conn = Database::getConnection();
        $insert = $conn->prepare("INSERT INTO USUARIO (USU_NOME, USU_EMAIL, USU_SENHA) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $nome, $email, $senha);
        $success = $insert->execute();

        if ($success) {
            $logs = new Logs();
            $logs->cadastrar_logs("NOVO USUÁRIO cadastrado: $email");
        }

        $insert->close();
        return $success;
    }

    // Listar usuários cadastrados (útil para administração)
    public function listar_usuarios() {
        $conn = Database::getConnection();
        $sql = "SELECT USU_ID, USU_NOME, USU_EMAIL FROM USUARIO ORDER BY USU_NOME";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
