<?php
/**
 * Created by PhpStorm.
 * User: alexi
 * Date: 07/03/2018
 * Time: 16:36
 */

class import
{
    private $_path;
    private $_filename;

    public function __construct($path, $filename)
    {
        $this->_path = $path;
        $this->_filename = $filename;
    }

    public function getFileExtension(){

        $filepath = $this->_path.DIRECTORY_SEPARATOR.$this->_filename;
        $extension = new SplFileInfo($filepath);
        $extension = $extension->getExtension();

        if(empty($extension)){
            die("Erreur : le fichier n'a pas d'extension");
        }
        return $extension;
    }

    public function importCSV (){

        $filepath = implode(DIRECTORY_SEPARATOR, array(
            $this->_path,
            $this->_filename
        ));

        if (file_exists($filepath)) {
            $dataCSV = array_map("str_getcsv", file($filepath));
            return $dataCSV;
        } else {
            die("Fichier CSV inexistant.");
        }
    }

    public function importXML (){

        $filepath = implode(DIRECTORY_SEPARATOR, array(
            $this->_path,
            $this->_filename
        ));

        if (file_exists($filepath)) {
            $dataXML = simplexml_load_file($filepath);
            return $dataXML;
        }
        else {
            die ("Fichier XML inexistant");
        }
    }

    public function getLineCSV ($data, $i){
            $line = "";
            for ($j = 1; $j < count($data[0]); $j++) {
                $line .= ', "' . $data[$i][$j] . '"';
            }
            $line = substr($line, 2);
            return $line;
    }

    public function getChampCSV ($data){
        $champs = "";
        for ($i = 1; $i < count($data[0]); $i++) {
            $champs .= ", " . $data[0][$i];
        }
        $champs = substr($champs, 2);
        return $champs;
    }

    public function getlineXML ($users){
            $line = "";
            foreach ($users as $value){
                $line .= ', "'.$value.'"';
            }
            $line = substr($line, 2);
            return $line;
    }

    public function getChampXML ($data) {
        $champs = "";
        foreach ($data->user[0] as $key=>$value){
            $champs .= ", " . $key;
        }
        $champs = substr($champs, 2);
        return $champs;
    }
}


