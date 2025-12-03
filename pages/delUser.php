<?php

    include_once('../config/conexao.php');
    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $select = "SELECT * FROM tb_saep_usuario WHERE id_Usuario=:id";

        try {
           $result=$conexao->prepare($select);
           $result->bindParam(':id',$id, PDO::PARAM_INT);
           $result->execute();

           $contar= $result->rowCount();
           if($contar>0){
                $loop = $result->fetchAll();
                foreach($loop as $exibir){

                }
                //Procurar e deletar a imagem
                $fotoDeleta = $exibir['img'];
                $arquivo = "../assets/img/user/".$fotoDeleta;
                unlink($arquivo);

                $delete = "DELETE FROM tb_saep_usuario WHERE id_Usuario=:id";

                try {
                    $result = $conexao->prepare($delete);
                    $result->bindParam(':id',$id,PDO::PARAM_INT);
                    $result->execute();

                    $contar = $result->rowCount();
                    if($contar>0){
                    
                        header("Location: home.php?saep=usuarios");
                    }else{
                        header("Location: home.php?saep=usuarios");
                    }

                } catch (PDOException $e) {
                    echo '<strong>ERRO DE DELETE = </strong>'.$e->getMessage();
                }
                
           }else{
                header('Location: home.php?saep=usuarios');
           }
        } catch (PDOException $e) {
            echo '<strong>ERRO DE SELECT = </strong>'.$e->getMessage();
        }


    }