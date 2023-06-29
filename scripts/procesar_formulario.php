<?php
require_once 'config.php';

// Establecer la conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $database);

// Verificar si hay errores de conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Agrega estas líneas al principio del archivo "procesar_formulario.php"
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Obtener los valores del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rut = $_POST['rut'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$comuna = $_POST['comuna'];
$motivo = $_POST['motivo'];
$mensaje = $_POST['mensaje'];

// Preparar la consulta SQL INSERT
$sql = "INSERT INTO consultas (nombre, apellido, rut, celular, email, comuna, motivo, mensaje) VALUES ('$nombre', '$apellido', '$rut', '$celular', '$email', '$comuna', '$motivo', '$mensaje')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "La consulta se ha guardado correctamente en la base de datos.";
} else {
    echo "Error al guardar la consulta en la base de datos: " . $conn->error;
}

// Obtener información detallada sobre el error en la consulta SQL
echo "Detalles del error: " . $conn->error;

// Cerrar la conexión a la base de datos
$conn->close();
?>
