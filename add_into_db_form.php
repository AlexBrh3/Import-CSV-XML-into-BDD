<?php
/**
 * Created by PhpStorm.
 * User: alexi
 * Date: 09/03/2018
 * Time: 10:28
 */

function add_into_db_form ($dbname, $table, $host, $login, $password){

    try {
        $pdo = new PDO("mysql:host=" . $host . ";port=3306;dbname=" . $dbname, $login, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        extract($_POST);

        $pdo->exec('INSERT INTO '.$table.' (first_name, last_name, email, gender, ip_address) VALUES ("'
            .$first_name.'", "'.$last_name.'", "'.$email.'", "'.$gender.'", "'.$ip_address.'")');

        echo "Utilisateur ajouté avec succès.";
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    finally {
        $pdo = null;
    }
}