<?php
    
    include("config.php");

    use PHPMailer\PHPMailer\PHPMailer;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';


    $correoadv = "";
    if(isset($_POST["submitE"])){
        $stmt = $conn->prepare("SELECT id from usuarios WHERE correo_electronico = ?");
        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();
        $resultado = $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $id = $fila['id'];
        $codigo = rand(100000, 999999);
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO codigos (codigo, id_usuario)  VALUES (?, ?)");
        $stmt->bind_param("is", $codigo, $id);
        $stmt->execute();
        $stmt->close();
        $correoadv = "El codigo ha sido enviado";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jperdomito0410@gmail.com';
        $mail->Password = 'mlyktlzyqcxdcvbb';
        $mail ->SMTPSecure = 'ssl';
        $mail->Port = 465;
    
        $mail->setFrom('jperdomito0410@gmail.com');
        $mail->addAddress($_POST['email']);
        $mail->isHTML(true);
        $mail->Subject = 'CODIGO PARA RECUPERAR CONTRASENA';
        $mail->Body = "Su codigo para recuperar su contraseña es: <br></br> <h1>$codigo</h1>";
        $mail->send();
    }else{
        $correoadv = "El correo no se encuentra en la base de datos";
        $stmt->close();
    }
}

    if(isset($_POST["submitC"])){
        $code = $_POST["code"];

        $stmt = $conn->prepare("SELECT id_usuario FROM codigos WHERE codigo = ?");
        $stmt->bind_param("i", $code);
        $stmt->execute();
        $resultado = $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $usuario_id = $fila['id_usuario'];

            header("Location: cambiar_contrasena.php?id=$usuario_id&code=$code");
            $stmt->close();
        }else{
            $correoadv = "El codigo es incorrecto";
            $stmt->close();
        }

        


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
            <h1 class="text-xl">Ingresa el correo vinculado a tu cuenta</h1>
            <form class="flex flex-col gap-3" action="recuperar_contrasena.php" method="post">
                <input type="email" class="p-2 rounded-md bg-teal-700" placeholder="ingresa el correo" name="email" id="email">
                <input class="p-2 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitE" value="Enviar codigo">
            </form>
            <h3><?php echo $correoadv; ?></h3>
        </section>
        <section class="flex flex-col justify-center items-center gap-3">
            <h1 class="text-xl">Inserte el codigo</h1>
            <form class="flex flex-col gap-2" action="recuperar_contrasena.php" method="post">
                <input class="p-2 rounded-md bg-teal-700"  type="text" placeholder="ingresa el codigo" name="code" id="code">
                <input class="p-2 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitC" value="Enviar codigo">
            </form>
        </section>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>
</body>
</html>