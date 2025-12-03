<?php
include_once('../config/conexao.php');
if (isset($_GET['idDel'])):
    $id = filter_input(INPUT_GET, 'idDel', FILTER_DEFAULT);

    $delete = "DELETE FROM tb_saep_aluno WHERE idtb_aluno=:id";

    try {
        $result = $conexao->prepare($delete);
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        //CONTAR REGISTROS
        $contar = $result->rowCount();
        if ($contar > 0) {
            header("Location:home.php?esc=lista");
        } else {
            header("Location:home.php?esc=lista");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
endif;
?>