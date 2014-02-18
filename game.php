
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

 <script type="text/javascript">

 	/*
	 * Inputs: duration --- the interval at which to call the function
	 * 		   fn       --- the function to call
	 *
	 * Replaces setTimeout() with a more accurate version.
	 */
	function interval(duration, fn) {

		this.baseline = undefined;
		this.duration = duration;

		this.run = function() {
		    if (this.baseline === undefined) {
		    	this.baseline = new Date().getTime();
		    }
		    fn();
		    var end = new Date().getTime();
		    this.baseline += this.duration;
		    var nextTick = this.duration - (end - this.baseline);
		    if (nextTick < 0) {
		      nextTick = 0;
		    }
		    (function(i) {
		        i.timer = setTimeout(function() {
		        	i.run(end);
		      	}, nextTick)
		    }) (this)
	  	}

		this.end = function() {
	   		clearTimeout(this.timer);
	   		this.baseline = undefined;
	 	}

	 	// Work on this later to be able to add a "tempo" control.
	 	this.setDuration = function(dur) {
	 		this.end();
	 		this.duration = dur;
	 		this.baseline = new Date().getTime();
	 		this.run();
	 	}
	}

	var t = 1;

	/*
	 * Load machine learning data from file.
	 */
	function load_from_file(input_files) {
		console.log(input_files[0]);

		var reader = new FileReader();
		reader.onload = function(e) {
			console.log("test");
		    console.log(e.target.result);
		};
		reader.readAsText(input_files[0]);
	}

	/*
	 * Play the except.
	 */
 	function play() {
 		timer.run();
 		external_timer.run();
 		stopwatch.run();
 		document.getElementById("goButton").disabled = true; 
 		document.getElementById("nextButton").disabled = false; 
 	}

 	/*
 	 * Stop the machine learning.
 	 */
 	function stop() {
 		// Reset time.
 		t = 1;
 		timer.end();
 		external_timer.end();
 		document.getElementById("goButton").disabled = false; 

 		// Set the last box to be highlighed black again.
 		var canv = document.getElementById('score');
		var cont = canv.getContext('2d');
		cont.globalAlpha = 1.0;
		cont.beginPath();
		cont.lineWidth = 5;
		cont.strokeStyle = 'black';
		cont.strokeRect((16) * 60, 0, 60, 120);
		cont.stroke();
 	}

 	var noteScoreNote = "";
 	var input_files = [];

 </script>

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
  <br><br>
  <font color="black">

    <div id="loading">
	  <br>
	  <font size=8>Program will start soon. Data is being loaded.....</font>
	</div>

	<div id="display">
		<div id="puzzle">
			<h2>C Major: Puzzle 1</h2>
		</div>
		<table>
			<tr>
				<canvas id="score" width="1050" height="120"></canvas>
			</tr>
		</table>
	</div>

	<div id="controls">
		<center>
			<br>
			<button class="btn btn-large btn-success" type="button" onclick="play()" id="goButton">Play!</button>
			<button class="btn btn-large btn-warning" type="button" onclick="next_puzzle()" id="nextButton" disabled="true">Next Level</button>
			<button class="btn btn-large btn-danger" type="button" onclick="end_game()">End Game</button>
			
			<form action="/gdform.php" method="post" name="gameMetrics" id="gameMetrics"> 
				<input type="hidden" name="subject" value="GameInfo<?= $user_id ?>" /> 
				<input type="hidden" id="redirectID" name="redirect" value="ComputerMusic/end.php?user_id=<?= $user_id ?>&score=-1" />
				<input type="hidden" name="test" value="123" />
				<input type="hidden" id="minuteID" name="minute" value="" />
				<input type="hidden" id="secondID" name="second" value="" />
				<input type="hidden" id="millisecondsID" name="milliseconds" value="" />
				<input type="hidden" id="scoreID" name="score" value="" />
				<input type="hidden" id="userID" name="user" value="<?= $user_id ?>" />
				<input type="hidden" id="wrongIndexID" name="wrongIndex" value="" />
			</form>

			<div id="timer">
				00:00:00
			</div>
			<font color="blue">
				<h2 id="score">Score: 0</h2> 
				<h2 id="numTries">Guesses Left: 3</h2>
			</font>
		</center>
	</div>

  </font>
</div>
</div>
<b>Instructions:</b> Click on the note that seems "out of place". <b>This program used the Web Audio API, which is currently only 
full supported on the Google Chrome Browser!</b>

UserID: 
<?php
$user_id = $_GET['user_id'];
echo $user_id;
?>
</div>
</div>

<script type="text/javascript">

// Submit game session data.
function end_game() {
	document.getElementById('minuteID').value = minuteRecord;
	document.getElementById('secondID').value = secondRecord;
	document.getElementById('millisecondsID').value = millisecondRecord;
	document.getElementById('scoreID').value = gameScore;
	document.getElementById('wrongIndexID').value = wrongIndexValues;
	document.getElementById('userID').value = "user_id=<?= $user_id ?>";
	document.getElementById('redirectID').value = "ComputerMusic/end.php?user_id=<?= $user_id ?>&score=" + gameScore;
	document.getElementById('gameMetrics').submit();
}

var trebleClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};

// Response to user's clicking guess.
$("#score").click(function myDown(e) 
{
	var position = $(canvas).position();
	var x = e.pageX-position.left;
	var y = e.pageY-position.top;
	console.log(wrongNoteIndex);
	var guess = Math.floor(x/60);
	if (guess === wrongNoteIndex) {
		gameScore += 10;
		next_puzzle();
	}
	else {
		if (x > 60) {
			var canv = document.getElementById('score');
			var cont = canv.getContext('2d');
			cont.globalAlpha = 1.0;
			cont.beginPath();
			cont.lineWidth = 5;
			if (guessNumber < 3) {
				cont.strokeStyle = 'red';
			  	cont.strokeRect(Math.floor(x/60) * 60, 0, 60, 120);
			  	trebleClicks[Math.floor(x/60) - 1] = 1;
			  	cont.stroke();
		 		guessNumber += 1;
			}
			if (guessNumber == 3) {
				document.getElementById("goButton").disabled = true; 
				document.getElementById("nextButton").disabled = false; 
				cont.strokeStyle = 'green';
		  		cont.strokeRect(wrongNoteIndex * 60, 0, 60, 120);
			}
			gameScore -= 3 * guessNumber;
			$('h2#numTries').text("Guesses Left: " + String(3 - guessNumber));
		}
	}
});

/*
 * Update to the next puzzle.
 */
function next_puzzle() {
	console.log("hurr");
	document.getElementById("goButton").disabled = false; 
	$('h2#numTries').text("Guesses Left: 3");
	trebleClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};
	// Record times for a particular level.
	minuteRecord += String(minute) + "-";
	secondRecord += String(second) + "-";
	millisecondRecord += String(millisecond) + "-";
	wrongIndexValues += String(wrongNoteIndex) + "-";
	// Reset timer variables.
	minute = 0;
	second = 0;
	millisecond = 0;
	minute_count = 0;
	second_count = 0;
	// Update game variables
	curr_puzzle += 1;
	if (curr_puzzle === puzzles.length) {
		end_game();
	}
	wrongNoteIndex = Math.floor((Math.random()*16) + 1);
	guessNumber = 0;
	// Reset musical time (measure).
	t = 1;
	// Update text describing current level.
	$('#puzzle').html("<h2>C Major: Puzzle " + String(curr_puzzle+1) + "</h2>");
	// Redraw the canvas
	var canvas = $("#score")[0];
	var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
	var ctx = renderer.getContext();
	ctx.clear();
	renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
	ctx = renderer.getContext();
	last_stave = new Vex.Flow.Stave(0, 0, 0);
	draw_canvas(curr_puzzle);
}

// The current tempo (in milliseconds).
var tempo = 700;

// Music notation.
var canvas = $("#score")[0];
var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
var ctx = renderer.getContext();
var last_stave = new Vex.Flow.Stave(0, 0, 0);

/*
 * Draw the notes for the current puzzle.
 */
var draw_canvas = function(puzzle) {
	// Clear the canvas here?

	var first_time = 0;
	var melody = puzzles[puzzle].slice(0);
	for (var i = -1; i < melody.length; i++) {
		var stave = new Vex.Flow.Stave(last_stave.width + last_stave.x, 0, 60);
		if (first_time === 0) {
			stave.addClef("treble").setContext(ctx).draw();
			first_time = 1;
			stave.setContext(ctx).draw();
			last_stave = stave;
			continue;
		}
		else {
			stave.setContext(ctx).draw();
		}
		var note = melody[i];
		// Convert note.
		if (note.length === 2) {
			var new_note = new Vex.Flow.StaveNote({ keys: [note.toLowerCase().substring(0, 1) + "/" + note.substring(1, 2)], duration: "q" });
		}
		else if (note === "rest") {
			var new_note = new Vex.Flow.StaveNote({ keys: ["b/4"], duration: "qr" });
		}
		else {
			var new_note = new Vex.Flow.StaveNote({ keys: [note.toLowerCase().substring(0, 2) + "/" + note.substring(2, 3)], duration: "q" });
		}
		var staff_notes = [];
		// Play note.
		if (note.length === 2 || note === "rest") {
			staff_notes.push(new_note);
		}
		else {
			if (note.substring(1, 2) === "#") {
				staff_notes.push(new_note.addAccidental(0, new Vex.Flow.Accidental("#")));
			}
			else {
				staff_notes.push(new_note.addAccidental(0, new Vex.Flow.Accidental("b")));
			}
		}
		// Create a voice in 1/4.
		var voice = new Vex.Flow.Voice({
			num_beats: 1,
			beat_value: 4,
			resolution: Vex.Flow.RESOLUTION
		});
		// Add notes to voice
		voice.addTickables(staff_notes);
		// Format and justify the notes to 500 pixels
		var formatter = new Vex.Flow.Formatter().joinVoices([voice]).format([voice], 500);
		// Render voice
		voice.draw(ctx, stave);
		last_stave = stave;

		if (note != "rest") {
			var canv = document.getElementById('score');
		 	var cont = canv.getContext('2d');
		 	cont.font="12px Verdana";
		    cont.fillText(note + " (" + noteToDegree[note] + ")", (i+1) * 60 + 5, 110);
		}
		
	    // Music highlighting.
		var canv = document.getElementById('score');
		var cont = canv.getContext('2d');
		cont.globalAlpha = 1.0;
		cont.beginPath();
		cont.lineWidth = 5;
		cont.strokeStyle = 'black';
		cont.strokeRect((i+1) * 60, 0, 60, 120);
		cont.stroke();
	}
};

// Constants for chord mappings
var noteToDegree = {"C4": 1, "C5": 1, "C6": 1, "D4": 2, "D5": 2, "D6": 2, "E4": 3, "E5": 3, "E6": 3, "F4": 4, "F5": 4, "F6": 4,
					"G4": 5, "G5": 5, "G6": 5, "A4": 6, "A5": 6, "A6": 6, "B4": 7, "B5": 7, "B6": 7};

var validNotes = ["C4", "C5", "D4", "D5", "E4", "E5", "F4", "F5", "G4", "G5", "A4", "A5", "B4", "B5"];

var note = "";

// Correct melodies
var puzzles = [ ["C5", "C5", "G5", "G5", "A5", "A5", "G5", "rest", "F5", "F5", "E5", "E5", "D5", "D5", "C5", "rest"],
				["C5", "D5", "E5", "C5", "C5", "D5", "E5", "C5", "E5", "F5", "G5", "rest", "E5", "F5", "G5", "rest"],
				["C5", "rest", "rest", "G4", "A4", "G4", "rest", "G4", "A4", "G4", "rest", "G4", "A4", "G4", "rest", "rest"],
				["C5", "D5", "E5", "F5", "G5", "A5", "B5", "C6", "C6", "B5", "A5", "G5", "F5", "E5", "D5", "C5"],
				["C5", "E5", "G5", "rest", "F5", "A5", "C6", "rest", "B5", "G5", "A5", "B5", "C6", "G5", "C5", "rest"],
				["A4", "B4", "C5", "B4", "C5", "B4", "C5", "E5", "F5", "E5", "D5", "C5", "B4", "A4", "E4", "A4"],
				["E5", "F5", "G5", "rest", "F5", "E5", "D5", "rest", "E5", "D5", "C5", "rest", "D5", "rest", "rest", "rest"],
				["C4", "E4", "G4", "C5", "E5", "G4", "C5", "E5", "C4", "E4", "G4", "C5", "E5", "G4", "C5", "E5"],
				["A4", "B4", "C5", "E5", "F5", "E5", "F5", "E5", "D5", "C5", "B4", "A4", "B4", "G5", "A5", "A5"],
				["C5", "C4", "A4", "C4", "G4", "C4", "E4", "C4", "D4", "E4", "D4", "C4", "D4", "rest", "C4"],
				["A4", "C5", "E5", "rest", "D5", "F5", "A5", "rest", "E5", "B4", "C5", "D5", "E5", "B4", "E4", "rest"],
				["C4", "C5", "D4", "A4", "B4", "C5", "A4", "G4", "C5", "C4", "E4", "A4", "G4", "A4", "G4", "C4"]
			 ];

// Game variables
var curr_puzzle = 0;
var wrongNoteIndex = Math.floor((Math.random()*16) + 1);
var guessNumber = 0;
var gameScore = 0;

// Timer saved variables.
var minuteRecord = "";
var secondRecord = "";
var millisecondRecord = "";
var wrongIndexValues = "";

// Stopwatch timer
var minute = 0;
var second = 0;
var millisecond = 0;
var minute_count = 0;
var second_count = 0;
var stopwatch = new interval(10, function() {
	minute_count += 1;
	second_count += 1;
	if (second_count === 100) {
		second += 1;
		second_count = 0;
	}
	if (minute_count === 100*60) {
		minute += 1;
		minute_count = 0;
	}
	millisecond += 1;
	if (second === 60) {
		second = 0;
	}
	if (millisecond === 100) {
		millisecond = 0;
	}
	var minuteString = String(minute);
	var secondString = String(second);
	var millisecondString = String(millisecond);
	if (minute < 10) {
		minuteString = "0" + minuteString;
	}
	if (second < 10) {
		secondString = "0" + secondString;
	}
	if (millisecond < 10) {
		millisecondString = "0" + millisecondString;
	}
	$('#timer').text(minuteString + ":" + secondString + ":" + millisecondString);
});

// Update highlighting of the notes.
var timer = new interval(tempo, function() {
	// Play note.
	if (t === wrongNoteIndex) {
		var tempValidNotes = validNotes.slice(0);
		tempValidNotes.splice(tempValidNotes.indexOf(puzzles[curr_puzzle].slice(0)[t-1]), 1);
		note = tempValidNotes[Math.floor(Math.random() * tempValidNotes.length)];
	}
	else {
		note = puzzles[curr_puzzle].slice(0)[t-1];
	}
	
	console.log("YO " + note);
	if (note != "rest") {
		MIDI.noteOn(0, MIDI.keyToNote[note], 127, 0);
	}
	
	// Music highlighting.
	var canv = document.getElementById('score');
	var cont = canv.getContext('2d');
	cont.beginPath();
	if (t > 1) {
		cont.globalAlpha = 1.0;
		cont.lineWidth = 5;
		if (trebleClicks[t-2] != 1) {
			cont.strokeStyle = 'black';
		}
		else {
			cont.strokeStyle = 'red';
		}
		cont.strokeRect((t-1) * 60, 0, 60, 120);
		cont.stroke();
	}
	if (t < puzzles[curr_puzzle].slice(0).length + 1) {
		cont.globalAlpha = 1.0;
		cont.lineWidth = 5;
		cont.strokeStyle = 'green';
		cont.strokeRect(t * 60, 0, 60, 120);
	}

	$('h2#score').text("Score: " + String(gameScore));
	
    t += 1;
}); 

var external_timer = new interval(tempo, function() {
	if (t > puzzles[curr_puzzle].slice(0).length + 1) {
		stop();
		//play();
	}
});

$(window).load(function() {
	$('#controls').hide();
	$('#display').hide();
	$('#load_files').hide();
    MIDI.loadPlugin({
		soundfontUrl: "./soundfont/",
		instruments: [ "acoustic_grand_piano", "acoustic_grand_piano" ],
		callback: function() {
			$('#loading').remove();
			$('#controls').show();
			$('#display').show();
			$('#load_files').show();
			draw_canvas(curr_puzzle);
		}
	});
});

</script>
</body>
</html>