<?php
$env = parse_ini_file('.env');

$servername = $env['SERVER_NAME'];
$username = $env['USER_NAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

$path = "data/";
$diretorio = dir($path);

$arquivo = scandir($path)[2];

$delimitador = ',';
$cerca = '"';

// Abrir arquivo para leitura
$f = fopen($path . $arquivo, 'r');

if ($f) {
// sql to create table

    $sql = "CREATE TABLE internamentos (`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
    // Ler cabecalho do arquivo
    $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
    foreach ($cabecalho as $coluna) {
        $value = str_replace("\x00", "_", $coluna);
        $sql = $sql . ", " . $value . " text";
    }
    ;
}
$sql = $sql . ");";
print("Query => ");
print($sql . "\n");
print("\n ================================================================================================= \n");

if ($conn->query($sql)) {
    print("Tabela internamentos criada com sucesso \n");
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
