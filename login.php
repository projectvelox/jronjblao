<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
    <title>WAIS</title>
    <link rel="stylesheet" href="css/login.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<div class="container"><div class="info"></div></div>
<div class="form">
  <div class="thumbnail"><img src="images/logo.gif"/></div>
  <form class="login-form" action="validate.php" method="post">
      <input name="u" type="text" placeholder="Username" required />
      <input name="p" type="password" placeholder="Password" required/>
      <input class="submit-button" type="submit" value="Login">
  </form>
</div>
<video id="video" autoplay="autoplay" loop="loop" playbackRate="1" poster=""><source src="images/bike.mp4" type="video/mp4"/></video>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>   
</body>
</html>
