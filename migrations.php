<?php

$dbconfig = json_decode(file_get_contents("dbconfig.json"));

try {
    $db = new PDO("pgsql:dbname={$dbconfig->dbname};host={$dbconfig->dbhost}", $dbconfig->dbuser, $dbconfig->dbpass);
    $db->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
} catch (PDOException $e) {
    var_dump($e->getMessage());exit;
}

echo "----------------------------------------- RODANDO MIGRATIONS --------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS imposto (
    id integer PRIMARY KEY,
    descricao char(255),
    porcentagem float
)";
$table = $db->exec($sql);
if (!$table) {
    echo "imposto ok \n";
} else {
    var_dump($db->errorInfo());
    echo "imposto não \n";
}

echo "---------------------------------------------------------------------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS  tiposprodutos (
    id integer CONSTRAINT firstkey PRIMARY KEY,
    descricao char(255)
)";
$table = $db->exec($sql);
if (!$table) {
    echo "tiposprodutos ok \n";
} else {
    var_dump($db->errorInfo());
    echo "tiposprodutos não \n";
}

echo "---------------------------------------------------------------------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS  impostotiposprodutos (
        tipoProdutoId integer NOT NULL,
        impostoId integer NOT NULL,
        FOREIGN KEY (tipoProdutoId) REFERENCES tiposprodutos (id),
        FOREIGN KEY (impostoId) REFERENCES imposto (id)
    )";
$table = $db->exec($sql);
if (!$table) {

    echo "impostotiposprodutos ok \n";
} else {
    var_dump($db->errorInfo());
    echo "impostotiposprodutos não \n";
}

echo "----------------------------------------- FIM DAS MIGRATIONS --------------------------------\n";