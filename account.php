<?php
    include("hf.php");
    #
    # OK HOLD UP. ITS BETTER TO UPDATE INDIVIDUALLY FOR HASHING
    #
    if(isset($_POST['submit']))
    {
        if($_POST['submit'] == "SAVE")
        {
            if(isset($_POST['email']))
            {
                if($_POST['email'] != "")
                {
                    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `email`=:email';
                        $sth = $dbh->prepare($sql);
                        $sth->bindParam(':email', $_POST['email']);
                        $sth->execute();
                        $data = $sth->fetchAll();
                        if(count($data) != 0)
                        {
                            echo "<script type='text/javascript'>alert('Email already in use.');</script>";
                        }
                        else
                        {
                            $sql = 'UPDATE `db_camagru`.`users` SET `email`=:email WHERE `username`=:uname';
                            $sth = $dbh->prepare($sql);
                            $sth->bindParam(':email', $_POST['email']);
                            $sth->bindParam(':uname', $_SESSION['user']);
                            $sth->execute();
                        }
                    }
                    else
                            echo "<script type='text/javascript'>alert('Invalid email.');</script>";
                }
            }
            if(isset($_POST['uname']))
            {
                if($_POST['uname'] != "")
                {
                    if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['uname']))
                    {
                        if(strlen($_POST['uname']) > 5 && strlen($_POST['uname']) < 13)
                        {
                            $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname';
                            $sth = $dbh->prepare($sql);
                            $sth->bindParam(':uname', $_POST['uname']);
                            $sth->execute();
                            $data = $sth->fetchAll();
                            if(count($data) != 0)
                            {
                                echo "<script type='text/javascript'>alert('Username already in use.');</script>";
                            }
                            else
                            {
                                $sql = 'UPDATE `db_camagru`.`users` SET `username`=:uname WHERE `username`=:oldname';
                                $sth = $dbh->prepare($sql);
                                $sth->bindParam(':uname', $_POST['uname']);
                                $sth->bindParam(':oldname', $_SESSION['user']);
                                $sth->execute();
                                $_SESSION['user'] = $_POST['uname'];
                            }
                        }
                        else
                            echo "<script type='text/javascript'>alert('Username must be between 6 and 12 characters.');</script>";
                    }
                    else
                        echo "<script type='text/javascript'>alert('Username should only contain alphanumeric characters.');</script>";
                }
            }
            if(isset($_POST['passwd']))
            {
                if($_POST['passwd'] != "")
                {
                    $uppercase = preg_match('@[A-Z]@', $_POST['passwd']);
                    $lowercase = preg_match('@[a-z]@', $_POST['passwd']);
                    $number = preg_match('@[0-9]@', $_POST['passwd']);
                    $specialChars = preg_match('@[^\w]@', $_POST['passwd']);
                    if($uppercase && $lowercase && $number && $specialChars && strlen($_POST['passwd']) > 7)
                    {
                        $p = hash('whirlpool',$_POST['passwd'].$_SESSION['user']);
                        $sql = 'UPDATE `db_camagru`.`users` SET `passwd`=:passwd WHERE `username`=:uname';
                        $sth = $dbh->prepare($sql);
                        $sth->bindParam(':passwd', $p);
                        $sth->bindParam(':uname', $_SESSION['user']);
                        $sth->execute();
                    }
                    else
                        echo "<script type='text/javascript'>alert('Password should be at least 8 characters in length and should include at least one lowercase letter, one uppercase letter, one digit, and one special character.');</script>";
                }
            }
            if(isset($_POST['notif']))
            {
                if($_POST['notif'] != "")
                {
                        $sql = 'UPDATE `db_camagru`.`users` SET `e_notif`=:notif WHERE `username`=:uname';
                        $sth = $dbh->prepare($sql);
                        $sth->bindParam(':notif', $_POST['notif']);
                        $sth->bindParam(':uname', $_SESSION['user']);
                        $sth->execute();
                }
            }
        }
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Account</title>
    </head>
    <body>
        <div id="home_mid" align="center">
            <h4>Account Management</h4>
            <?php
            if(!isset($_POST['opt']))
            {?>
                <form id="formo" name="account.php" method="POST">
                    <select name="opt">
                        <option value="1">Change email</option>
                        <option value="2">Change username</option>
                        <option value="3">Change password</option>
                        <option value="4">Enable/Disable notifications</option>
                    </select>
                    <input type="submit" name="GO" value="GO" />
                </form>
            <?php
            }
            else if($_POST['opt'] == 1)
            {?>
                <form id="formo" name="account.php" method="POST">
                    <input type="text" name="email" autocomplete="Email" placeholder="Email"/>
			        <br />
			        <input type="submit" name="submit" value="SAVE" />
			    </form>
            <?php
            }
            else if($_POST['opt'] == 2)
            {?>
                <form id="formo" name="account.php" method="POST">
			        <input type="text" name="uname" autocomplete="Username" placeholder="Username"/>
			        <br />
			        <input type="submit" name="submit" value="SAVE" />
			    </form>
            <?php
            }
            else if($_POST['opt'] == 3)
            {?>
                <form id="formo" name="account.php" method="POST">
			        <input type="password" name="passwd" autocomplete="Password" placeholder="Password"/>
			        <br />
			        <input type="submit" name="submit" value="SAVE" />
			    </form>
            <?php
            }
            else if($_POST['opt'] == 4)
            {?>
                <form id="formo" name="account.php" method="POST">
                    Do you want to receive email notifications?
                    <select name="notif">
                        <option value="1">YES</option>
                        <option value="0">NO</option>
                    </select>
                    <br />
			        <input type="submit" name="submit" value="SAVE" />
                </form>
            <?php
            }?>
        </div>
    </body>
</html>