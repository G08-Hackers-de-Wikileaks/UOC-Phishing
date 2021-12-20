<!DOCTYPE html>
<?php
/*
 * @Project: UOC Phishing Simulator
 * @Author: Jordi Montorio Rocafull
 * @Email: jordi7afe@uoc.edu
 */

include "secret.php";

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function addLoginLog($db, $ip, $username){
    $db->query("INSERT INTO login_log VALUES (NULL, '$ip', '$username', DEFAULT)");
}

function getLogTable($db){
    return $db->query("SELECT * FROM login_log ORDER BY id DESC");
}

$ip = getUserIpAddr();
?>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>UOC Phishing Simulator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row mt-2"><h1><img src="https://cv.uoc.edu/mc-icons/inici/logoUOC.png?su=3" alt="" height="60px"> UOC Phishing Simulator</h1>
        <small>TEX - GRUP 08 Hackers de Wikileaks</small>
    </div>
    <div class="row mt-2">

        <div class="alert alert-warning">
            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h4>Benvingut, ".$_POST['username']."!</h4>Aquesta pàgina és un simulador de phishing per conscienciar del gran perill que comporta per als usuaris. Com pots observar el teu nom d'usuari ha quedat enregistrat. Aquesta és una pàgina amb finalitat educativa i, per tant, <b>no hem enregistrat la contrasenya</b>. Malgrat això, hem de ser conscients de la facilitat amb la que es capturen les nostres dades. <br><br><i>Pots comprovar el codi font al nostre GitHub: <a href='https://github.com/G08-Hackers-de-Wikileaks/UOC-Phishing'>https://github.com/G08-Hackers-de-Wikileaks/UOC-Phishing</a></i>";
                addLoginLog($mysqli, $ip, $_POST['username']);

            }else{
                echo "ERROR";
            }
            ?>
        </div>

        <div class="alert alert-info">
            <h4>Com evitar el phishing?</h4>
            Si et fixes a la barra de navegació aquesta pàgina és <b>uoc08.tecnoclub.org</b>, i per tant, pertany al domini de <b>tecnoclub.org</b>, que no forma part del domini oficial de la UOC. També és important fixar-se en el tipus de connexió (http o <b>https</b>) on la segona ens permet garantir que la pàgina visitada és exactament la que volíem visitar.
        </div>

    </div>
    <div class="row">
        <?php
            $result = getLogTable($mysqli);

            if ($result->num_rows > 0) {
                echo "<table class='table table-striped'>";
                echo "<tr class='table-dark'><th>Data</th><th>Usuari</th><th>IP</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td><td>" . $row['ip'] . "</td></tr>";
                }
                echo "</table>";
            }
        ?>
    </div>



</div>

</body>
</html>
