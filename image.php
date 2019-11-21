<?php
    include("hf.php");
    if(!isset($_GET['id']) || !ctype_digit($_GET['id']))
        header('Location: gallery.php?p=1');
    try
    {
        $sql = 'SELECT * FROM `db_camagru`.`images` WHERE `img_id`=:imgid';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':imgid', $_GET['id']);
        $sth->execute();
        $uploads = $sth->fetchAll();
        
        $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':uname', $_SESSION['user']);
        $sth->execute();
        $poster = $sth->fetchAll();
        
        $sql = 'SELECT * FROM `db_camagru`.`users` JOIN `db_camagru`.`images` ON `db_camagru`.`users`.`user_id` = `db_camagru`.`images`.`user_id` WHERE `db_camagru`.`images`.`img_id`=:imgid';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':imgid', $_GET['id']);
        $sth->execute();
        $author = $sth->fetchAll();

        $sql = 'SELECT * FROM `db_camagru`.`likes` JOIN `db_camagru`.`users` ON `db_camagru`.`users`.`user_id` = `db_camagru`.`likes`.`liker_id` WHERE `db_camagru`.`likes`.`img_id`=:imgid';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':imgid', $_GET['id']);
        $sth->execute();
        $likers = $sth->fetchAll(); 
        #total likes
        $like = "LIKE";
        foreach ($likers as $i => $v)
        {
            if($v['username'] == $_SESSION['user'])
                $like = "UNLIKE";
        }
        
    }
    catch (PDOException $e)
    {
        echo "<script type='text/javascript'>alert('Connection failed: ".$e->getMessage()."');</script>";
    }
    if(isset($_POST['submit']))
    {
        if($_POST['submit'] == "Post" && isset($_POST['cmnt']))
        {
            $clean = htmlspecialchars($_POST['cmnt']);
            try
            {
                $sql = 'INSERT INTO `db_camagru`.`comments` (`img_id`, `poster_id`, `content`) VALUES (:imgid, :pid, :cmnt);';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':imgid', $_GET['id']);
                $sth->bindParam(':pid', $poster[0]['user_id']);
                $sth->bindParam(':cmnt', $clean);
                $sth->execute();
                if($author[0]['e_notif'] == 1)
                {
                    $to = $author[0]['email'];
                    $headers = 'From:no-reply@camagru.com'."\r\n"."Content-type: text/html; charset=utf-8";
                    $subject = 'New comment received';
                    $message = "
                    <html>
                        <body>
                            <p>Hi, ".$author[0]['username']."!</p>
                            <p>Your <span><a href='localhost/camagru/image.php?id=".$_GET['id']."'>upload</a></span> has received a new comment.</p>
                        </body>
                    </html>";
                    mail($to, $subject, $message, $headers);
                }
            }
            catch (PDOException $e)
            {
                echo "<script type='text/javascript'>alert('Connection failed: ".$e->getMessage()."');</script>";
            }
        }
        if($_POST['submit'] == "LIKE")
        {
            $sql = 'INSERT INTO `db_camagru`.`likes` (`img_id`, `liker_id`) VALUES (:imgid, :lid);';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':imgid', $_GET['id']);
            $sth->bindParam(':lid', $poster[0]['user_id']);
            $sth->execute();
            header('Location: image.php?id='.$_GET['id']);
        }
        if($_POST['submit'] == "UNLIKE")
        {
            $sql = 'DELETE FROM `db_camagru`.`likes` WHERE `img_id`=:imgid AND `liker_id`=:lid;';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':imgid', $_GET['id']);
            $sth->bindParam(':lid', $poster[0]['user_id']);
            $sth->execute();
            header('Location: image.php?id='.$_GET['id']);
        }
        if($_POST['submit'] == "CONFIRM")
        {
            try
            {
                $sql = 'DELETE FROM `db_camagru`.`images` WHERE `img_id`=:imgid';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':imgid', $_GET['id']);
                $sth->execute();
                header('Location: image.php?id='.$_GET['id']);
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
        <title>Image</title>
    </head>
    <body>
        <div id="up_midl" align="center">
        <?php
        if(count($uploads) != 1)
        {
            header('Location: gallery.php?p=1');
        }
        else
        {
            try
            { 
                $sql = 'SELECT * FROM `db_camagru`.`comments` JOIN `db_camagru`.`images` ON `db_camagru`.`comments`.`img_id` = `db_camagru`.`images`.`img_id` WHERE `db_camagru`.`images`.`img_id`=:imgid ORDER BY `comment_id` DESC LIMIT 5 ;';
                $sth = $dbh->prepare($sql);
                $sth->bindParam(':imgid', $_GET['id']);
                $sth->execute();
                $comments = $sth->fetchAll();
            }
            catch (PDOException $e)
            {
                echo "<script type='text/javascript'>alert('Connection failed: ".$e->getMessage()."');</script>";
            }
            if(isset($_POST['submit']))
            {
                if($_SESSION['user'] == $author[0]['username'] && $_POST['submit'] != "DELETE")
                {?>
                <form id="formo" name="image.php?id=<?php echo $_GET['id']?>"  method="POST">
                    <input type="submit" name="submit" value="DELETE" />
                </form>
                <?php
                }
                if($_POST['submit'] == "DELETE")
                {?>
                <form id="formo" name="image.php?id=<?php echo $_GET['id']?>"  method="POST">
                    <input type="submit" name="submit" value="CONFIRM" />
                </form>
                <?php
                }
            }
            else
            {
                if($_SESSION['user'] == $author[0]['username'])
                {?>
                <form id="formo" name="image.php?id=<?php echo $_GET['id']?>"  method="POST">
                    <input type="submit" name="submit" value="DELETE" />
                </form>
                <?php
                }
            }
            ?>
            <br />
            <img id="full_img" src=<?php echo "user_img_uploads/".$_GET['id'].".jpg"?>>
            <p>Uploader: <?php echo $author[0]['username']?> --- Likes: <?php echo count($likers)?></p>
        <?php
        }
        ?>
        </div>
        <div id="up_midr2" align="center">
            <div>
                <a href="https://twitter.com/intent/tweet?text=THIS+PICTURE+ON+CAMAGRU+IS+AWESOME:
                http://localhost/camagru/image.php?id=<?php echo $_GET['id']?>" data-share="twitter" title="Twitter" target="_blank">
                    <img alt="tweet" width="40px"src="clipart/twitter.png">
                </a>
            </div>
            <div>
            <?php
            if($_SESSION['user'] != "")
            {
                
            ?>
            <form id="formo" name="image.php?id=<?php echo $_GET['id']?>"  method="POST">
			    <input type="submit" name="submit" value=<?php echo $like?> />
			</form>
            <br />
            <form id="formo" autocomplete="off" name="image.php?id=<?php echo $_GET['id']?>"  method="POST">
                <textarea name="cmnt" maxlength="42" style="resize:none" placeholder="Leave a comment..." required></textarea>
			    <input type="submit" name="submit" value="Post" />
			</form>
            <?php
            }
            else
            {?>
               <p>Log in to like or leave a comment.</p>
            <?php
            }?>
            </div>
        </div>
        <div id="comments">
            <div>
                <h3>Latest comments:</h3>
            </div>
                <?php
                foreach ($comments as $i => $v)
                {
                    $sql = 'SELECT * FROM `db_camagru`.`users` JOIN `db_camagru`.`comments` ON `db_camagru`.`users`.`user_id` = `db_camagru`.`comments`.`poster_id` WHERE `db_camagru`.`users`.`user_id`=:posterid';
                    $sth = $dbh->prepare($sql);
                    $sth->bindParam(':posterid', $v['poster_id']);
                    $sth->execute();
                    $poster = $sth->fetchAll();
                    ?>
            <div>
                    <h4><?php echo $poster[0]['username']?>:</h4>
                    <p style="word-wrap: break-word"><?php echo $v['content']?></p>
                    <br />
                </div>
                    <?php
                }
                ?>
        </div>
    </body>
</html>