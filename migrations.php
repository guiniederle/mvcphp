<?php

$dbconfig = json_decode(file_get_contents("dbconfig.json"));

try {
    $db = new PDO("pgsql:dbname={$dbconfig->dbname};host={$dbconfig->dbhost}", $dbconfig->dbuser, $dbconfig->dbpass);
    $db->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
} catch (PDOException $e) {
    var_dump($e->getMessage());exit;
}

echo "----------------------------------------- RODANDO MIGRATIONS --------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS tax (
    id SERIAL PRIMARY KEY,
    description char(255),
    percentage float
)";
$table = $db->exec($sql);
if (!$table) {
    echo "tax ok \n";
} else {
    var_dump($db->errorInfo());
    echo "tax não \n";
}

echo "---------------------------------------------------------------------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS  producttype (
    id SERIAL PRIMARY KEY,
    description char(255)
)";
$table = $db->exec($sql);
if (!$table) {
    echo "producttype ok \n";
} else {
    var_dump($db->errorInfo());
    echo "producttype não \n";
}

echo "---------------------------------------------------------------------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS  taxproducttype (
        productTypeId integer NOT NULL,
        taxId integer NOT NULL,
        FOREIGN KEY (productTypeId) REFERENCES producttype (id),
        FOREIGN KEY (taxId) REFERENCES tax (id)
    )";
$table = $db->exec($sql);
if (!$table) {

    echo "taxproducttype ok \n";
} else {
    var_dump($db->errorInfo());
    echo "taxproducttype não \n";
}

echo "----------------------------------------- FIM DAS MIGRATIONS --------------------------------\n";