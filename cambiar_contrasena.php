<?php

include("config.php");

$passwordadv = "";

$id = $_GET['id'];
$code = $_GET["code"];

$stmt = $conn->prepare("SELECT * FROM codigos WHERE codigo = ?");
$stmt->bind_param("i", $code);
$stmt->execute();
$resultado = $resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $usuario_id = $fila['id_usuario'];

    if(isset($_POST['submit'])) {
        $password = $_POST['password'];

        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/", $password)){
            $passwordDB = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios set contraseña = ? WHERE id = ?");
            $stmt->bind_param("si", $passwordDB, $usuario_id);
            $stmt->execute();
            $stmt->close();
            $stmt = $conn->prepare("DELETE FROM codigos WHERE id_usuario = ?");
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: index.php");
        }else{
            $passwordadv = "Contrasena invalida";
        }
    }
}else{
    header('Location: index.php');
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
    <main  class="h-full min-h-[80vh] flex flex-col items-center justify-center p-10 gap-4 text-[#ffffff] bg-[#4DB6AC]">
        <section class="flex flex-col justify-center items-center gap-3">
            <h1 class="text-xl">INGRESE SU NUEVA CONTRASEÑA</h1>
            <?php echo "<form class='flex flex-col gap-3' action='cambiar_contrasena.php?id=$id&code=$code' method='post'>" ?>
                <input class="p-2 rounded-md bg-teal-700"  type="password" name="password" id="password">
                <input class="p-2 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submit" value="Cambiar contraseña">
            </form>
            <h3><?php echo $passwordadv; ?></h3>
        </section>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>
</body>
</html>
