<?php
    include("hf.php");
    try
    {
        $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `passwd`=:passwd';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':passwd', $_GET['a']);
        $sth->execute();
        $data = $sth->fetchAll();
        if(count($data) != 1)
        {
            header('Location: index.php');
        }
        else
        {
            $sql = 'UPDATE `db_camagru`.`users` SET `active`=1 WHERE `passwd`=:passwd';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':passwd', $_GET['a']);
            $sth->execute();
        }
    }
    catch (PDOException $e)
    {
        echo "<script type='text/javascript'>alert('Connection failed: ".$e->getMessage()."');</script>";
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Activate</title>
    </head>
    <body>
        <div id="home_mid" align="center">
            Account succesfully activated!
            <br />
            You may now log in with your username and password.
        </div>
    </body>
</html>