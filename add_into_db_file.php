<?php
/**
 * Created by PhpStorm.
 * User: alexi
 * Date: 09/03/2018
 * Time: 10:26
 */

function add_into_db_file ($dbname, $table, $host, $login, $password, $path, $filename){

    try {
        $pdo = new PDO("mysql:host=" . $host . ";port=3306;dbname=".$dbname, $login, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //Utilisation de la classe import


        require "import.php";

        $import = new import($path, $filename);

        $ext = $import->getFileExtension();

        if($ext == "csv"){
            $data = $import->importCSV();
            $champs = $import->getChampCSV($data);

            for ($i = 1; $i < count($data); $i++) {
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

            for($i= 0; $i < count($users); $i++){
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