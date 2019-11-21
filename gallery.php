<?php
    include("hf.php");
    try
    {
        $sql = 'SELECT * FROM `db_camagru`.`images` ORDER BY `img_id` DESC';
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $uploads = $sth->fetchAll();
        $imgcnt = count($uploads);
        $pmax = ceil($imgcnt / 5);
    }
    catch (PDOException $e)
    {
        echo "Connection failed: ".$e->getMessage();
    }
    if($imgcnt == 0)
        header('Location: empty.php');
    else if(!isset($_GET['p']) || $_GET['p'] < 1 || $_GET['p'] > $pmax || !ctype_digit($_GET['p']))
        header('Location: gallery.php?p=1');
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="camagru.css">
        <title>Camagru - Gallery</title>
    </head>
    <body>
        <div id="gal_mid">
            <?php
            if($_GET['p'] == 1)
            {?>
                <div></div>
            <?php
            }
            else
            {?>
                <div>
                    <a href="gallery.php?p=1">
                        <img src="img/start.png" id="pyl" alt="first" title="FIRST">
                    </a>
                </div>
            <?php }
            if($_GET['p'] < 2)
            {?>
                <div></div>
            <?php
            }
            else
            {?>
                <div>
                    <a href="gallery.php?p=<?php echo $_GET['p'] - 1;?>">
                        <img src="img/prev.png" id="pyl" alt="prev" title="PREV">
                    </a>
                </div>
            <?php }?>
                <div style="font-size:16px">
                    Page <?php echo $_GET['p'] ?> 
                    <br />
                    of <?php echo $pmax ?>
                </div>
            <?php
            if($_GET['p'] > $pmax - 1)
            {?>
                <div></div>
            <?php
            }
            else
            {?>
                <div>
                    <a href="gallery.php?p=<?php echo $_GET['p'] + 1;?>">
                        <img src="img/next.png" id="pyl" alt="next" title="NEXT">
                    </a>
                </div>
            <?php }
            if($_GET['p'] == $pmax)
            {?>
                <div></div>
            <?php
            }
            else
            {?>
                <div>
                    <a href="gallery.php?p=<?php echo $pmax;?>">
                        <img src="img/last.png" id="pyl" alt="last" title="LAST">
                    </a>
                </div>
            <?php }?>
            <div></div>
            <div></div>
            <div id="pics">
                <?php
                foreach ($uploads as $i => $v)
                {
                    if($i < 5 * $_GET['p'] && $i >= 5 * ($_GET['p'] - 1))
                    {
                    ?>
                        <a href="image.php?id=<?php echo $v['img_id']?>">
                            <img id="gal_img" src=<?php echo "user_img_uploads/".$v['img_id'].".jpg"?>>
                        </a>
                    <br />
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>