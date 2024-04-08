<?php

include("config.php");

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$camposadv = "";
$emailadv = "";
$usernameadv = "";
$passwordadv = "";
$registroadv = "";
$validez = 0;

session_start();
if(!empty($_SESSION['id'])){
    header('Location: mi_perfil.php');
    exit(); 
}

if (!empty($_POST['submitR'])){
    
    if($_POST['email'] !== "" && $_POST['password'] !== "" && $_POST['user'] !== ""){

        $email = $_POST['email'];

        $stmt = $conn->prepare("SELECT correo_electronico FROM usuarios WHERE correo_electronico = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $emailadv = "El correo electrónico ya está registrado";
    } else {
        if(preg_match("/^[\w\-\.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,}$/", $email)){
            $validez += 1;
        }else{
            $emailadv = "Email invalido <br/>"; 
        }
    }

        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/", $_POST["password"])){
            $password = $_POST['password'];
            $passwordDB = password_hash($password, PASSWORD_DEFAULT);
            $validez += 1;
        }else{
            $passwordadv = "Contrasena invalida <br/>";
        }

        $username = $_POST["user"];

        $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $usernameadv = "El username electrónico ya está registrado</br>";
    } else {
        if(preg_match("/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*$/", $username)){
            $validez += 1;
        }else{
            $usernameadv = "username invalido <br/>";
        }
    }
        if($validez == 3){
        
            $stmt = $conn->prepare("INSERT INTO Usuarios (nombre_usuario, correo_electronico, contraseña, rol) VALUES (?, ?, ?, 'user')");
    
            $stmt->bind_param("sss", $username, $email, $passwordDB);
            
            $stmt->execute();
    
            $stmt->close();

            $conn->close();

            $camposadv = "Refistro exitoso, inicie sesion";
            $correo = $_POST['email'];
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jperdomito0410@gmail.com';
            $mail->Password = 'mlyktlzyqcxdcvbb';
            $mail ->SMTPSecure = 'ssl';
            $mail->Port = 465;
        
            $mail->setFrom('jperdomito0410@gmail.com');
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a Red Social! Comienza tu aventura hoy';
            $mail->Body = '¡Bienvenido a Red Social!<br><br>Estamos emocionados de tenerte como parte de nuestra comunidad. En Red Social, puedes conectarte con amigos, compartir momentos especiales, y descubrir nuevas experiencias juntos.<br><br>Nuestra misión es crear un espacio seguro y acogedor donde puedas expresarte libremente y crear conexiones significativas.<br><br>No dudes en explorar todas las características que ofrecemos y comenzar a interactuar con otros usuarios. ¡Tu aventura en Red Social acaba de empezar!<br><br>Si tienes alguna pregunta o necesitas ayuda, no dudes en ponerte en contacto con nuestro equipo de soporte.<br><br>¡Disfruta tu estadía en Red Social!<br><br>Atentamente,<br>El equipo de Red Social<br>';
            $mail->send();
    
        }else{
            $camposadv = 'Rellena todos los campos correctamente <br/>';
        }


    }else{
        $camposadv = 'Rellena todos los campos <br/>';
    }
}elseif (!empty($_POST['submitL'])) {
    if($_POST['emailL'] !== "" && $_POST['passwordL']){
        $correo = $_POST['emailL'];
        $contrasena = $_POST['passwordL'];

        $stmt = $conn->prepare('SELECT * FROM usuarios WHERE correo_electronico = ?');

        $stmt->bind_param("s", $correo);
        
        $stmt->execute(); 

        $stmt->store_result();
        $num_rows = $stmt->num_rows;
    
        if($num_rows > 0) {
            $stmt->bind_result($id,$nombre_usuario,$email,$password,$user,$fechad,$fechaa);
            $stmt->fetch();
    
            if(password_verify($contrasena, $password)){
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['id'] = $id;
                header("Location: mi_perfil.php");
                exit();
            } else {
                $passwordadv = 'Contraseña incorrecta';
            }
        } else {
            $emailadv = 'El correo electrónico no está registrado';
        }
    
        $stmt->close();
    }else{
        $camposadv = 'Rellena todos los campos correctamente <br/>';
    }
}else{

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
                <li><a href="recuperar_contrasena.php">Recuperar contraseña</a></li>
            </ul>
        </section>
    </header>
    <main class="h-full min-h-[80vh] flex flex-col items-center justify-center p-10 gap-4 text-[#ffffff] bg-[#4DB6AC]">
        <section class="flex gap-4">
            <button class="p-3 bg-[#6ED1C0] rounded-md" onclick="mostrarR()">Registrarse</button>
            <button class="p-3 bg-[#6ED1C0] rounded-md" onclick="mostrarL()">Iniciar sesion</button>
        </section>
        <section id="registro" class="flex flex-col justify-center items-center gap-3">
            <h2>REGISTRARSE</h2>
            <form class="flex flex-col justify-center items-center gap-3" action="index.php" method="post">
                <label for="email">Correo electronico</label>
                <input class="p-2 rounded-md bg-teal-700" type="email" name="email" id="email" placeholder="Ingresa tu correo electronico">
                <label for="password">Contraseña</label>
                <input class="p-2 rounded-md bg-teal-700" type="password" name="password" id="password" placeholder="Ingresa una contraseña">
                <label for="user">Username</label>
                <input class=" p-2 rounded-md bg-teal-700" type="text" name="user" id="user" placeholder="Ingresa un username">
                <input class="p-3 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitR" value="Registrarte">
            </form>
        </section>
        <section id="login" class="hidden flex-col justify-center items-center gap-3">
            <h2>INICIAR SESION</h2>
            <form class="flex flex-col justify-center items-center gap-3" action="index.php" method="post">
                <label for="email">Correo electronico</label>
                <input class="rounded-md p-2 bg-teal-700" type="email" name="emailL" id="emailL" placeholder="Ingresa tu correo electronico">
                <label for="password">Contraseña</label>
                <input class="p-2 rounded-md bg-teal-700" type="password" name="passwordL" id="passwordL" placeholder="Ingresa una contraseña">
                <input class="p-3 bg-[#6ED1C0] rounded-md hover:cursor-pointer" type="submit" name="submitL" value="Iniciar sesion">
            </form>
        </section>
        <h3><?php echo "$camposadv";echo "$emailadv";echo "$passwordadv";echo "$usernameadv";  ?></h3>
    </main>
    <footer class="bg-teal-700 flex justify-between p-4 text-white items-center">
        <h1>PAGINA HECHA POR JOSE PERDOMO</h1>
    </footer>


    <script src="index.js"></script>
</body>
</html>