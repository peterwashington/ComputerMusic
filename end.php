
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
  <br>
  <font color="black">

  	<p>Thanks for playing. Please fill out the same quiz you took at the beginning of the experiment so that we can see if your 
  		basic musical understanding has improved through playing this game! </p>
 
	<?php
	$user_id = $_GET['user_id'];
	$score = $_GET['score'];
	?>

  	<br>

  	<form action="/gdform.php" method="post"> 
		<input type="hidden" name="subject" value="AFTERquiz<?= $user_id ?>" /> 
		<input type="hidden" name="redirect" value="ComputerMusic/thanks.php?user_id=<?= $user_id ?>&score=<?= $score ?>" />
		<p>1) Which of the following notes is a third above G?<br>
			<input type="radio" name="one" value="1wrongA">G<br>
			<input type="radio" name="one" value="1wrongB">A<br>
			<input type="radio" name="one" value="1rightC">B<br>
			<input type="radio" name="one" value="1wrongD">C<br>
		</p>
		<canvas id="questionOne" width=700 height=100></canvas>
		<script language="javascript">
			var canvas = $("#questionOne")[0];
			var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
			var ctx = renderer.getContext();
			var stave = new Vex.Flow.Stave(10, 0, 600);
			stave.addClef("treble").setContext(ctx).draw();
			// Create the notes
			var notes = [
			// A quarter-note C.
			new Vex.Flow.StaveNote({ keys: ["e/4"], duration: "q" }),

			// A quarter-note D.
			new Vex.Flow.StaveNote({ keys: ["b/5"], duration: "q" }),

			// A quarter-note rest. Note that the key (b/4) specifies the vertical
			// position of the rest.
			new Vex.Flow.StaveNote({ keys: ["b/4"], duration: "qr" }),

			// A C-Major chord.
			new Vex.Flow.StaveNote({ keys: ["c/4", "e/4", "g/4"], duration: "q" })
			];

			// Create a voice in 4/4
			var voice = new Vex.Flow.Voice({
			num_beats: 4,
			beat_value: 4,
			resolution: Vex.Flow.RESOLUTION
			});

			// Add notes to voice
			voice.addTickables(notes);

			// Format and justify the notes to 500 pixels
			var formatter = new Vex.Flow.Formatter().
			joinVoices([voice]).format([voice], 500);

			// Render voice
			voice.draw(ctx, stave);
		</script>
		<br>
		<p>2) What is the first note in the measure above? <br>
			<input type="radio" name="two" value="2wrongA">C<br>
			<input type="radio" name="two" value="2wrongB">D<br>
			<input type="radio" name="two" value="2rightC">E<br>
			<input type="radio" name="two" value="2wrongD">F<br>
		</p>
		<p>3) What is the second note in the measure above? <br>
			<input type="radio" name="three" value="3rightA">B<br>
			<input type="radio" name="three" value="3wrongB">D<br>
			<input type="radio" name="three" value="3wrongC">F<br>
			<input type="radio" name="three" value="3wrongD">A<br>
		</p>
		<p>4) What does the third symbol in the measure indicate? <br>
			<input type="radio" name="four" value="4wrongA">Play the previous note another time.<br>
			<input type="radio" name="four" value="4wrongB">Play the previous note, but louder.<br>
			<input type="radio" name="four" value="4wrongC">Play the previous note, but softer.<br>
			<input type="radio" name="four" value="4rightD">A rest, or pause in the music.<br>
		</p>
		<p>5) What notes make up the chord in the measure (the last set of notes)? <br>
			<input type="radio" name="five" value="5wrongA">A,B,C<br>
			<input type="radio" name="five" value="5wrongB">A,C,E<br>
			<input type="radio" name="five" value="5rightC">C,E,G<br>
			<input type="radio" name="five" value="5wrongD">F,A,C<br>
		</p>
		<input type="submit" name="submit" value="Submit!"/>
	</form>


  </font>
</div>
</div>
</div>
</div>


</body>
</html>