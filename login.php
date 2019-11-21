<?php
    include("hf.php");
    if(isset($_POST['submit']))
    {
        if($_POST['submit'] == "OK")
        {
            try
            {
                $p = hash('whirlpool',$_POST['passwd'].$_POST['uname']);
                $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname AND `passwd`=:passwd';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':uname', $_POST['uname']);
                $sth->bindParam(':passwd', $p);
                $sth->execute();
                $data = $sth->fetchAll();
                if(count($data) != 1)
                {
                    echo "<script type='text/javascript'>alert('Username and password do not match!');</script>";
                }
                else if($data[0]['active'] == "1")
                {
                    $_SESSION['user'] = $data[0]['username'];
                    header('Location: index.php');
                }
                else if($data[0]['active'] == "0")
                {
                    header('Location: verify.php');
                }
            }
            catch (PDOException $e)
            {
                echo "<script type='text/javascript'>alert('Connection failed: ".$e->getMessage()."');</script>";
            }
        }
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Log in</title>
    </head>
    <body>
        <div id="form_mid" align="center">
            <form id="formo" name="login.php"  method="POST">
			    <input type="text" name="uname" autocomplete="Username" placeholder="Username" required/>
			    <br />
			    <input type="password" name="passwd" autocomplete="Password" placeholder="Password" required/>
			    <br />
			    <input type="submit" name="submit" value="OK" />
			</form>
            <br />
            <a id="lin" href="signup.php"><strong>Create new account?</strong></a>
            <br />
            <a id="lin" href="forgot.php"><strong>Forgot password?</strong></a>
        </div>
    </body>
</html>