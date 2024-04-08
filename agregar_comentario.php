<?php
    include("config.php");
    session_start();
    if(!empty($_SESSION["id"]) && $_GET["contenido"] !== ""){
        $id_publicacion = $_GET["id_publicacion"];
        $contenido = $_GET['contenido'];
        $id_usuario = $_SESSION["id"];
        $location = $_GET["location"];

        $stmt = $conn->prepare("INSERT INTO comentarios (id_usuario, id_publicacion, contenido) VALUES (?, ?, ?)");
    
        $stmt->bind_param("sss", $id_usuario, $id_publicacion, $contenido);
        
        $stmt->execute();

        $stmt->close();

        $conn->close();

        header("Location: $location");

    }else{
        $location = $_GET["location"];
        header("Location: $location");
        exit();
    }


?>