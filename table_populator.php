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

$delimitador = ',';
$cerca = '"';

$dir = new DirectoryIterator($path);
foreach ($dir as $fileInfo) {
    $arquivo = $fileInfo->getFilename();

// Abrir arquivo para leitura
    if (($fileInfo->getFilename() != '.') && ($fileInfo->getFilename() != '..')) {
        $f = fopen($path . $arquivo, 'r');

        if ($f) {
            print("Importando arquivo:".$arquivo."\n");
            print("\n ======================================== \n");
            $linhas = 0;
            $erros = 0;
            $cabeca = '';
            $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
            foreach ($cabecalho as $coluna) {
                $value = str_replace("\x00", "_", $coluna);
                $cabeca = $cabeca . "`" . $value . "`,";
            }
            ;
            $cabeca = substr($cabeca, 0, -1);
            $rows = array_map('str_getcsv', file($path . $arquivo));
            $header = array_shift($rows);
            $csv = array();

            foreach ($rows as $row) {
                $query = "Insert INTO internamentos(" . $cabeca . ")";
                $values = "Values";
                $val = "(";
                foreach ($row as $item) {
                    $val = $val . '"' . $item . '",';
                }
                $val = substr($val, 0, -1) . ');';
                $values = $values . $val;
                if ($conn->query($query . $values)) {
                    $linhas++;
                } else {
                   $erros++;
                }

            }

        }
        print("\n ======================================== \n");
        print("Arquivo :".$arquivo." total de erros: ".$erros."/ total de registros importados: ".$linhas."\n");
        print("\n ======================================== \n");
    }
   
}
$conn->close();
