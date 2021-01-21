<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codice Fiscale PHP</title>
</head>
<body>
    <form action="codiceFiscale.php" method="post">
        Cognome: <input type="text" name="surname" value="Hossain"> <br>
        Nome: <input type="text" name="name" value="Rafiu"> <br>
        Data di nascita: <input type="date" name="date" value="2002-10-23"> <br>
        <input type="radio" name="gender" value="1" checked> Uomo
        <input type="radio" name="gender" value="0"> Donna<br>
        Luogo di nascita: <input type="text" name="city" value="Arzignano"> <br>
        <button type="submit" name="confirm" value="1">Calcola</button>
    </form>
</body>
</html>

