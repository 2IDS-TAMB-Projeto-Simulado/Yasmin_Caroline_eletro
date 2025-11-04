<?php
    require_once "../config/db.php";
    require_once "model_estoque.php";
    require_once "model_logs.php";

    class Produto{
        public function cadastrar_produto($nome, $descricao, $fornecedor, $categoria, $potencia, $consumo_energetico, $garantia, $prioridade_reposicao, $fk_usu_id) {
            $conn = Database::getConnection();
            $insert = $conn->prepare("INSERT INTO PRODUTO (PROD_NOME, PROD_DESCRICAO, PROD_FORNECEDOR, PROD_CATEGORIA, PROD_POTENCIA, PROD_CONSUMO_ENERGETICO, PROD_GARANTIA, PROD_PRIORIDADE_REPOSICAO, FK_USU_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssssssi", $nome, $descricao, $fornecedor, $categoria, $potencia, $consumo_energetico, $garantia, $prioridade_reposicao, $fk_usu_id);
            $success = $insert->execute();

            if($success){
                $produto_id = $conn->insert_id;

                $estoque = new Estoque();
                $estoque->adicionar_estoque(0, $fk_usu_id, $produto_id);

                $logs = new Logs();
                $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> NOME: ".$nome." <br> AÇÃO: Cadastrado! <br> ID USUÁRIO: ".$fk_usu_id);
            }

            $insert->close();
            return $success;
        }

        public function listar_produtos() {
            $conn = Database::getConnection();
            $sql = "SELECT      P.PROD_ID,
                                P.PROD_NOME,
                                P.PROD_DESCRICAO,
                                P.PROD_FORNECEDOR,
                                P.PROD_CATEGORIA,
                                P.PROD_POTENCIA,
                                P.PROD_CONSUMO_ENERGETICO,
                                P.PROD_GARANTIA,
                                P.PROD_PRIORIDADE_REPOSICAO,
                                A.ESTOQUE_QUANTIDADE,
                                U.USU_NOME,
                                U.USU_EMAIL
                    FROM        PRODUTO P
                    JOIN        USUARIO U ON P.FK_USU_ID = U.USU_ID
                    JOIN        ESTOQUE A ON P.PROD_ID = A.FK_PROD_ID
                    ORDER BY    P.PROD_NOME";
            $result = $conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function excluir_produto($produto_id, $fk_usu_id) {
            $conn = Database::getConnection();
            $delete = $conn->prepare("DELETE FROM PRODUTO WHERE PROD_ID = ?");
            $delete->bind_param("i", $produto_id);

            $logs = new Logs();
            $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> AÇÃO: Excluído! <br> ID USUÁRIO: ".$fk_usu_id);
            
            $success = $delete->execute();
            $delete->close();
            return $success;
        }

        public function buscar_produto_pelo_id($id) {
            $conn = Database::getConnection();
            $select = $conn->prepare("SELECT      P.PROD_ID,
                                                  P.PROD_NOME,
                                                  P.PROD_DESCRICAO,
                                                  P.PROD_FORNECEDOR,
                                                  P.PROD_CATEGORIA,
                                                  P.PROD_POTENCIA,
                                                  P.PROD_CONSUMO_ENERGETICO,
                                                  P.PROD_GARANTIA,
                                                  P.PROD_PRIORIDADE_REPOSICAO,
                                                  A.ESTOQUE_QUANTIDADE,
                                                  U.USU_NOME,
                                                  U.USU_EMAIL
                                      FROM        PRODUTO P
                                      JOIN        USUARIO U ON P.FK_USU_ID = U.USU_ID
                                      JOIN        ESTOQUE A ON P.PROD_ID = A.FK_PROD_ID
                                      WHERE       P.PROD_ID = ?
                                      ORDER BY    P.PROD_NOME");
            $select->bind_param("i", $id);
            $select->execute();
            $result = $select->get_result();
            $produto = $result->fetch_all(MYSQLI_ASSOC);
            $select->close();
            return $produto;
        }

        public function editar_produto($nome, $descricao, $fornecedor, $categoria, $potencia, $consumo_energetico, $garantia, $prioridade_reposicao, $produto_id, $fk_usu_id) {
            $conn = Database::getConnection();
            $update = $conn->prepare("UPDATE PRODUTO SET PROD_NOME = ?, PROD_DESCRICAO = ?, PROD_FORNECEDOR = ?, PROD_CATEGORIA = ?, PROD_POTENCIA = ?, PROD_CONSUMO_ENERGETICO = ?, PROD_GARANTIA = ?, PROD_PRIORIDADE_REPOSICAO = ? WHERE PROD_ID = ?");
            $update->bind_param("ssssssssi", $nome, $descricao, $fornecedor, $categoria, $potencia, $consumo_energetico, $garantia, $prioridade_reposicao, $produto_id);
            $success = $update->execute();

            if($success){
                $logs = new Logs();
                $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> NOME: ".$nome." <br> AÇÃO: Editado! <br> ID USUÁRIO: ".$fk_usu_id);
            }

            $update->close();
            return $success;
        }

        public function filtrar_produto($campo) {
            $conn = Database::getConnection();
            $select = $conn->prepare("SELECT      P.PROD_ID,
                                                  P.PROD_NOME,
                                                  P.PROD_DESCRICAO,
                                                  P.PROD_FORNECEDOR,
                                                  P.PROD_CATEGORIA,
                                                  P.PROD_POTENCIA,
                                                  P.PROD_CONSUMO_ENERGETICO,
                                                  P.PROD_GARANTIA,
                                                  P.PROD_PRIORIDADE_REPOSICAO,
                                                  A.ESTOQUE_QUANTIDADE,
                                                  U.USU_NOME,
                                                  U.USU_EMAIL
                                      FROM        PRODUTO P
                                      JOIN        USUARIO U ON P.FK_USU_ID = U.USU_ID
                                      JOIN        ESTOQUE A ON P.PROD_ID = A.FK_PROD_ID
                                      WHERE       P.PROD_NOME LIKE ?
                                      ORDER BY    P.PROD_NOME");
            $termo = "%" . $campo . "%";
            $select->bind_param("s", $termo);
            $select->execute();
            $result = $select->get_result();
            $produtos = $result->fetch_all(MYSQLI_ASSOC);
            $select->close();
            return $produtos;
        }
    }
?>