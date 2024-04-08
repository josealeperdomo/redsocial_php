<?php
include("config.php");

session_start();
if(!empty($_SESSION['id'])){

    $idcomprobar = $_GET['id'];

    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $idcomprobar);
    $stmt->execute();

    $stmt->bind_result($iduser,$nombre_usuario,$email,$password,$rol,$fechad,$fechaa);
    $stmt->fetch();
    $stmt->close();

    $id = $_SESSION['id'];

    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->bind_result($idusuario,$nombre_usuario,$email,$password,$rol,$fechad,$fechaa);
    $stmt->fetch();
    $stmt->close();


    if($iduser == $idusuario || $rol == 'admin'){
        $stmt = $conn->prepare('DELETE FROM comentarios WHERE id_usuario = ?');
        $stmt->bind_param('i', $idcomprobar);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare('DELETE FROM publicaciones WHERE id_usuario = ?');
        $stmt->bind_param('i', $idcomprobar);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $idcomprobar);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        if($rol == 'user'){
            session_unset();

            session_destroy();
        }else{
            header("Location: index.php");
        }

        header("Location: index.php");
    }else{
        echo'ERROR 404';
    }

}else{
    header("Location: index.php");
    exit();
}
exit();

?>