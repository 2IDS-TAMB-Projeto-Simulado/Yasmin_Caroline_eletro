<?php
require_once "./../controller/controller_doce.php";

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">       
        <title>Editar Doce</title>
    </head>
    <body>
        <h1>Editar Doce</h1>
        <form action="" method="POST">
            <label>Nome:</label>
            <br>
            <input type="text" id="nome" name="nome" value="<?php echo $doce_editar["DOCE_NOME"]; ?>" required>

            <br>
            <br>

            <label>Descrição:</label>
            <br>
            <input type="text" id="descricao" name="descricao" value="<?php echo $doce_editar["DOCE_DESCRICAO"]; ?>" required>

            <br>
            <br>

            <label>Fornecedor:</label>
            <br>
            <input type="text" id="fornecedor" name="fornecedor" value="<?php echo $doce_editar["DOCE_FORNECEDOR"]; ?>" required>

            <br>
            <br>

            <label>Categoria:</label>
            <br>
            <input type="text" id="categoria" name="categoria"  value="<?php echo $doce_editar["DOCE_CATEGORIA"]; ?>" required>

            <br>
            <br>

            <label>Prazo de Validade:</label>
            <br>
            <input type="date" id="prazo_validade" name="prazo_validade"  value="<?php echo $doce_editar["DOCE_PRAZO_VALIDADE"]; ?>" required>

            <br>
            <br>

            <label>Prioridade de Reposição:</label>
            <br>
            <input type="text" id="prioridade_reposicao" name="prioridade_reposicao"  value="<?php echo $doce_editar["DOCE_PRIORIDADE_REPOSICAO"]; ?>" required>

            <br>
            <br>

            <input type="submit" id="editar_doce" name="editar_doce" value="Salvar Alterações">
        </form>
        <br>
        <a href="inicial.php"><button>Voltar</button></a>
    </body>
</html>