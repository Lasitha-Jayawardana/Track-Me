<?php session_start() ?>
<?php
    if (empty($_SESSION["UserID"]) or empty($_SESSION["NameF"])){
        session_unset();
 session_destroy  ();
    header('location:index.html');

       }

       ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>About Us</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="css/main.css" />
    </head>
    <body>

        <!-- Wrapper -->
            <div id="wrapper" style="background-image: url(Image/bg.jpg)">

                <!-- Header -->
                    <header id="header">
                        <span class="avatar"><img src="Image/Main icon.png" alt="" /></span>
                        <h1>This is <strong>SLTC Shuttle Tracking System </strong> is designed by <strong><?php echo $_SESSION["NameF"]; ?> </strong><br />
                        and released for free for all SLTC Undergaduates & SLTC Staff.</h1>


                        <ul class="icons">

                            <li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
                            <li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>

                            <li><a href="mailto:lasithaj@sltc.edu.lk?body=Please send me a copy of your new program!" class="icon style2 fa-envelope-o"><span class="label">Email</span></a></li>
                        </ul>
                    </header>



                <!-- Footer -->
                    <footer id="footer">
                      <p> Copyright &copy 2019 by Lasitha. All rights reserved.</p>
                    </footer>

            </div>

        <!-- Scripts -->
            <script src="js/jquery.min.js"></script>
            <script src="js/jquery.poptrox.min.js"></script>
            <script src="js/skel.min.js"></script>
            <script src="js/main.js"></script>

    </body>
</html>