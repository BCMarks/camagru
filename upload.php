<?php
    include("hf.php");
    require 'src/claviska/SimpleImage.php';
    if($_SESSION['user'] == "" || !isset($_SESSION['user']))
        header('Location: login.php');
    try
    {
        $sql = 'SELECT * FROM `db_camagru`.`images` JOIN `db_camagru`.`users` ON `db_camagru`.`images`.`user_id` = `db_camagru`.`users`.`user_id` WHERE `db_camagru`.`users`.`username`=:uname ORDER BY `img_id`';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':uname', $_SESSION['user']);
        $sth->execute();
        $uploads = $sth->fetchAll();
    }
    catch (PDOException $e)
    {
        echo "Connection failed: ".$e->getMessage();
    }

if(isset($_POST['submit']))
{
    if($_POST['submit'] == "UPLOAD")
    {
        if($_POST['sup'] != "" && $_POST['last'] == 1)
        {
            $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':uname', $_SESSION['user']);
            $sth->execute();
            $user = $sth->fetchAll();
            
            $sql = 'INSERT INTO `db_camagru`.`images` (`user_id`) VALUES (:uid);';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':uid', $user[0]['user_id']);
            $sth->execute();
            
            $sql = 'SELECT * FROM `db_camagru`.`images` ORDER BY `img_id` DESC LIMIT 1';
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $latest = $sth->fetchAll();
            
            $file = "user_img_uploads/".$latest[0]['img_id'].".jpg";
            move_uploaded_file($_FILES["img"]["tmp_name"], "tmp/tmp.png");
            
            $image = new \claviska\SimpleImage();

            $image
            ->fromFile('tmp/tmp.png')
            ->autoOrient()                        // adjust orientation based on exif data
            ->resize(1280, 720)
            ->overlay($_POST['sup'], 'bottom right') // add a watermark image
            ->toFile($file, 'image/jpeg');

        }
        else if($_POST['sup'] != "" && $_POST["last"] == 2)
        {
            $sql = 'SELECT * FROM `db_camagru`.`users` WHERE `username`=:uname';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':uname', $_SESSION['user']);
            $sth->execute();
            $user = $sth->fetchAll();
            
            $sql = 'INSERT INTO `db_camagru`.`images` (`user_id`) VALUES (:uid);';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':uid', $user[0]['user_id']);
            $sth->execute();
            
            $sql = 'SELECT * FROM `db_camagru`.`images` ORDER BY `img_id` DESC LIMIT 1';
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $latest = $sth->fetchAll();
            
            $file = "user_img_uploads/".$latest[0]['img_id'].".jpg";
            $ifp = fopen('tmp/tmp.png', 'wb'); 
            $data = explode(',', $_POST['wimg']);
            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);
            
            $image = new \claviska\SimpleImage();

            $image
            ->fromFile('tmp/tmp.png')
            ->flip('x')
            ->autoOrient()                        // adjust orientation based on exif data
            ->resize(1280, 720)
            ->overlay($_POST['sup'], 'bottom right') // add a watermark image
            ->toFile($file, 'image/jpeg');
        }
        header('Location: upload.php');
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Upload</title>
    </head>
    <body>
        <script type="text/javascript" src="cam.js"></script>
        <div id="up_midl" align="center">
                <video id="cam" class="webcam" autoplay poster="img/cam.jpg"></video>
                <img src="clipart/blank.png" id="disp">
                <img src="clipart/blank.png" id="overl">
                <img src="clipart/black.png" id="blak">
                <div id="candiv">
                    <canvas id="cnvs"></canvas>
                </div>
            <div class="controller">
                <button id="snap" onclick="snape();lastw();enablebutt();" title="DOUBLE CLICK TO CAPTURE" disabled>Capture</button>
                <h6>OR</h6>
            </div>
            <script>
                function enablebutt(){
                    var butt = document.getElementById('upbutt');
                    var d1 = document.getElementById('wimg').value;
                    var d2 = document.getElementById('filo').value;
                    var d3 = document.getElementsByClassName('rad');
                    var i;
                    var yes = 0;

                    for(i = 0; i < d3.length; i++)
                    {
                        if(d3[i].checked == true)
                            yes = 1;
                    }

                    if((d1 != "" || d2 != "") && yes == 1)
                        butt.removeAttribute("disabled");
                }

                function lastw(){
                    document.getElementById('last').value = 2;
                }

                function lastf(){
                    document.getElementById('last').value = 1;
                }

                function displaythumb(e){
                    document.querySelector('#overl').setAttribute('src', e);
                }

                function displayImage(e){
                    if (e.files[0]) {
                        var reader = new FileReader();
                        var canvas = document.getElementById('cnvs');
                        var ctx = canvas.getContext('2d');
                        document.getElementById('blak').style.display = "block";

                        reader.onload = function(e) {
                            document.querySelector('#disp').setAttribute('src', e.target.result);
                        }
                        reader.readAsDataURL(e.files[0]);
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    }
                }

            </script>
            <form style="display:inline-block" enctype="multipart/form-data" name="upload.php"  method="POST">
                <input id="filo" type="file" accept="image/*" name="img" onChange="displayImage(this);lastf();enablebutt();"/>
                <input type="hidden" id="wimg" name="wimg"/>
                <input type="hidden" id="last" name="last"/>
                <br />
                <label>
                    <input class="rad" type="radio" name="sup" value="clipart/Frank.png" onChange="displaythumb(this.value);enablebutt();">
                    <img class="thumb2" src="clipart/Frank.png">
                </label>
                <label>
                    <input class="rad" type="radio" name="sup" value="clipart/swiper.png" onChange="displaythumb(this.value);enablebutt();">
                    <img class="thumb2" src="clipart/swiper.png">
                </label>
                <label>
                    <input class="rad" type="radio" name="sup" value="clipart/logo.png" onChange="displaythumb(this.value);enablebutt();">
                    <img class="thumb2" src="clipart/logo.png">
                </label>
                <br />
			    <input id="upbutt" type="submit" name="submit" value="UPLOAD" disabled/>
            </form>
        </div>
        <div id="up_midr" align="center">
            <?php
                foreach ($uploads as $i => $v)
                {
                    ?>
                    <a href="image.php?id=<?php echo $v['img_id']?>">
                       <img class="thumb" src=<?php echo "user_img_uploads/".$v['img_id'].".jpg"?> />
                    </a>
                    <?php
                }
            ?>
        </div>
    </body>
</html>