<?php
class db {
    private $host = 'localhost';
    private $usermane = 'root';
    private $password = '';
    private $database = 'proyecto_1_db';
    private $connection;

    public function __construct()
    {
        try{
            $this->connection = new PDO(
                "mysql:host={$this->host}; dbname={$this->database}",
                $this->usermane,
                $this->password

            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            die("No se pudo hacer la coneccion con la BASE DE DATOS: " . $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}
?>