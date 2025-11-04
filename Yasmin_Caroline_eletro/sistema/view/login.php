<?php
// Encerra qualquer sessão ativa antes de exibir o login
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">       
    <title>Login - Loja de Eletrodomésticos SAEP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            width: 350px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .erro {
            color: red;
            margin-top: 10px;
        }
        small {
            color: #555;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login - Loja de Eletrodomésticos</h1>
        <form action="./../controller/controller_usuario.php" method="POST">
            <label for="email"><strong>Email:</strong></label><br>
            <input type="email" id="email" name="email" placeholder="Digite seu email..." required><br><br>

            <label for="senha"><strong>Senha:</strong></label><br>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha..." required><br>

            <?php
                session_start();
                if (isset($_SESSION['erro_login'])) {
                    echo "<div class='erro'>" . $_SESSION['erro_login'] . "</div>";
                    unset($_SESSION['erro_login']);
                }
            ?>

            <br>
            <input type="submit" id="login" name="login" value="Acessar">
        </form>
        <br>
        <small>© 2025 Loja de Eletrodomésticos SAEP</small>
    </div>
</body>
</html>
