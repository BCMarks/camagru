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
            if($_POST['submit'] == "OK")
            {
                $uppercase = preg_match('@[A-Z]@', $_POST['npasswd']);
                $lowercase = preg_match('@[a-z]@', $_POST['npasswd']);
                $number = preg_match('@[0-9]@', $_POST['npasswd']);
                $specialChars = preg_match('@[^\w]@', $_POST['npasswd']);
                if($uppercase && $lowercase && $number && $specialChars && strlen($_POST['npasswd']) > 7)
                {
                    $sql = 'UPDATE `db_camagru`.`users` SET `passwd`=:npasswd WHERE `passwd`=:passwd';
                    $sth = $dbh->prepare($sql);
                    $sth->bindParam(':passwd', $_GET['a']);
                    $sth->bindParam(':npasswd', hash('whirlpool',$_POST['npasswd'].$data[0]['username']));
                    $sth->execute();
                    header('Location: login.php');
                }
                else
                    echo "<script type='text/javascript'>console.log('Password should be at least 8 characters in length and should include at least one lowercase letter, one uppercase letter, one digit, and one special character.');</script>";
            }
        }
    }
    catch (PDOException $e)
    {
        echo "Connection failed: ".$e->getMessage();
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Password Reset</title>
    </head>
    <body>
        <div id="form_mid" align="center">
            <form style="display:inline-block" name="pwreset.php"  method="POST">
                <input type="password" name="npasswd" autocomplete="New Password" placeholder="New Password" required/>
			    <br />
			    <input type="submit" name="submit" value="OK" />
			</form>
        </div>
    </body>
</html>