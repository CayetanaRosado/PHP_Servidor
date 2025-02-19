<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 40px;
        text-align: center;
        max-width: 600px;
        width: 100%;
    }

    h1 {
        font-size: 28px;
        color: rgb(26, 42, 77);
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin-bottom: 30px;
        color: #555;
    }

    a {
        font-size: 16px;
        color: white;
        background-color: rgb(26, 42, 77);
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    a:hover {
        background-color: rgb(26, 42, 77);
    }
    .error-message {
        font-size: 2em;
        color: rgb(26, 42, 77);
        background-color: rgb(179, 196, 206);
        padding: 2em;
        border-radius: 5px;
    }
</style>

<body>
    <?php
     // Incluir archivo de conexión para poder hacer la conexion a la base de datos
    include "../controlador/Conexion.php";

    //Ahora establecemos la conexión mediante mysqli_connect
    $conexion = mysqli_connect($host, $usuario, $contraseña, $nombreBD);

    //Comprobamos que la conexión se ha establecido con existo
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Obtenemos los datos del formulario
    $peso = isset($_POST['peso']) ? $_POST['peso'] : 0;
    $altura = isset($_POST['altura']) ? $_POST['altura'] : 0;
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $actividad_fisica = isset($_POST['actividad_fisica']) ? $_POST['actividad_fisica'] : '';
    $objetivo = isset($_POST['objetivo']) ? $_POST['objetivo'] : '';
    $enfermedad = isset($_POST['enfermedad']) ? $_POST['enfermedad'] : '';
    $alimentacion = isset($_POST['alimentacion']) ? $_POST['alimentacion'] : '';
    $quiero_perder = isset($_POST['quieroPerder']) ? $_POST['quieroPerder'] : '';

    //Hacemos un control de errores
    if (($peso < 30 || $peso > 300) || $peso == "No proporcionado") {
        echo "<p>Tienes que meter un valor entre 30 y 300 o no has proporcionado ningun valor</p>";
    }
    if (($altura < 100 || $altura > 250) || $altura == "No proporcionado") {
        echo "<p>Tienes que meter un valor entre 100 y 250 o no has proporcionado ningun valor</p>";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email == "No proporcionado") {
        echo "<p>Tienes que meter un email valido o no has proporcionado ningun valor</p>";
    }

    // Verificación de que los valores no sean 0 antes de calcular IMC
    if ($peso > 0 && $altura > 0) {
        $altura_metros = $altura / 100;
        $imc = $peso / ($altura_metros * $altura_metros);
    } else {
        $imc = 0; // IMC no válido si no tenemos datos suficientes
    }

    //comprobar que no existe ya en la base de datos
    $sql_compruebo = "SELECT email From usuarios where email='$email'";

     // Ejecuta la consulta SQL y almacena el resultado en $resultado_compruebo.
    $resultado_compruebo = mysqli_query($conexion, $sql_compruebo);

    // Verificar si hubo un error al ejecutar la consulta sql_compruebo
    if (!$resultado_compruebo) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }
    
    // Verificar si existe algun resultado de la consulta sql_compruebo
    if (mysqli_num_rows($resultado_compruebo) > 0) {

        // Si existe no inserta al usuario
        echo "<div class='error-message'>Ya existe un registro con ese email
         <a href='../index'>VOLVER AL INICIO</a></div>";
    } else {
        //Si no existe, entonces lo inserta en la base de datos
        $sql = "INSERT INTO usuarios (peso, altura, fecha_nacimiento, email, sexo, actividad_fisica, objetivo, enfermedad, alimentacion, quiero_perder)
    VALUES ('$peso', '$altura', '$fecha_nacimiento', '$email', '$sexo', '$actividad_fisica', '$objetivo', '$enfermedad', '$alimentacion', '$quiero_perder')";


        //Comprobamos que la insección
        $result1 = mysqli_query($conexion, $sql);

        //Confirmamos mediante un mensaje si la insercion ha sido exitosa
        if ($result1) {
            echo "
        <div class='container'>
            <h1>¡Registro Exitoso!</h1>
            <p>Tu información ha sido registrada correctamente. Gracias por unirte.</p>
            <a href='../index'>VOLVER AL INICIO</a>
        </div>";
        } else {
            //o si no ha sido exitosa
            echo "<div class='error-message'>Error: " . $sql . "<br>" . mysqli_connect_error() . "</div>";
        }
    }

    //Cerramos la conexión
    mysqli_close($conexion);
    ?>
</body>

</html>