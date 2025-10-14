<?php
    require_once "../config/db.php";
    require_once "model_estoque.php";
    require_once "model_logs.php";

    class Doce{
        public function cadastrar_doce($nome, $descricao, $fornecedor,  $categoria, $prazo_validade, $prioridade_reposicao, $fk_usu_id) {
            $conn = Database::getConnection();
            $insert = $conn->prepare("INSERT INTO DOCE (DOCE_NOME, DOCE_DESCRICAO, DOCE_FORNECEDOR,DOCE_CATEGORIA,DOCE_PRAZO_VALIDADE, DOCE_PRIORIDADE_REPOSICAO, FK_USU_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssssi", $nome, $descricao, $fornecedor, $categoria, $prazo_validade, $prioridade_reposicao, $fk_usu_id);
            $success = $insert->execute();

            if($success){
                $doce_id = $conn->insert_id;

                $estoque = new Estoque();
                $estoque->adicionar_estoque(0,$fk_usu_id,$doce_id);

                $logs = new Logs();
                $logs->cadastrar_logs("DOCE <br> ID: ".$doce_id." <br> NOME: ".$nome." <br> AÇÃO: Cadastrado! <br> ID USUÁRIO: ".$fk_usu_id);
            }

            $insert->close();
            return $success;
        }

        public function listar_doces() {
            $conn = Database::getConnection();
            $sql = "SELECT      L.DOCE_ID,
                                L.DOCE_NOME,
                                L.DOCE_DESCRICAO,
                                L.DOCE_FORNECEDOR,
                                L.DOCE_CATEGORIA,
                                L.DOCE_PRAZO_VALIDADE,
                                L.DOCE_PRIORIDADE_REPOSICAO,
                                A.ESTOQUE_QUANTIDADE,
                                U.USU_NOME,
                                U.USU_EMAIL
                    FROM        DOCE L
                    JOIN        USUARIO U ON L.FK_USU_ID = U.USU_ID
                    JOIN        ESTOQUE A ON L.DOCE_ID = A.FK_DOCE_ID
                    ORDER BY    L.DOCE_NOME";
            $result = $conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function excluir_doce($doce_id, $fk_usu_id) {
            $conn = Database::getConnection();
            $delete = $conn->prepare("DELETE FROM DOCE WHERE DOCE_ID = ?");
            $delete->bind_param("i", $doce_id);

            $logs = new Logs();
            $logs->cadastrar_logs("DOCE <br> ID: ".$doce_id." <br> AÇÃO: Excluído! <br> ID USUÁRIO: ".$fk_usu_id);
            
            $success = $delete->execute();
            $delete->close();
            return $success;
        }

        public function buscar_doce_pelo_id($id) {
            $conn = Database::getConnection();
                $select = $conn->prepare ("SELECT        L.DOCE_ID,
                                                        L.DOCE_NOME,
                                                        L.DOCE_DESCRICAO,
                                                        L.DOCE_FORNECEDOR,
                                                        L.DOCE_CATEGORIA,
                                                        L.DOCE_PRAZO_VALIDADE,
                                                        L.DOCE_PRIORIDADE_REPOSICAO,
                                                        A.ESTOQUE_QUANTIDADE,
                                                        U.USU_NOME,
                                                        U.USU_EMAIL
                                                            FROM        DOCE L
                                        JOIN        USUARIO U ON L.FK_USU_ID = U.USU_ID
                                        JOIN        ESTOQUE A ON L.DOCE_ID = A.FK_DOCE_ID
                                        ORDER BY    L.DOCE_NOME");
            $select->bind_param("i", $id);
            $select->execute();
            $result = $select->get_result();
            $doce = $result->fetch_all(MYSQLI_ASSOC);
            $select->close();
            return $doce;
        }

        public function editar_doce($nome, $descricao, $fornecedor, $categoria, $prazo_validade, $prioridade_reposicao, $doce_id, $fk_usu_id) {
            $conn = Database::getConnection();
            $insert = $conn->prepare("UPDATE DOCE SET DOCE_NOME = ?, DOCE_DESCRICAO = ?, DOCE_FORNECEDOR = ?, DOCE_CATEGORIA = ?, DOCE_PRAZO_VALIDADE = ?, DOCE_PRIORIDADE_REPOSICAO =?  WHERE DOCE_ID = ?");
            $insert->bind_param("ssssssi", $nome, $descricao, $fornecedor, $categoria, $prazo_validade, $prioridade_reposicao, $ldoce_id);
            $success = $insert->execute();

            if($success){
                $logs = new Logs();
                $logs->cadastrar_logs("DOCE <br> ID: ".$doce_id." <br> NOME: ".$nome." <br> AÇÃO: Editado! <br> ID USUÁRIO: ".$fk_usu_id);
            }

            $insert->close();
            return $success;
        }

        public function filtrar_doce($campo) {
            $conn = Database::getConnection();
            $select = $conn->prepare("SELECT        L.DOCE_ID,
                                                    L.DOCE_NOME,
                                                    L.DOCE_DESCRICAO,
                                                    L.DOCE_FORNECEDOR,
                                                    L.DOCE_CATEGORIA,
                                                    L.DOCE_PRAZO_VALIDADE,
                                                    L.DOCE_PRIORIDADE_REPOSICAO,
                                                    A.ESTOQUE_QUANTIDADE,
                                                    U.USU_NOME,
                                                    U.USU_EMAIL
                                        FROM        DOCE L
                                        JOIN        USUARIO U ON L.FK_USU_ID = U.USU_ID
                                        JOIN       ESTOQUE A ON L.DOCE_ID = A.FK_DOCE_ID
                                        WHERE       L.DOCE_NOME LIKE ?
                                        ORDER BY    L.DOCE_NOME");
            $termo = "%" . $campo . "%";
            $select->bind_param("s", $termo);
            $select->execute();
            $result = $select->get_result();
            $doces = $result->fetch_all(MYSQLI_ASSOC);
            $select->close();
            return $doces;
        }
    }
?>