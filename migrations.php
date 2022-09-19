<?php

$dbconfig = json_decode(file_get_contents("env.json"));

try {
    $db = new PDO(
        "pgsql:dbname={$dbconfig->dbconfig->dbname};host={$dbconfig->dbconfig->dbhost}",
        $dbconfig->dbconfig->dbuser,
        $dbconfig->dbconfig->dbpass
    );
    $db->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
} catch (PDOException $e) {
    var_dump($e->getMessage());exit;
}
//$sql = "TRUNCATE producttype CASCADE";
//$table = $db->exec($sql);
//exit;

echo "----------------------------------------- RODANDO MIGRATIONS --------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS tax (
    id SERIAL PRIMARY KEY,
    description text,
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
    description text
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

echo "---------------------------------------------------------------------------------------------\n";

$sql = "CREATE TABLE IF NOT EXISTS  products (
        id SERIAL PRIMARY KEY,
        description text,
        price float,
        productTypeId integer NOT NULL,
        FOREIGN KEY (productTypeId) REFERENCES producttype (id)
    )";
$table = $db->exec($sql);
if (!$table) {

    echo "products ok \n";
} else {
    var_dump($db->errorInfo());
    echo "products não \n";
}

echo "----------------------------------------- FIM DAS MIGRATIONS --------------------------------\n";