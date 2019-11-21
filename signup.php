<?php
    include("hf.php");
    if(isset($_POST['submit']))
    {
        if($_POST['submit'] == "OK")
        {
            #check for dup email or username
            try
            {
                $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname OR `email`=:email';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':uname', $_POST['uname']);
                $sth->bindParam(':email', $_POST['email']);
                $sth->execute();
                $data = $sth->fetchAll();
                if(count($data) != 0)
                {
                    echo "<script type='text/javascript'>alert('Account already in use.');</script>";
                }
                else
                {
                    if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['uname']))
                    {
                        if(strlen($_POST['uname']) > 5 && strlen($_POST['uname']) < 13)
                        {	
                            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                            {
                                $uppercase = preg_match('@[A-Z]@', $_POST['passwd']);
                                $lowercase = preg_match('@[a-z]@', $_POST['passwd']);
                                $number = preg_match('@[0-9]@', $_POST['passwd']);
                                $specialChars = preg_match('@[^\w]@', $_POST['passwd']);
                                if($uppercase && $lowercase && $number && $specialChars && strlen($_POST['passwd']) > 7)
                                {
                                    $p = hash('whirlpool',$_POST['passwd'].$_POST['uname']);
                                    $sql = 'INSERT INTO `db_camagru`.`users` (`username`, `passwd`, `email`) VALUES (:uname, :passwd, :email);';
                                    $sth = $dbh->prepare($sql);
                                    $sth->bindParam(':uname', $_POST['uname']);
                                    $sth->bindParam(':passwd', $p);
                                    $sth->bindParam(':email', $_POST['email']);
                                    $sth->execute();
                                    #send email. fix any format issues later.
                                    $to = $_POST['email'];
                                    $headers = 'From:no-reply@camagru.com'."\r\n"."Content-type: text/html; charset=utf-8";
                                    $subject = 'Camagru Verification';
                                    $message = "<html>
                                    <body>
                                        <p>Thanks for signing up for a Camagru account!</p>
                                        <br />
                                        <p>Please follow this link to activate your account before logging in:</p>
                                        <a href='localhost/camagru/activate.php?a=".$p."'>Account verification</a>
                                        <br />
                                        <br />
                                        <p>If you didn't sign up, just ignore this email and nothing will happen.</p>
                                    </body>
                                </html>";
                                    mail($to, $subject, $message, $headers);
                                    header('Location: verify.php');
                                }
                                else
                                    echo "<script type='text/javascript'>alert('Password should be at least 8 characters in length and should include at least one lowercase letter, one uppercase letter, one digit, and one special character.');</script>";	
                            }
                            else
                                echo "<script type='text/javascript'>alert('Please enter a valid email address.');</script>";
                        }
                        else
                            echo "<script type='text/javascript'>alert('Username must be between 6 and 12 characters.');</script>";
                    }
                    else 
                        echo "<script type='text/javascript'>alert('Username should only contain alphanumeric characters.');</script>";
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
        <title>Camagru - Sign up</title>
    </head>
    <body>
        <div id="form_mid" align="center">
            <form id="formo" name="signup.php"  method="POST">
                <input type="text" name="email" autocomplete="Email" placeholder="Email" required/>
			    <br />
			    <input type="text" name="uname" autocomplete="Username" placeholder="Username" required/>
			    <br />
			    <input type="password" name="passwd" autocomplete="Password" placeholder="Password" required/>
			    <br />
			    <input type="submit" name="submit" value="OK" />
			</form>
        </div>
    </body>
</html>