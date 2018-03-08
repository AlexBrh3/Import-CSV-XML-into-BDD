<?php
/**
 * Created by PhpStorm.
 * User: Alexis Brohan
 * Date: 01/03/2018
 * Time: 09:21
 */

function add_into_db ($dbname, $table, $host, $login, $password, $path, $filename){

    try {
        $pdo = new PDO("mysql:host=" . $host . ";port=3306;dbname=".$dbname, $login, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $base = $pdo->query('SELECT * FROM '.$table);
        $base = $base->fetchAll(PDO::FETCH_ASSOC);

        $lastData = count($base);


        //Utilisation de la classe import


        require "import.php";

        $import = new import($path, $filename);

        $ext = $import->getFileExtension();

        if($ext == "csv"){
            $data = $import->importCSV();
            $champs = $import->getChampCSV($data);

            for ($i = $lastData + 1; $i < count($data); $i++) {
                $line = $import->getLineCSV($data, $i);
                $pdo->exec('INSERT INTO ' . $table . ' (' . $champs . ') VALUES ('
                    . $line . ')');
            }
            echo "Fichier CSV importé dans la base de donnée $dbname.";
        }
        else if ($ext == "xml") {
            $data = $import->importXML();
            $champs = $import->getChampXML($data);
            $users = $data->user;

            for($i= $lastData; $i < count($users); $i++){
                $line = $import->getlineXML($users[$i]);
                $pdo->exec('INSERT INTO ' . $table . ' (' . $champs . ') VALUES ('
                    . $line . ')');
            }
            echo "Fichier XML importé dans la base de donnée $dbname.";
        }
        else {
            echo "Cette extension n'est pas encore prise en charge.";
        }
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    finally {
        $pdo = null;
    }
}

add_into_db("mock_data", "users", "127.0.0.1", "root", "", __DIR__, "MOCK_DATA.xml");
