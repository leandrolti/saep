<?php
include_once('../config/conexao.php');
if (isset($_GET['id'])):
    $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

    $delete = "DELETE FROM tb_saep_cursos WHERE id_cursos=:id";

    try {
        $result = $conexao->prepare($delete);
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        //CONTAR REGISTROS
        $contar = $result->rowCount();
        if ($contar > 0) {
            header("Location:home.php?saep=cursos");
        } else {
            header("Location:home.php?saep=cursos");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
endif;
?>