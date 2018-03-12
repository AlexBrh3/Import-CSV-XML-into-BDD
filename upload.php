<?php
/**
 * Created by PhpStorm.
 * User: alexi
 * Date: 08/03/2018
 * Time: 21:57
 */

include "add_into_db_file.php";

if(!empty($_FILES["fichier"]["name"])){
    $dir = $_FILES["fichier"]["tmp_name"];
    $ext = substr(strstr($_FILES["fichier"]["name"], "."), 1);

    if(!is_uploaded_file($dir)){
        echo "Fichier manquant.";
    }
    else {
        $ext_valides = ["csv", "xml"];

        if(in_array($ext, $ext_valides)) {
            $taille_max = 10000;

            if ($_FILES["fichier"]["size"] <= $taille_max) {
                $uploads = __DIR__.DIRECTORY_SEPARATOR."uploads";

                if(!file_exists($uploads)) {
                    mkdir($uploads);
                }

                $id = uniqid(rand(), true);
                $name_file = $id.".".$ext;
                $dest = $uploads.DIRECTORY_SEPARATOR.$name_file;
                if(move_uploaded_file($dir, $dest)){
                    add_into_db_file("mock_data", "users", "127.0.0.1", "root", "", $uploads, $name_file);
                }
                else {
                    echo "Erreur";
                }
            }
            else {
                echo "Fichier trop volumineux.";
            }
        }
        else{
            echo "Extension du fichier incorrecte.";
        }
    }
}
else {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["email"]) && !empty($_POST["gender"]) && !empty($_POST["ip_address"])) {
        include "add_into_db_form.php";
        add_into_db_form("mock_data", "users", "127.0.0.1", "root", "");
    }
    else{
        echo "Veuillez remplir tous les champs.";
    }
}