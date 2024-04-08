<?php
include("config.php");

$cambioadv = "";

session_start();
if(!empty($_SESSION['id'])){
    $id = $_SESSION['id'];

    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->bind_result($id,$nombre_usuario,$email,$password,$user,$fechad,$fechaa);
    $stmt->fetch();

    if(!$email && !$password){

    }

    $stmt->close();

    if(!empty($_POST['submitUser'])){
        if(preg_match("/^[a-zA-Z0-9_]{4,20}$/", $_POST["username"])){
            $username = $_POST['username'];

            $stmt = $conn->prepare('UPDATE usuarios SET nombre_usuario = ? WHERE id = ?');
            $stmt->bind_param('si', $username ,$id);
            $stmt->execute();
            $stmt->close();
            $cambioadv = "Username modificado con exito";
        }else{
            $cambioadv = "Username Invalido";
        }
    }elseif(!empty($_POST['submitEmail'])){
        if(preg_match("/^[\w\-\.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,}$/", $_POST["email"])){
            $email = $_POST['email'];

            $stmt = $conn->prepare('UPDATE usuarios SET correo_electronico = ? WHERE id = ?');
            $stmt->bind_param('si', $email ,$id);
            $stmt->execute();
            $stmt->close();
            $cambioadv = "Correo modificado con exito";
        }else{
            $cambioadv = "Correo invalido";
        }
    
    }elseif(!empty($_POST['submitPassword'])){
        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/", $_POST["password"])){
            $passwordnew = $_POST['password'];
            $passwordHash = password_hash( $passwordnew, PASSWORD_DEFAULT);
    
            $stmt = $conn->prepare('UPDATE usuarios SET correo_electronico = ? WHERE id = ?');
            $stmt->bind_param('si', $passwordHash ,$id);
            $stmt->execute();
            $stmt->close();
            $cambioadv = "Contrase modificado con exito";
        }else{
            $cambioadv = "Contrasena invalida";
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
            <img class="w-56" src="https://icones.pro/wp-content/uploads/2021/02/icone-utilisateur-bleu.png" alt="imagen de perfil">
            <h2 class="text-4xl"><?php echo"$nombre_usuario";?></h2>
            <form class="flex flex-col justify-center items-center gap-3" action="editar_perfil.php" method="post">
                <label for="username">Username</label>
                <input class="p-2 rounded-md bg-teal-700" type="text" name="username" value="<?php echo$nombre_usuario; ?>" id="username">
                <input class="p-3 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitUser" value="Cambiar Username">
            </form>
            <form class="flex flex-col justify-center items-center gap-3" action="editar_perfil.php" method="post">
                <label for="email">Correo</label>
                <input class="p-2 rounded-md bg-teal-700" type="correo" name="email" value="<?php echo$email; ?>" id="email">
                <input class="p-3 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitEmail" id="submitEmail" value="Cambiar email">
            </form>
            <form class="flex flex-col justify-center items-center gap-3" action="editar_perfil.php" method="post">
                <label for="password">Contrasena</label>
                <input class="p-2 rounded-md bg-teal-700" type="password" name="password" id="password">
                <input class="p-3 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitPassword" id="submitPassword" value="Cambiar contrasena">
            </form>
            <h3><?php echo $cambioadv;?></h3>
        </section>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>
</body>
</html>