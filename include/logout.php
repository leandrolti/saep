<?php
if(isset($_REQUEST['sair'])){
session_start();
    session_destroy();
//    session_unset(['loginuser']);
//    session_unset(['senhauser']);
    header("Location: ../index.php");
}