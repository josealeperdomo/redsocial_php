<?php
include("config.php");

session_start();
if(!empty($_SESSION['id'])){
    $id = $_GET['id'];


    $stmt = $conn->prepare('SELECT id_usuario FROM comentarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($id_usuario);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare('SELECT rol FROM usuarios WHERE id = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();

    $stmt->bind_result($rol);
    $stmt->fetch();

    $stmt->close();

    if($_SESSION['id'] == $id_usuario || $rol == 'admin'){
        $stmt = $conn->prepare('DELETE FROM comentarios WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }else{
        echo'ERROR';
    }




    header("Location: dashboard.php");
}else{
    header("Location: index.php");
    exit();
}
exit();

?>