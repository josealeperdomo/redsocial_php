<?php
include("config.php");
$adv = "";
session_start();
if(!empty($_SESSION['id'])){

    if(!empty($_POST['submit'])){
        
        $id = $_POST['id'];
        $contenido = $_POST["contenido"];

        if(preg_match("/^.*\S.*$/", $contenido)){
            $stmt = $conn->prepare('UPDATE comentarios set contenido = ? WHERE id = ?');
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
            <h1 class="text-3xl">Edita tu comentario</h1>
            <?php
                
                $stmt = $conn->prepare("SELECT contenido FROM comentarios WHERE id = ?");
                    
                $stmt->bind_param("i", $_GET["id"]);
                
                $stmt->execute();

                
                $stmt->bind_result($contenido);
            
                $stmt->fetch();
            ?>

            <form class="flex flex-col justify-center items-center gap-2" action="editar_comentario.php" method="post">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <textarea class='bg-teal-700 w-80 p-2' name="contenido" id="contenido" value="Hola" cols="30" rows="4" style='resize: none; font-size: 14px;' cols='50'><?php echo $contenido;  ?></textarea>
                <input class='p-1 hover:cursor-pointer bg-[#6ED1C0] rounded-md' type="submit" name="submit" value="Editar comentario">
            </form>

            <?php
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