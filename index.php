
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>

 <title>Interactive Computer Music - Share your score</title>

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
  <br>
  <font color="black">

  	<p>Hello, and welcome to MusicTrainer, a fun way to learn sight reading. 
  	You need zero music knowledge to try it out! In fact, we believe that through the simple act of playing this game, you
  	will learn the basics of music theory. </p>

  	<p>Since this is the beta version of the app and because we wish to collect data regarding whether people can actually learn
  		music theory by using this tool, we ask you to please fill out a <i>very short</i> music theory quiz before and after
  		using this app. Enjoy! </p>

  	<br>

  	<form action="/gdform.php" method="post"> 
		<input type="hidden" name="subject" value="PREquiz<?= $user_id ?>" /> 
		<input type="hidden" name="redirect" value="ComputerMusic/game.php?user_id=<?= $user_id ?>" />
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

		<p>
			<br>
			<b>Study Info</b>
			<br>
			<font size="2" style="line-height:1.2em;">
				<b>Study Title:</b> Computational Methods for Music Pedagogy<br>
				<b>Primary Investigator:</b> Peter Washington, 6320 Main St., Houston, TX, peterwashington@rice.edu<br>
				<b>Principal Investigator (Faculty Advisor):</b> Dr. Lin Zhong, 6100 Main St., Houston, TX, 713-348-4163, 
				lzhong@rice.edu<br>
				<b>Purpose of the study:</b> The purpose of this study is to attempt to show that a user with little to no prior knowledge of music theory can learn basic musical concepts (primarily the ability to sight read) through teaching a computer and through computerized gamification.
				<br>
				<b>Procedures:</b> 1) Participant enters website.
					2) Participant takes a short music theory quiz.
					3) Participant plays the music theory game.
					4) Participant takes exit survey (another short music theory quiz).
					5) Information is stored regarding the user’s performance on the game, without storing any personal information about the user. The participant does not provide any personal information, but may opt to share the results of the study on Facebook or via email. If this is the case, the Facebook info and email address of the participant will not be recorded or known to the researchers.
				<br>
				<b>Participant Requirements:</b> In order to be included in this study, a participant must complete the initial quiz, spend some time playing the music theory game, and complete the exit survey. The participant does not provide any personal information, but may opt to share the results of the study on Facebook or via email. If this is the case, the Facebook info and email address of the participant will not be recorded or known to the researchers.
				<br>
				<b>Risks:</b> The risks and discomfort associated with participation in this study are no greater than those ordinarily encountered in daily life.
				<br>
				<b>Benefits:</b> There may be no personal benefit from your participation in the study but the knowledge received may be of value to humanity.
				<br>
				<b>Confidentiality:</b> By participating in the study, you understand and agree that Rice University may be required to disclose your consent form, data and other personally identifiable information as required by law, regulation, subpoena or court order.  Otherwise, your confidentiality will be maintained in the following manner: Your data and consent form will be kept separate. Your consent form will be stored in a locked location on Rice University property and will not be disclosed to third parties. By participating, you understand and agree that the data and information gathered during this study may be used by Rice University and published and/or disclosed by Rice University to others outside of Rice University.  However, your name, address, contact information and other direct personal identifiers in your consent form will not be mentioned by Rice University in any such publication or dissemination of the research data and/or results.
				<br>
				<b>Rights:</b> Your participation is voluntary.  You are free to stop your participation at any point.  Refusal to participate or withdrawal of your consent or discontinued participation in the study will not result in any penalty or loss of benefits or rights to which you might otherwise be entitled.  The Principal Investigator may at his/her discretion remove you from the study for any of a number of reasons.  In such an event, you will not suffer any penalty or loss of benefits or rights which you might otherwise be entitled.
				<br>
				<b>Rights to Ask Questions and Contact Information:</b> If you have questions about this study, desire additional information, or wish to withdraw your participation please contact Peter Washington by mail, phone or e-mail in accordance with the contact information listed on the first page of this consent.  If you have questions pertaining to your rights as a research participant; or to report objections to this study, you should contact William Turner, Assistant Vice President for Research, at Rice University. Email: william.turner@rice.edu or Telephone: 713-348-6071
  

			</font>
		</p>
		<input type="submit" name="submit" value="Accept and Start Playing"/>
	</form>


  </font>
</div>
</div>
</div>
</div>


</body>
</html>