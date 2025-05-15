<?php
class Bills {
    private $id;
    private $value;
    private $idCategory;
    private $idReport;
    
    // Propiedades adicionales para mostrar información relacionada
    private $categoryName;
    private $month;
    private $year;
    private $percentage;

    // Constructor
    public function __construct() {
        // Constructor vacío
    }

    // Getters y setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getIdCategory() {
        return $this->idCategory;
    }

    public function setIdCategory($idCategory) {
        $this->idCategory = $idCategory;
    }

    public function getIdReport() {
        return $this->idReport;
    }

    public function setIdReport($idReport) {
        $this->idReport = $idReport;
    }

    // Getters y setters para propiedades adicionales
    public function getCategoryName() {
        return $this->categoryName;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

    public function getMonth() {
        return $this->month;
    }

    public function setMonth($month) {
        $this->month = $month;
    }

    public function getYear() {
        return $this->year;
    }

    public function setYear($year) {
        $this->year = $year;
    }

    public function getPercentage() {
        return $this->percentage;
    }

    public function setPercentage($percentage) {
        $this->percentage = $percentage;
    }
}
?>