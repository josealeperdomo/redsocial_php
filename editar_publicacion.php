<?php
include("config.php");
$adv = "";
session_start();
if(!empty($_SESSION['id'])){

    if(!empty($_POST['submit'])){
        
        $id = $_POST['id'];
        $contenido = $_POST["contenido"];

        if(preg_match("/^.*\S.*$/", $contenido)){
            $stmt = $conn->prepare('UPDATE publicaciones set contenido = ? WHERE id = ?');
            $stmt->bind_param('si', $contenido, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            header("Location: mi_perfil.php");
        }else{
            echo "El contenido no puede estar vacio";
            header("Location: editar_publicacion.php?id=$id");
        }
    }



}else{
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Red Social</title>
    <link rel="shortcut icon" href="image/R.jpg" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="bg-teal-700 flex justify-between p-10 text-white items-center">
        <section>
            <h1 class="text-6xl">Red Social</h1>
        </section>
        <section>
            <ul class="flex text-xl gap-5">
                <li><a href="index.php">Login/Register</a></li>
                <li><a href="dashboard.php">Publicaciones</a></li>
                <li><a href="mi_perfil.php">Perfil</a></li>
                <li><a href="cerrar_sesion.php">Cerrar sesion</a></li>
            </ul>
        </section>
    </header>

    <main class="h-full min-h-[80vh] flex flex-col items-center justify-center p-10 gap-4 text-[#ffffff] bg-[#4DB6AC]">
        <section class="flex flex-col justify-center items-center gap-3">
            <?php
                
                $stmt = $conn->prepare("SELECT id, contenido, fecha_creacion, fecha_actualizacion FROM publicaciones WHERE id = ? ORDER BY id DESC");
                    
                $stmt->bind_param("i", $_GET["id"]);
                
                $stmt->execute();
                    
                $resultado = $resultado = $stmt->get_result();
                
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<div class='bg-teal-800 flex flex-col justify-center gap-1 p-2 rounded-md min-w-[400px] max-w-[400px] items-center'>";
                        echo "<h2>Texto original: ". $fila['contenido'] . "</h2>";
                        echo "<h3>Ingrese el nuevo texto:</h3>";
                        echo "<form action='editar_publicacion.php' method='post'>";
                        echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
                        echo "<textarea class='bg-teal-700 w-80 p-2' name='contenido' style='resize: none; font-size: 14px;' rows='4' cols='50'></textarea><br />";
                        echo "<input class='p-1 hover:cursor-pointer bg-[#6ED1C0] rounded-md' type='submit' name='submit' value='Editar Publicacion' />";
                        echo "</form>";
                    }
                } else {
                    echo "<h3>No se encontraron publicaciones.</h3>";
                }

                $stmt->close(); 
                $conn->close();
            ?>
        </section>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>
</body>
</html>