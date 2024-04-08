<?php
include("config.php");

$display = '';

session_start();
if(!empty($_SESSION['id'])){
    $username = $_GET['username'];

    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE nombre_usuario = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $stmt->bind_result($id,$nombre_usuario,$email,$password,$user,$fechad,$fechaa);
    $stmt->fetch();

    $stmt->close();

    $stmt = $conn->prepare('SELECT rol FROM usuarios WHERE id = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();

    $stmt->bind_result($rol);
    $stmt->fetch();

    $stmt->close();

    if($rol === 'admin'){
        $display = 'flex';
    }else{
        $display = 'hidden';
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
        <section  class="flex flex-col justify-center items-center gap-3">
            <img class="w-56" src="https://icones.pro/wp-content/uploads/2021/02/icone-utilisateur-bleu.png" alt="imagen de perfil">
            <h2 class="text-4xl"><?php echo $nombre_usuario;?></h2>
            <button class='<?php echo $display; ?> p-3 bg-[#6ED1C0] rounded-md'><a href='eliminar_perfil.php?id=<?php echo $id ; ?>'>Eliminar Perfil<a></button>
        </section>

        <section class="flex flex-col justify-center items-center gap-3">
            <h1 class="text-3xl">Publicaciones</h1>
            <?php     
                $stmt = $conn->prepare("SELECT publicaciones.id, publicaciones.contenido, publicaciones.fecha_creacion, publicaciones.fecha_actualizacion, usuarios.nombre_usuario FROM publicaciones INNER JOIN usuarios ON publicaciones.id_usuario = usuarios.id WHERE publicaciones.id_usuario = ? ORDER BY publicaciones.id DESC;");
            
                $stmt->bind_param("i", $id);
                
                $stmt->execute();
                    
                $resultado = $resultado = $stmt->get_result();
                
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<div class='bg-teal-800 flex flex-col justify-center gap-1 p-2 rounded-md min-w-[400px] max-w-[400px]'>";
                        echo "<h1 class='text-2xl text-indigo-300'><a href='perfil.php?username=".$fila['nombre_usuario']."'>".$fila["nombre_usuario"]."</a></h1>";                        
                        echo "<h2 class='text-xl'>". $fila['contenido'] . "</h2>";
                        echo "<p class='text-xs'>Fecha de publicacion: " . $fila['fecha_creacion'] . "</p>";
                        echo "<p class='text-xs'>Fecha de actualizacion: " . $fila['fecha_actualizacion'] . "</p>";
                        echo "<button class='$display p-1 bg-[#6ED1C0] rounded-md'><a href='borrar_publicacion.php?id=".$fila['id'] . "'>Borrar publicacion</a></button>";
                        echo "<a class='text-sm text-teal-200' href='publicacion.php?id=".$fila['id']."'>Ver publicacion</a>"; 
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