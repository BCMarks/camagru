<?php
    include("hf.php");
    if(isset($_POST['submit']))
    {
        if($_POST['submit'] == "OK")
        {
            try
            {
                $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `email`=:email';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':email', $_POST['email']);
                $sth->execute();
                $data = $sth->fetchAll();
                if(count($data) != 1)
                {
                    echo "<script type='text/javascript'>alert('Invalid email.');</script>";
                }
                else
                {
                    $to = $_POST['email'];
                    $headers = 'From:no-reply@camagru.com'."\r\n"."Content-type: text/html; charset=utf-8";
                    $subject = "Camagru Password Reset";
                    $message = "<html>
                    <body>
                        <p>A password reset has been requested for your Camagru account.</p>    
                        <br />
                        <p>Please follow this link to reset your password:</p>
                        <a href='localhost/camagru/pwreset.php?a=".$data[0]['passwd']."'>Password reset.</a>   
                        <br />
                        <br />
                        <p>If you didn't request this email, just ignore it and nothing will happen.</p>      
                    </body>
                    </html>";
                    mail($to, $subject, $message, $headers);
                    header('Location: forgot2.php');
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
        <title>Camagru - Forgot Password</title>
    </head>
    <body>
        <div id="form_mid" align="center">
            <form style="display:inline-block" name="forgot.php"  method="POST">
                <input type="text" name="email" placeholder="Email" required/>
			    <br />
			    <input type="submit" name="submit" value="OK" />
			</form>
        </div>
    </body>
</html>