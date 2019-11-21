<?php
    include_once("config/setup.php");
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="camagru.css">
    </head>
    <body>
        <div id="header">
            <div id="tabs">
                <a id="tlink" href="index.php"><h4 id="toptxt">HOME</h4></a>
            </div>
            <div id="tabs">
                <a id="tlink" href="gallery.php?p=1"><h4 id="toptxt">GALLERY</h4></a>
            </div>
            <div id="tabs">
                <a id="tlink" href="upload.php"><h4 id="toptxt">UPLOAD</h4></a>
            </div>
            <?php
            if(isset($_SESSION['user']))
            {
                if($_SESSION['user'] != "")
                {
                ?>
                <div id="tabs">
                    <a id="tlink" href="account.php"><h4 id="toptxt">ACCOUNT</h4></a>
                </div>
                <?php
                }
                else{?>
                    <div id="tabs">
                        <a id="tlink" href="login.php"><h4 id="toptxt">LOG IN</h4></a>
                    </div>
                    <?php
                }
            }
            else{?>
            <div id="tabs">
                <a id="tlink" href="login.php"><h4 id="toptxt">LOG IN</h4></a>
            </div>
            <?php
            }
            if(isset($_SESSION['user']))
            {
                if($_SESSION['user'] != ""){?>
                <div id="tabs">
                    <a id="tlink" href="logout.php"><h4 id="toptxt">LOG OUT</h4></a>
                </div>
                <?php
                }
            }?>
        </div>
        <div id="footer" align="center">
            <hr noshade>
            <p>&copy; bmarks 2019</p>
            <a href="https://github.com/BCMarks" target="_blank">Github</a>
            <a href="https://www.linkedin.com/in/bradleigh-marks-aa289913a/" target="_blank">LinkedIn</a>
            <hr noshade>
        </div>
    </body>
</html>