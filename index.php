
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>

 <title>Interactive Computer Music</title>

 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <!-- midi.js package -->
 <script src="./js/MIDI/AudioDetect.js" type="text/javascript"></script>
 <script src="./js/MIDI/LoadPlugin.js" type="text/javascript"></script>
 <script src="./js/MIDI/Plugin.js" type="text/javascript"></script>
 <script src="./js/MIDI/Player.js" type="text/javascript"></script>
 <script src="./js/Window/DOMLoader.XMLHttp.js" type="text/javascript"></script>
 <script src="./js/Window/DOMLoader.script.js" type="text/javascript"></script>
 <!-- extras -->
 <script src="./inc/Base64.js" type="text/javascript"></script>
 <script src="./inc/base64binary.js" type="text/javascript"></script>
 <!--Vexflow-->
 <script src="js/vexflow.js"></script>
 <script src="bootstrap/js/bootstrap.min.js"></script>

 <script src="js/jquery-1.6.2.min.js"></script>

 <link href="style.css" rel="stylesheet" type="text/css">

 <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
 <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

</head>


<body bgcolor="#D3D3D3">

<?php
$user_id = rand();
?>


<div class="navbar-wrapper"><!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
<div class="container">
<div class="navbar navbar-inverse">
<div class="navbar-inner"> 
	<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="brand" href="#">Computer Music</a> 
</div>
</div>
</div>
</div>

<div class="container">
<div class="row-fluid">
<div class="span12  offsetHalf block">
<div class="hero-unit span12">
  <br><br>
  <font color="black">

  	<p>Hello, and welcome to MusicTrainer [come up with better name], a fun way to learn sight reading. 
  	You need zero music knowledge to try it out! In fact, we believe that through the simple act of playing this game, you
  	will learn the basics of music theory. </p>

  	<p>Since this is the beta version of the app and because we wish to collect data regarding whether people can actually learn
  		music theory by using this tool, we ask you to please fill out a <i>very short</i> music theory quiz before and after
  		using this app. Enjoy! </p>

  	<br>

  	<form action="/gdform.php" method="post"> 
		<input type="hidden" name="subject" value="Prequiz<?= $user_id ?>" /> 
		<input type="hidden" name="redirect" value="ComputerMusic/game.php?user_id=<?= $user_id ?>" />
		<p>Which of the following notes is a third above G?<br>
			<input type="radio" name="one" value="1wrongA">G<br>
			<input type="radio" name="one" value="1wrongB">A<br>
			<input type="radio" name="one" value="1rightC">B<br>
			<input type="radio" name="one" value="1wrongD">C<br>
		</p>
		<p>Which notes make up a C major chord?<br>
			<input type="radio" name="two" value="2wrongA">C,D,E<br>
			<input type="radio" name="two" value="2wrongB">C<br>
			<input type="radio" name="two" value="2rightC">C,E,G<br>
			<input type="radio" name="two" value="2wrongD">A,B,C<br>
		</p>
		<input type="submit" name="submit" value="Continue"/>
	</form>


  </font>
</div>
</div>
</div>
</div>


</body>
</html>