
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

	var measure = 0;
	var first_time = 0;

	var measure2 = 0
	var first_time2 = 0;

	var t = 1;

	 /*
	  * Draw notes on the canvas.
	  */
	function draw_state(note, chord) {
	  // DRAW NOTE
	  measure += 1;
	  if (measure === 12) {
	  	trebleClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};
		bassClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};
	  	measure = 0;
	  	t = 1;
	  	last_stave.x = 10;
        ctx.clear();
        last_stave = new Vex.Flow.Stave(0, 0, 60);
		last_stave.addClef("treble").setContext(ctx).draw();
	  }
      var stave = new Vex.Flow.Stave(last_stave.width + last_stave.x, 0, 60);
      if (measure === 0 && first_time === 1) {
      	stave.addClef("treble").setContext(ctx).draw();
      	first_time = 1;
      }
      else {
      	stave.setContext(ctx).draw();
      }
      // Convert note.
      if (note.length === 2) {
      	var new_note = new Vex.Flow.StaveNote({ keys: [note.toLowerCase().substring(0, 1) + "/" + note.substring(1, 2)], duration: "q" });
      }
	  else {
	  	var new_note = new Vex.Flow.StaveNote({ keys: [note.toLowerCase().substring(0, 2) + "/" + note.substring(2, 3)], duration: "q" });
	  }
	  var staff_notes = [];
	  // Play note.
	  if (note.length === 2) {
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
	  console.log(staff_notes);
	  // Format and justify the notes to 500 pixels
	  var formatter = new Vex.Flow.Formatter().joinVoices([voice]).format([voice], 500);
	  // Render voice
	  voice.draw(ctx, stave);
	  last_stave = stave;


	  // DRAW CHORD
	  measure2 += 1;
	  if (measure2 === 12) {
	  	measure2 = 0;
	  	last_stave2.x = 10;
        ctx2.clear();
        last_stave2 = new Vex.Flow.Stave(0, 0, 60);
		last_stave2.addClef("bass").setContext(ctx2).draw();
	  }
      var stave2 = new Vex.Flow.Stave(last_stave2.width + last_stave2.x, 0, 60);
      if (measure2 === 0 && first_time2 == 1) {
      	stave2.addClef("bass").setContext(ctx2).draw();
      	first_time2 = 1;
      }
      else {
      	stave2.setContext(ctx2).draw();
      }
      // Convert note.
      var chord_notes = new Array();
	  for (i = 0; i < chord.length; i++) {
	  		chord_notes.push(chord[i].toLowerCase().substring(0, 1) + "/" + chord[i].substring(1, 2));
	  }
	  var new_chord = new Vex.Flow.StaveNote({ keys: chord_notes, duration: "q" });
	  var staff_chords = [];
	  // Play note.
	   staff_chords.push(new_chord);
	  // Create a voice in 1/4.
	  var voice2 = new Vex.Flow.Voice({
	    num_beats: 1,
	    beat_value: 4,
	    resolution: Vex.Flow.RESOLUTION
	  });
	  // Add notes to voice
	  voice2.addTickables(staff_chords);
	  // Format and justify the notes to 500 pixels
	  var formatter2 = new Vex.Flow.Formatter().joinVoices([voice2]).format([voice2], 500);
	  // Render voice
	  voice2.draw(ctx2, stave2);
	  last_stave2 = stave2;
	}

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
	 * Update the machine learning models.
	 *
	 * For now, the learning_method is the order of the Markov Model.
	 */
 	function update_learning_models(learning_method) {
 		// Update note scores.
 		for (var i = 0; i < note_state.length; i++) {
 			var scoreKeyNote = new Array();
			for (var j = 0; j <= learning_method; j++) {
				scoreKeyNote.push(note_state[i-j]);
			}
			scoreKeyNote.reverse();
			// Hack --- figure out how to deal with this situation later.
			if (scoreKeyNote.length < learning_method) {
				continue;
			}
 			if (trebleClicks[i] != 0 && trebleClicks[i] % 2 == 0) {
 				if (scoreKeyNote in noteScores) {
 					noteScores[scoreKeyNote] -= 2.5;
 				}
 				else {
 					noteScores[scoreKeyNote] = -2.5;
 				}
 			}
 			if (trebleClicks[i] != 0 && trebleClicks[i] % 2 == 1) {
 				if (scoreKeyNote in noteScores) {
 					noteScores[scoreKeyNote] += 2.5;
 				}
 				else {
 					noteScores[scoreKeyNote] = 2.5;
 				}
 			}
 		}
 		// Update chord scores.
 		for (var i = 0; i < chord_state.length; i++) {
 			var scoreKeyChord = new Array();
			for (var j = 0; j <= learning_method; j++) {
				scoreKeyChord.push(chord_state[i-j]);
			}
			scoreKeyChord.reverse();
			if (scoreKeyChord.length < learning_method) {
				continue;
			}
 			if (bassClicks[i] != 0 && bassClicks[i] % 2 == 0) {
 				if (scoreKeyChord in chordScores) {
 					chordScores[scoreKeyChord] -= 2.5;
 				}
 				else {
 					chordScores[scoreKeyChord] = -2.5;
 				}
 			}
 			if (bassClicks[i] != 0 && bassClicks[i] % 2 == 1) {
 				if (scoreKeyChord in chordScores) {
 					chordScores[scoreKeyChord] += 2.5;
 				}
 				else {
 					chordScores[scoreKeyChord] = 2.5;
 				}
 			}
 		}
 		// Update note pair scores.
 		for (var i = 0; i < note_state.length; i++) {
			var infoList = [];
			for (var j = 0; j <= learning_method; j++) {
				var newArray = Array();
				newArray.push(note_state[i-j]);
				newArray.push(chord_state[i-j]);
				infoList.push(newArray);
			}
			infoList.reverse();
			if (infoList.length < learning_method) {
				continue;
			}
			if (trebleClicks[i] != 0 && trebleClicks[i] % 2 == 0) {
				if (!(note_state[i] in notePairScores)) {
					var keyString = infoList.toString();
					notePairScores[note_state[i]] = {};
					notePairScores[note_state[i]][infoList.toString()] = -2.5;
				}
				else {
					if (infoList.toString() in notePairScores[note_state[i]]) {
 						notePairScores[note_state[i]][infoList.toString()] -= 2.5;
	 				}
	 				else {
	 					notePairScores[note_state[i]][infoList.toString()] = -2.5;
	 				}
				}
 			}
 			if (trebleClicks[i] != 0 && trebleClicks[i] % 2 == 1) {
 				if (!(note_state[i] in notePairScores)) {
					notePairScores[note_state[i]] = {};
					notePairScores[note_state[i]][infoList.toString()] = 2.5;
				}
				else {
					if (infoList.toString() in notePairScores[note_state[i]]) {
 						notePairScores[note_state[i]][infoList.toString()] += 2.5;
	 				}
	 				else {
	 					notePairScores[note_state[i]][infoList.toString()] = 2.5;
	 				}
				}
 			}
 		}
 		// Update chord pair scores.
 		for (var i = 0; i < chord_state.length; i++) {
			var infoList = [];
			for (var j = 0; j <= learning_method; j++) {
				var newArray = Array();
				newArray.push(note_state[i-j]);
				newArray.push(chord_state[i-j]);
				infoList.push(newArray);
			}
			infoList.reverse();
			if (infoList.length < learning_method) {
				continue;
			}
			if (bassClicks[i] != 0 && bassClicks[i] % 2 == 0) {
				if (!(chord_state[i] in chordPairScores)) {
					var keyString = infoList.toString();
					chordPairScores[chord_state[i]] = {};
					chordPairScores[chord_state[i]][infoList.toString()] = -2.5;
				}
				else {
					if (infoList.toString() in chordPairScores[chord_state[i]]) {
 						chordPairScores[chord_state[i]][infoList.toString()] -= 2.5;
	 				}
	 				else {
	 					chordPairScores[chord_state[i]][infoList.toString()] = -2.5;
	 				}
				}
 			}
 			if (bassClicks[i] != 0 && bassClicks[i] % 2 == 1) {
 				if (!(chord_state[i] in chordPairScores)) {
					chordPairScores[chord_state[i]] = {};
					chordPairScores[chord_state[i]][infoList.toString()] = 2.5;
				}
				else {
					if (infoList.toString() in chordPairScores[chord_state[i]]) {
 						chordPairScores[chord_state[i]][infoList.toString()] += 2.5;
	 				}
	 				else {
	 					chordPairScores[chord_state[i]][infoList.toString()] = 2.5;
	 				}
				}
 			}
 		}
 	}

 	/*
	 *
	 * Proposed data structure for n-order Markov models:
	 * 
	 * noteScores: { [note1, note2, ..., noteN]: score, ... }
	 * chordScores: { [chord1, chord2, ..., chordN]: score, ... }
	 * notePairScores: { note1: { [Info(note1, chord1), ..., Info(noteN, chordN)]: score, .. }, .. }
	 * chordPairScores: { chord1: { [Info(note1, chord1), ..., Info(noteN, chordN)]: score, .. }, .. }
	 *
	 */

	/*
	 * Start our machine learning.
	 */
 	function start() {
 		iteration += 1;
 		update_learning_models(1);
 		note_state = [];
		chord_state = [];
 		timer.run();
 		external_timer.run();

 		//console.log(noteScores);
		//console.log(chordScores);
		console.log(notePairScores);
		for (var s in notePairScores) {
			console.log(notePairScores[s]);
			for (var t in notePairScores[s]) {
				console.log(t);
			}
		}
		console.log(chordPairScores);
		for (var s in chordPairScores) {
			console.log(chordPairScores[s]);
			for (var t in chordPairScores[s]) {
				console.log(t);
			}
		}
 	}

 	/*
 	 * Stop the machine learning.
 	 */
 	function stop() {
 		timer.end();
 	}

 	/*
 	 * Info object.
 	 */
	function Info(chord, note) {
		this.chord = chord;
		this.note = note;
	}

	/*
	 * Compare two arrays.
	 */
	 function compare_arrays(array1, array2) {
	 	if (array1.length != array2.length) {
	 		return false;
	 	}
	 	for (var i = 0; i < array1.length; i++) {
	 		if (array1[i] != array2[i]) {
	 			return false;
	 		}
	 	}
	 	return true;
	 }

 	/*
 	 * Play the note / chord combination based on probability that the next note is favorable to the user.
 	 */
 	function machine_learning(noteState, chordState, validNotes, validChords, note_scores, chord_scores, note_pair_scores, chord_pair_scores, markovOrder, iteration) {
 		var chordContents = {"C": ["C4", "E4", "G4"], "D": ["D4", "F4", "A4"], "E": ["E4", "G4", "B4"],
					 "F": ["F4", "A4", "C5"], "G": ["G4", "B4", "D5"], "A": ["A4", "C4", "E5"],
					 "B": ["B4", "D5", "F5"] };

 		var noteProb = Math.random();
 		var chordProb = Math.random();

 		//var iterationProbabiliy = 0.9 - iteration*0.05;
 		var iterationProbabiliy = 0.1;
 		if (iterationProbabiliy < 0.0) {
 			iterationProbabiliy = 0.0;
 		}
 		console.log(iterationProbabiliy);
 		var noteRandProb = Math.random();
 		var chordRandProb = Math.random();

 		var noteScoreNote = validNotes[Math.floor(Math.random() * validNotes.length)];
 		noteScoreNote = noteScoreNote["Name"];
        var chordScoreChord = validChords[Math.floor(Math.random() * validChords.length)];
        chordScoreChord = chordScoreChord["Name"];

        /* Start here next time!
        var noteScoreNoteProb = validNotes[Math.floor(Math.random() * validNotes.length)];
 		noteScoreNoteProb = noteScoreNoteProb["Name"];
        var chordScoreChordProb = validChords[Math.floor(Math.random() * validChords.length)];
        chordScoreChordProb = chordScoreChordProb["Name"];
        */


        // Get the preferred note based on note Markov chain.
        if (noteRandProb > iterationProbabiliy && noteState.length > markovOrder+1) {
        	var last_n_notes = noteState.slice(noteState.length-markovOrder, noteState.length);
	 		var note_total = 0;
	 		for (var key in note_scores) {
	 			if (compare_arrays(key.split(",").slice(0, markovOrder), last_n_notes) != true) {
	 				continue;
	 			}
	 			if (note_scores[key] > 0.0) {
	 				note_total += note_scores[key];
	 			}
	 		}
			var note_value = note_total * noteProb;
	 		var note_sum = 0;
	 		for (var key in note_scores) {
	 			if (compare_arrays(key.split(",").slice(0, markovOrder), last_n_notes) != true) {
	 				continue;
	 			}
	 			if (note_scores[key] < 0.0) {
	 				continue;
	 			}
	 			note_sum += note_scores[key];
	 			if (note_sum > note_value) {
	 				noteScoreNote = key.split(",")[markovOrder];
	 				break;
	 			}
	 		}
        }
 		
 		// Get the preferred chord based on chord Markov chain.
 		if (chordRandProb > iterationProbabiliy && chordState.length > markovOrder+1) {
 			var last_n_chords = chordState.slice(chordState.length-markovOrder, chordState.length);
	 		var chord_total = 0;
	 		for (var key in chord_scores) {
	 			if (compare_arrays(key.split(",").slice(0, markovOrder), last_n_chords) != true) {
	 				continue;
	 			}
	 			if (chord_scores[key] > 0.0) {
	 				chord_total += chord_scores[key];
	 			}
	 		}
			var chord_value = chord_total * chordProb;
	 		var chord_sum = 0;
	 		for (var key in chord_scores) {
	 			if (compare_arrays(key.split(",").slice(0, markovOrder), last_n_chords) != true) {
	 				continue;
	 			}
	 			if (chord_scores[key] < 0.0) {
	 				continue;
	 			}
	 			chord_sum += chord_scores[key];
	 			if (chord_sum > chord_value) {
	 				chordScoreChord = key.split(",")[markovOrder];
	 				break;
	 			}
	 		}
 		}

 		// Get the preferred note based on the pair Markov chain.

 		// Get the preferred chord based on pair Markov chain.

 		// Push the note and chord to be played.
 		var chord_contents = chordContents[chordScoreChord];
 		var nextNote = MIDI.keyToNote[noteScoreNote];
 		var nextChord = [];
 		for (var i = 0; i < chord_contents.length; i++) {
 			nextChord.push(MIDI.keyToNote[chord_contents[i]]);
 		}
 		var next = new Info(nextChord, nextNote);
 		noteState.push(noteScoreNote);
 		chordState.push(chordScoreChord);
 		return next;
 	}

 	var noteScoreNote = "";
 	var chordScoreChord = "";
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
		<h2>C Major:</h2>
		<table>
			<tr>
				<canvas id="score" width="1000" height="120"></canvas>
				<canvas id="score2" width="1000" height="140"></canvas>
			</tr>
		</table>
	</div>

	<div id="controls">
		<center>
			<br>
			<button class="btn btn-large btn-primary" type="button" onclick="start()">Play learned model!</button>
		</center>
		<!-- Comment out external controls for now, but add the functionaity demonstrated for saving files later.
		<button class="btn btn-large btn-primary" type="button" onclick="stop()">Pause</button>
		<button class="btn btn-large btn-primary" type="button" onclick="window.location.href='data:text;charset=utf-8,helloWorld'">Save Machine Learning Data</button>
		-->
	</div>

	<!-- Comment out externa controls for now, but add the functionaity demonstrated for saving files later.
	<div id="load_files">
		<h2>Load Machine Learning Data:</h2>
		<input type="file" multiple onchange="load_from_file(this.files)">
	</div>
	-->

  </font>
</div>
</div>
<b>Instructions:</b> After clicking the "Play learned model!" button, click the notes that you want to comment on. A note
or chord highlighted green indicates positive feedback, while highlighting red indicates negative feedback. After providing
the feedback, press the "Play learned model!" button again to hear the new music adapted to your tastes. Repeat as many
times as you like! <b>This program used the Web Audio API, which is currently only full supported on the Google Chrome 
Browser!</b>

UserID: 
<?php
echo $_GET['user_id'];
?>
</div>
</div>


<script type="text/javascript">

var trebleClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};
var bassClicks = {0:0, 1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0, 9:0, 10:0, 11:0, 12:0};

// Canvas highlighting
$("#score").click(function myDown(e) 
{
  var position = $(canvas).position();
  var x = e.pageX-position.left;
  var y = e.pageY-position.top;
  var canv = document.getElementById('score');
  var cont = canv.getContext('2d');
  cont.globalAlpha = 1.0;
  cont.beginPath();
  cont.lineWidth = 5;
  if (trebleClicks[Math.floor(x/60) - 1] % 2 === 0) {
  	cont.strokeStyle = 'green';
  	cont.strokeRect(Math.floor(x/60) * 60, 0, 60, 120);
  	trebleClicks[Math.floor(x/60) - 1] += 1;
  	cont.stroke();
  }
  else {
  	cont.strokeStyle = 'red';
  	cont.strokeRect(Math.floor(x/60) * 60, 0, 60, 120);
  	trebleClicks[Math.floor(x/60) - 1] += 1;
  	cont.stroke();
  }
});

$("#score2").click(function myDown(e) 
{
  var position = $(canvas).position();
  var x = e.pageX-position.left;
  var y = e.pageY-position.top;
  var canv = document.getElementById('score2');
  var cont = canv.getContext('2d');
  cont.globalAlpha = 1.0;
  cont.beginPath();
  cont.lineWidth = 5;
  if (bassClicks[Math.floor(x/60)-1] % 2 === 0) {
  	cont.strokeStyle = 'green';
  	cont.strokeRect(Math.floor(x/60) * 60, 0, 60, 140);
  	bassClicks[Math.floor(x/60)-1] += 1;
  	cont.stroke();
  }
  else {
  	cont.strokeStyle = 'red';
  	cont.strokeRect(Math.floor(x/60) * 60, 0, 60, 140);
  	bassClicks[Math.floor(x/60)-1] += 1;
  	cont.stroke();
  }
});


// The current tempo (in milliseconds).
var tempo = 1000;

// Music notation.
var canvas = $("#score")[0];
var canvas2 = $("#score2")[0];
var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
var renderer2 = new Vex.Flow.Renderer(canvas2, Vex.Flow.Renderer.Backends.CANVAS);
var ctx = renderer.getContext();
var ctx2 = renderer2.getContext();
var last_stave = new Vex.Flow.Stave(0, 0, 60);
var last_stave2 = new Vex.Flow.Stave(0, 0, 60);

var draw_canvas = function() {
	last_stave.addClef("treble").setContext(ctx).draw();
	last_stave2.addClef("bass").setContext(ctx2).draw();
};

// Constants for chord mappings
var noteToDegree = {"C4": 1, "C5": 1, "D4": 2, "D5": 2, "E4": 3, "E5": 3, "F4": 4, "F5": 4,
					"G4": 5, "G5": 5, "A4": 6, "A5": 6, "B4": 7, "B5": 7};

var noteToChord = {"C4": "CMaj(I)", "C5": "CMaj(I)", "D4": "DMin(ii)", "D5": "DMin(ii)", 
					"E4": "EMin(iii)", "E5": "EMin(iii)", "F4": "FMaj(IV)", "F5": "FMaj(IV)",
					"G4": "GMaj(V)", "G5": "GMaj(V)", "A4": "AMin(vi)", "A5": "AMin(vi)", 
					"B4": "BDim(vii)", "B5": "BDim(vii)"};

// Reinforcement machine learning variables.
var notes = [ {"Name": "C4"}, 
			  {"Name": "E4"}, 
			  {"Name": "G4"}, 
			  {"Name": "C5"}, 
			  {"Name": "E5"}, 
			  {"Name": "G5"} ];

var chords = [ {"Name": "C", "Contents": ["C4", "E4", "G4"], "FullName": "C Maj (I)"},
			   {"Name": "D", "Contents": ["D4", "F4", "A4"], "FullName": "D Min (ii)"},
			   {"Name": "E", "Contents": ["E4", "G4", "B4"], "FullName": "E Min (iii)"},
			   {"Name": "F", "Contents": ["F4", "A4", "C5"], "FullName": "F Maj (IV)"},
			   {"Name": "G", "Contents": ["G4", "B4", "D5"], "FullName": "G Maj (V)"},
			   {"Name": "A", "Contents": ["A4", "C4", "E5"], "FullName": "A Min (vi)"},
			   {"Name": "B", "Contents": ["B4", "D5", "F5"], "FullName": "B Dim (vii)"} ];

/*
 *
 * Proposed data structure for n-order Markov models:
 * 
 * noteScores: { [note1, note2, ..., noteN]: score, ... }
 * chordScores: { [chord1, chord2, ..., chordN]: score, ... }
 * notePairScores: { note1: { [Info(note1, chord1), ..., Info(noteN, chordN)]: score, .. }, .. }
 * chordPairScores: { chord1: { [Info(note1, chord1), ..., Info(noteN, chordN)]: score, .. }, .. }
 *
 */

// Just account for melody and chord structure, independent of each other.
var noteScores = {};
var chordScores = {};

// Account for note-chord pairs.
var notePairScores = {};
var chordPairScores = {};

var note = "";
var chord = "";
var note_state = [];
var chord_state = [];

var iteration = 0;

var timer = new interval(tempo, function() {

	var info = new Info(null, null);

	// Get the next note via machine learning.
	info = machine_learning(note_state, chord_state, notes, chords, noteScores, chordScores, notePairScores, chordPairScores, 1, iteration);

	// Play note.
	note = info.note;
	chord = info.chord; 
	MIDI.noteOn(0, note, 127, 0);
	MIDI.chordOn(1, chord, 50, 0);

	// Draw the note and its info.
	draw_state(MIDI.noteToKey[note], [MIDI.noteToKey[chord[0]], MIDI.noteToKey[chord[1]], MIDI.noteToKey[chord[2]] ]);

	var canv = document.getElementById('score');
 	var cont = canv.getContext('2d');
 	cont.font="12px Verdana";
    cont.fillText(MIDI.noteToKey[note] + " (" + noteToDegree[MIDI.noteToKey[note]] + ")", t * 60 + 5, 110);

    var canv2 = document.getElementById('score2');
 	var cont2 = canv2.getContext('2d');
 	cont2.font="12px Verdana";
    //cont2.fillText(MIDI.noteToKey[chord[0]] + "," + MIDI.noteToKey[chord[1]] + "," + MIDI.noteToKey[chord[2]] + ")", t * 60 + 5, 125);
    cont2.fillText(noteToChord[MIDI.noteToKey[chord[0]]], t * 60 + 5, 110);
    //cont2.fillText(noteToChord[MIDI.noteToKey[chord[0]]], t * 60 + 5, 125);
    t += 1;
}); 

var external_timer = new interval(tempo, function() {
	if (t === 13) {
		stop();
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
			draw_canvas();
		}
	});
});

</script>
</body>
</html>