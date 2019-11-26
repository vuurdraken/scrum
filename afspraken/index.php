<?php
    function connect($a){
        if ($a==1){
            $servername = "localhost";
            $username = "root";
            $password = "";
            try {
                $conn = new PDO("mysql:host=$servername", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected successfully";
                return $conn;
                }
            catch(PDOException $e)
                {
                //echo "Connection failed: " . $e->getMessage();
                }
        }else if ($a==2){
    
                $servername = "localhost";
                $username = "root";
                $password = "";
                $DB="afspraken";
    
    
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$DB", $username, $password);
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //echo "Connected successfully";
                    return $conn;
                    }
                catch(PDOException $e)
                    {
                    echo "Connection failed: " . $e->getMessage();
                    }
        }
    }
    function create_database(){
        $conn = connect(1);
        $conn->query("CREATE DATABASE IF NOT EXISTS afspraken");
        stop_connect(1);
    }
    function create_table(){
        $conn = connect(2);
        $conn->query("CREATE TABLE IF NOT EXISTS afspraken (
            naam_leerling VARCHAR(20) NOT NULL,
            afspraak_naam_persoon VARCHAR(20) NOT NULL,
            afspraak_plaats VARCHAR(50) NOT NULL,
            afspraak_voor VARCHAR(50) NOT NULL,
            datum VARCHAR(10) NOT NULL,
            tijd VARCHAR(5) NOT NULL,
            contact_manier VARCHAR(255) NOT NULL
            )
        ");
        stop_connect(2);
    }
    function stuur_data(){
        if(isset($_POST['add'])){
            $conn = connect(2);
            //set alle data
            $naam_lerling = $_POST["naam_lerling"];
            $afspraak_naam_persoon = $_POST["afspraak_naam_persoon"];
            $afspraak_plaats = $_POST["afspraak_plaats"];
            $afspraak_voor = $_POST["afspraak_voor"];
            $datum = $_POST["datum"];
            $tijd = $_POST["tijd"];
            $contact_manier = $_POST["contact_manier"];
            if(!isset($naam_lerling) && !isset($afspraak_naam_persoon) && !isset($afspraak_plaats) && !isset($afspraak_voor) && !isset($datum) && !isset($tijd) && !isset($contact_manier)){
                header("Location:index.php");
            }else{
                if(!isset($naam_lerling)){
                    $naam_lerling = "-";
                    }
                if(!isset($afspraak_naam_persoon)){
                    $afspraak_naam_persoon = "-";
                    }
                if(!isset($afspraak_plaats)){
                    $afspraak_plaats = "-";
                    }
                if(!isset($afspraak_voor)){
                    $afspraak_voor = "-";
                    }
                if(!isset($datum)){
                    $datum = "-";
                    }
                if(!isset($tijd)){
                    $tijd = "-";
                    }
                if(!isset($contact_manier)){
                    $contact_manier = "-";
                    }

                //kijken of data al bestaat
                $data = $conn->query("SELECT * FROM afspraken WHERE naam_leerling='$naam_lerling' AND  afspraak_naam_persoon='$afspraak_naam_persoon' AND afspraak_plaats='$afspraak_plaats' AND afspraak_voor='$afspraak_voor' AND datum='$datum' AND tijd='$tijd' AND contact_manier='contact_manier'");
                $data = $data->fetchall(PDO::FETCH_ASSOC);
                if(sizeof($data) >0){
                    header("Location:index.php");
                }else{
                    $conn->query("INSERT INTO afspraken VALUES ('$naam_lerling','$afspraak_naam_persoon', '$afspraak_plaats', '$afspraak_voor', '$datum', '$tijd', '$contact_manier')");
                    header("Location:index.php");
                }
            }
            stop_connect(2);
        }
    }
    function stop_connect($i){
        $conn = connect($i);
        $conn = null;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Afspraken</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        create_database();
        create_table();
        stuur_data();
        echo "<table>";
        echo "<tr><th>naam_lerling</th><th>afspraak_naam_persoon</th><th>afspraak_plaats</th><th>afspraak_voor</th><th>datum</th><th>tijd</th><th>contact_manier</th><th></th></tr>";
    ?>
    <tr>
        <form id="stuur" name="stuur" action="" method="post">
            <td><input type="text" name="naam_lerling" id="naam_lerling" placeholder="naam_lerling"></td>
            <td><input type="text" name="afspraak_naam_persoon" id="afspraak_naam_persoon" placeholder="afspraak_naam_persoon"></td>
            <td><input type="text" name="afspraak_plaats" id="afspraak_plaats" placeholder="afspraak_plaats"></td>
            <td><input type="text" name="afspraak_voor" id="afspraak_voor" placeholder="afspraak_voor"></td>
            <td><input type="date" name="datum" id="datum" placeholder="datum"></td>
            <td><input type="text" name="tijd" id="tijd" placeholder="tijd"></td>
            <td><input type="text" name="contact_manier" id="contact_manier" placeholder="contact_manier"></td>
            <td><input type="submit"  id="add" name="add" value="add">
        </form>
    </tr>   
    <?php
        $conn = connect(2);
        $return = $conn->query("SELECT * From afspraken");
        $data = $return ->fetchall(PDO::FETCH_ASSOC);
        for($i=0;$i<sizeof($data);$i++){
            echo "<tr>";
            foreach($data[$i] as $x=>$y){
                echo "<td>" . $y . "</td>";
            }
            echo "<td></td>";
            echo "</tr>";
        }
        echo "<table>";
        stop_connect(2);
    ?>



    
</body>
</html>