<?php
require_once __DIR__ . '/../vendor/autoload.php';
//anotar comentarios luego
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..'); //en caso de error checar la diagonal que falta despues de los dos puntos xd

//Simplemente lee el archivo -env para usar la variables de entorno
$dotenv -> load();
//crea la clase conexión 
class Conexion {
    //creamos el metodo conectar()
    public function conectar(){
        $usuario = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $usuario, $password, 
        [
            //configurar manejo de errores 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // configurar obtención de registros
            //FETCH_ASSOC -> muestra los resultados como un arreglo asociativo
            // ejemplo: {nombre => 'ejemplo', apaterno => 'ejemplo2'}
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // controlar si PDO simula consultas preparadas
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $conexion;
    }
}

?>