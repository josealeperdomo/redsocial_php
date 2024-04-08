<?php

include("config.php");

$display = '';
$display2 = '';

session_start();
if(!empty($_SESSION['id'])){
    $id = $_SESSION['id'];

    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->bind_result($id,$nombre_usuario,$email,$password,$user,$fechad,$fechaa);
    $stmt->fetch();
    $stmt->close();
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
                $stmt = $conn->prepare("SELECT publicaciones.id, publicaciones.contenido, publicaciones.fecha_creacion, publicaciones.fecha_actualizacion, usuarios.nombre_usuario FROM publicaciones INNER JOIN usuarios ON publicaciones.id_usuario = usuarios.id WHERE publicaciones.id = ? ORDER BY publicaciones.id DESC;");
                            
                $stmt->bind_param("i", $_GET["id"]);

                $stmt->execute();
                    
                $resultado = $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<div class='bg-teal-800 flex flex-col justify-center gap-1 p-2 rounded-md min-w-[400px] max-w-[400px]'>";
                        echo "<h1 class='text-2xl text-indigo-300'><a href='perfil.php?username=".$fila['nombre_usuario']."'>".$fila["nombre_usuario"]."</a></h1>";
                        echo "<h2 class='text-xl'>". $fila['contenido'] . "</h2>";
                        echo "<p class='text-xs'>Fecha de publicacion: " . $fila['fecha_creacion'] . "</p>";
                        echo "<p class='text-xs'>Fecha de actualizacion: " . $fila['fecha_actualizacion'] . "</p>";
                        echo "<form action='agregar_comentario.php'>";
                        echo "<input type='hidden' name='id_publicacion' value='". $fila['id'] . "' />";
                        echo "<input type='hidden' name='location' value='publicacion.php?id=".$fila['id']."'/>";
                        echo "<textarea class='bg-teal-700 w-80 p-2' name='contenido' rows='2' style='resize: none; font-size: 14px;' cols='50'></textarea><br/>";
                        echo "<input class='p-1 hover:cursor-pointer bg-[#6ED1C0] rounded-md' type='submit' value='Agregar Comentario'/>";
                        echo "</form>";
                        $comentarios_stmt = $conn->prepare("SELECT comentarios.id, comentarios.id_usuario, usuarios.nombre_usuario, comentarios.contenido, comentarios.fecha_creacion FROM comentarios INNER JOIN usuarios ON comentarios.id_usuario = usuarios.id WHERE comentarios.id_publicacion = ? ORDER BY comentarios.id DESC;");
                        $comentarios_stmt->bind_param("i", $fila['id']);
                        $comentarios_stmt->execute();
                        $comentarios_resultado = $comentarios_stmt->get_result();



                        if ($comentarios_resultado->num_rows > 0) {
                            echo "<h3>Comentarios:</h3>";
                            echo "<ul class='flex flex-col gap-1'>";
                            while ($comentario = $comentarios_resultado->fetch_assoc()) {
                                if($comentario['id_usuario'] == $_SESSION['id'] || $user === 'admin'){
                                    $display = 'flex';
                                }else{
                                    $display = 'hidden';
                                }
                                if($comentario['id_usuario'] == $_SESSION['id']){
                                    $display2 = 'flex';
                                }else{
                                    $display2 = 'hidden';
                                }
                                echo "<li class='flex flex-col gap-1'>".$comentario['nombre_usuario']. ": " . $comentario['contenido'] . " (Fecha: " . $comentario['fecha_creacion'] . ")<button class='bg-teal-700 text-xs rounded-md p-2 $display2'><a href='editar_comentario.php?id=".$comentario['id']."'>Editar Comentario</a></button><button class='bg-teal-700 text-xs rounded-md p-2 $display'><a href='eliminar_comentario.php?id=".$comentario['id']."'>Eliminar Comentario</a></button></li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No hay comentarios.</p>";
                        }

                        $comentarios_stmt->close();

                                echo "</div>";
                            }
                        } else {
                            echo "<h3>No se encontraron publicaciones.</h3>";
                        }

                        $stmt->close();        
                        ?>
        </section>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>
</body>
</html>