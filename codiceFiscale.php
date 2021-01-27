<?php
class CodiceFiscale {
    private $surname = "";
    public $name= "";
    private $isMale = true;
    private $date = "";
    private $city = "";
    private $surnameCode = "";
    private $nameCode = "";
    private $dateCode = "";
    private $cityCode = "";
    private $controlCode = "";
    private $finalCode = "";

    private $MONTH_CODES = [
        "01" => 'A',
        "02" => 'B',
        "03" => 'C',
        "04" => 'D',
        "05" => 'E',
        "06" => 'H',
        "07" => 'L',
        "08" => 'M',
        "09" => 'P',
        "10" => 'R',
        "11" => 'S',
        "12" => 'T',
    ];
    private $ODD_CHAR = [
        "0" => 1,  
        "1" => 0, 
        "2" => 5, 
        "3" => 7, 
        "4" => 9, 
        "5" => 13,
        "6" => 15,
        "7" => 17,
        "8" => 19,
        "9" => 21,
        "A" => 1, 
        "B" => 0, 
        "C" => 5, 
        "D" => 7, 
        "E" => 9, 
        "F" => 13,
        "G" => 15,
        "H" => 17,
        "I" => 19,
        "J" => 21,
        "K" => 2, 
        "L" => 4, 
        "M" => 18,
        "N" => 20,
        "O" => 11,
        "P" => 3, 
        "Q" => 6, 
        "R" => 8, 
        "S" => 12,
        "T" => 14,
        "U" => 16,
        "V" => 10,
        "W" => 22,
        "X" => 25,
        "Y" => 24,
        "Z" => 23,
    ];
    private $A_OFFSET = 65;
    private $N_OFFSET = 48;

    public function getCode() {
        return $this->finalCode;
    }

    public function __construct(
        string $_surname, string $_name, bool $_isMale, string $_date, string $_city) {
        $this->surname = strtoupper($_surname);
        $this->name = strtoupper($_name);
        $this->isMale = $_isMale == "1";
        $this->date = strtoupper($_date);
        $this->city = strtoupper($_city);
        $this->init();
    }
    private function isVowel(string $l) {
        if($l == 'A' || $l == 'E' || $l == 'I' || $l == 'O' || $l == 'U')
            return true;
        else
            return false;
    }
    private function cityQuery(string $city) {
        $address = "localhost";
        $user = "root";
        $pass = "";
        $db = "codicicatastali";
        $conn = new mysqli($address, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: ".$conn->connect_error);
        }

        $city = "'%".$city."%'";
        $query = "SELECT CodiceCatastale FROM codicicatastali WHERE Luogo LIKE ".$city." LIMIT 1";
        $result = $conn->query($query);
        $result = $result->fetch_assoc()["CodiceCatastale"];
        $conn->close();

        return $result;
    }
    private function evenConvert(string $c) {
        $ascii = ord($c);
        $result = $ascii > ord("9") ? $ascii - ord("A") : $ascii - ord("0");  
        return $result;
    }
    private function oddConvert(string $c) {
        return $this->ODD_CHAR[$c];
    }
    private function remainderConvert(int $i) {
        return chr($i + $this->A_OFFSET);
    }
    private function generateSurname(string $surname) {
        $result = "";
        $isShort = "true";
        for ($i = 0; ($i < strlen($surname)) && $isShort; $i++) {
            if(!($this->isVowel($surname[$i]))) {
                $result .= $surname[$i];
                if(strlen($result) == 3)
                    $isShort = false;
            }
        }
        for($i = 0; ($i < strlen($surname)) && $isShort; $i++){
            if($this->isVowel($surname[$i])) {
                $result .= $surname[i];
                if(strlen($result) == 3)
                    $isShort = false;
            }
        }
        while(strlen($result) < 3) {
            $result .= 'X';
        }
        return $result;
    }    
    private function generateName(string $name) {
        $result = "";
        $isShort = "true";
        for ($i = 0; ($i < strlen($name)) && $isShort; $i++) {
            if (!$this->isVowel($name[$i])) {
                $result .= $name[$i];
                if (strlen($result) == 4)
                    $isShort = false;
            }
        }
        if(strlen($result) == 4) 
            $result = $result[0].$result[2].$result[3];
        for ($i = 0; ($i < strlen($name)) && $isShort; $i++) {
            if ($this->isVowel($name[$i])) {
                $result .= $name[$i];
                if (strlen($result) == 3)
                    $isShort = false;
            }
        }
        while(strlen($result) < 3) {
            $result .= 'X';
        }
        return $result;
    }
    private function generateDate(string $date, bool $isMale) {
        $dateObj = explode('-', $date);
        $result = $dateObj[0][2].$dateObj[0][3];
        $result .= $this->MONTH_CODES[$dateObj[1]];
        $result .= $isMale ? $dateObj[2] : strval((int)($dateObj[2]) + 40);
        return $result;
    }
    private function generateCity(string $city) {
        return $this->cityQuery($city);
    }
    private function generateControlCode(string $code) {
        $sum = 0;
        $isEven = false;
        for ($i = 0; $i < strlen($code); $i++) {
            $c = $code[$i];
            $sum += $isEven ? $this->evenConvert($c) : $this->oddConvert($c);
            $isEven = !$isEven;
        }
        /*foreach($code as $c) {
            $sum += isEven ? evenConvert($c) : oddConvert($c);
            $isEven = !$isEven;
        }*/
        $remainder = $sum % 26;
        return $this->remainderConvert($remainder);
    }
    private function init() {
        $this->surnameCode = $this->generateSurname($this->surname);
        $this->nameCode = $this->generateName($this->name);
        $this->dateCode = $this->generateDate($this->date, $this->isMale);
        $this->cityCode = $this->generateCity($this->city);
        $this->finalCode = $this->surnameCode.$this->nameCode.$this->dateCode.$this->cityCode;
        $this->controlCode = $this->generateControlCode($this->finalCode);
        $this->finalCode .= $this->controlCode;
    }
}
if(isset($_REQUEST["confirm"])) {
    $code = new CodiceFiscale(
        $_REQUEST["surname"], $_REQUEST["name"], $_REQUEST["gender"], $_REQUEST["date"], $_REQUEST["city"]); 
    echo $code->getCode();
}
?>