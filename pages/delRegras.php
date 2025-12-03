<?php

    include_once('../config/conexao.php');
    if(isset($_GET['idDel'])){
        $id = $_GET['idDel'];

        $select = "SELECT * FROM tb_saep_edital WHERE id_edital=:id";

        try {
           $result=$conexao->prepare($select);
           $result->bindParam(':id',$id, PDO::PARAM_INT);
           $result->execute();

           $contar= $result->rowCount();
           if($contar>0){
                $loop = $result->fetchAll();
                foreach($loop as $exibir){

                }
                

                $delete = "DELETE FROM tb_saep_edital WHERE id_edital=:id";

                try {
                    $result = $conexao->prepare($delete);
                    $result->bindParam(':id',$id,PDO::PARAM_INT);
                    $result->execute();

                    $contar = $result->rowCount();
                    if($contar>0){
                    
                        header("Location: home.php?saep=regras");
                    }else{
                        header("Location: home.php?saep=regras");
                    }

                } catch (PDOException $e) {
                    echo '<strong>ERRO DE DELETE = </strong>'.$e->getMessage();
                }
                
           }else{
                header('Location: home.php?saep=regras');
           }
        } catch (PDOException $e) {
            echo '<strong>ERRO DE SELECT = </strong>'.$e->getMessage();
        }


    }