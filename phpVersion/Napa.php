<!doctype html>
<html lang="en" dir="ltr"><head>
<meta charset="UTF-8">
<title>Napa</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap stylesheet and JavaScript -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
    <!-- Font awesome -->
    <script defer src="https://pro.fontawesome.com/releases/v5.10.0/js/all.js" integrity="sha384-G/ZR3ntz68JZrH4pfPJyRbjW+c0+ojii5f+GYiYwldYU69A+Ejat6yIfLSxljXxD" crossorigin="anonymous"></script>
<link rel="stylesheet" href="styles.css">
<style>
	
		#hero {	
			background-image: url("images/napa.jpg");
	}
	


</style>
<script>
		function getInfo() {
			getAQI();
			getSun();
			getWeather();
		}

		function getWeather() {
			/* Step 1: Make instance of request object...
			...to make HTTP request after page is loaded*/
			request2 = new XMLHttpRequest();
			console.log("1 - request object created");

			// Step 2: Set the URL for the AJAX request to be the JSON file

		request2.open("GET", "https://api.openweathermap.org/data/2.5/onecall?lat=38.297539&lon=	-122.286865&exclude=hourly,daily&appid=6e477249e2b1e0f4d6461a66a1947eaa", true)

			console.log("2 - opened request file");

			// Step 3: set up event handler/callback

			request2.onreadystatechange = function() {

				if (request2.readyState == 4 && request2.status == 200) {

					// Step 5: wait for done + success
					console.log("5 - response received");
					result = request2.responseText;
					//alert(result);
					//document.getElementById("temp").innerHTML = result;
					weather = JSON.parse(result);

					temp = weather["current"]["temp"];
					temp = (temp - 273.15) * 9/5 + 32;
					temp = temp.toFixed(2);
					document.getElementById("temp").innerHTML ="Temperature: " + temp + "&deg;F";

				}
				else if (request2.readyState == 4 && request2.status != 200) {

					document.getElementById("temp").innerHTML = "Something is wrong!  Check the logs to see where this went off the rails";

				}

				else if (request2.readyState == 3) {

					document.getElementById("temp").innerHTML = "Too soon!  Try again";

				}

			}
		// Step 4: fire off the HTTP request
			request2.send();
			console.log("4 - Request sent");
		}
		// convert UTC to PST
		function formatTime(raw) {
			raw_int = parseInt(raw); 
			var pst = 0;
			if (raw_int < 7) {
				pst = raw_int + 5; 

			} else {
				pst = raw_int - 7;
			}

			return pst;

		}


		// returns the minutes and seconds values for the times
		function getMinutes(raw){
			raw_int = parseInt(raw);
			var length = 0;
			var start = 0;

			// the hour is two digits
			if (raw_int > 9) {
				length = 8;
				start = 2;
			}

			// the hour is one digit
			if (raw_int < 10) {
				length = 7;
				start = 1;

			}
			var count = 0;
			var minutes = "";
			for (var i = start; i < length; i++) {
				minutes = minutes.concat(raw[i]);
			}

			return minutes;
		}



		// determine whether the AM or PM value changes or not
		function AMorPM(raw) {
			raw_int = parseInt(raw);
			var day_time = "";

			// am or pm is different 
			if (raw_int == 12 || (raw_int >= 1 && raw_int < 7)) {
				if (raw[raw.length-2] == "P") {
					day_time = day_time.concat("A");
				} else {
					day_time = day_time.concat("P");
				}
				day_time = day_time.concat(raw[raw.length-1]);
			} 
			// am or pm is the same 
			if (raw_int >= 7 && raw_int < 12) {
				day_time = day_time.concat(raw[raw.length-2]);
				day_time = day_time.concat(raw[raw.length-1]);
			}
			return day_time;
		}


		function getSun() {
			request1 = new XMLHttpRequest();

			console.log("1. request object created");

			//request json file from sunrise-sunset.org, fill in lat and lng for Somerville
			request1.open("GET", "https://api.sunrise-sunset.org/json?lat=32.715736&lng=-117.161087", true)

			console.log("2. opened request file");

			request1.onreadystatechange = function() {

				if (request1.readyState == 4 && request1.status == 200) {
					console.log("5. response received");
					result = request1.responseText;
					//document.getElementById("data").innerHTML = result;
					sun = JSON.parse(result);
					var raw_sunrise = sun["results"]["sunrise"];
					var raw_sunset = sun["results"]["sunset"];
					sunrise = formatTime(raw_sunrise);
					sunset = formatTime(raw_sunset);

					var sunrise_minutes = getMinutes(raw_sunrise);
					var sunset_minutes = getMinutes(raw_sunset);
					var sunrise_daytime = AMorPM(raw_sunrise);
					var sunset_daytime = AMorPM(raw_sunset);

					//displays sunrise time:
					document.getElementById("sr").innerHTML ="Sunrise: " + sunrise + sunrise_minutes + "  " + sunrise_daytime;

					//displays sunset time:
					document.getElementById("ss").innerHTML ="Sunset: " +  sunset + sunset_minutes + "  " + sunset_daytime;

					//displays current day length
					document.getElementById("dayl").innerHTML ="Day length: " + sun["results"]["day_length"];

				}
				else if (request1.readyState == 4 && request1.status != 200) {
					document.getElementById("sr").innerHTML = "Something went wrong!";
				}

				else if (request1.readyState == 3) {
					document.getElementById("sr").innerHTML = "Too soon! Try again";
				}

			}

			request1.send();
			console.log("4. request sent");
		}

	    function getAQI() {
            request = new XMLHttpRequest();
            console.log("1 - request object created");


            request.open("GET", "http://api.openweathermap.org/data/2.5/air_pollution?lat=38.297539&lon=-122.286865&appid=9eb8bd1cc3e0712eec15fc85f42632dd", true);
            console.log("2 - opened request file");

            request.onreadystatechange = function() {

                if (request.readyState == 4 && request.status == 200) {


                    console.log("5 - response received");
                    result = request.responseText;

                    var pollution = JSON.parse(result);
                    var aqi = pollution["list"][0]["main"]["aqi"];
                    var description = "";

                    if (aqi == 1) {
                        description = "Good";
                    } else if (aqi == 2) {
                        description = "Fair";
                    } else if (aqi == 3) {
                        description = "Moderate";
                    } else if (aqi == 4) {
                        description = "Poor";
                    } else {
                        description = "Very Poor";
                    }

                    document.getElementById("info").innerHTML = "Current AQI: " + aqi + " (" + description + ")";


                }
                else if (request.readyState == 4 && request.status != 200) {

                    document.getElementById("info").innerHTML = "Error";

                }

                else if (request.readyState == 3) {

                    document.getElementById("info").innerHTML = "Too soon!  Try again";

                }

            }

            request.send();
            console.log("4 - Request sent");
        }
	</script>
</head>

<body onload="getInfo()">
	<!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">California Road Trip</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://jasonxiang.great-site.net/final/yourTrip.php">Your Trip</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cities
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/LosAngeles.php">Los Angeles</a></li>
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/Napa.php">Napa</a></li>
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/SanDiego.php">San Diego</a></li>
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/SanFrancisco.php" data-toggle="dropdown">San Francisco</a></li>
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/SantaCruz.php">Santa Cruz</a></li>
                            <li><a class="dropdown-item" href="http://jasonxiang.great-site.net/final/SantaBarbara.php">Santa Barbara</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

	<div id = "hero">
		Napa
		<form method='post' action='http://jasonxiang.great-site.net/final/Napa.php'>
			<input name="save_status" type="submit" class = "btn" value="Save"/>
			<input name="save_status" type="submit" class = "btn" value="Unsave" />
		</form>

		<?php

			$save_status = $_POST["save_status"];

			//establish connection info
			$server = "sql309.epizy.com";
			$userid = "epiz_27886961";
			$pw = "eqoCc6gwr6t";
			$db= "epiz_27886961_yourTrip";

			// Create connection
			$conn = new mysqli($server, $userid, $pw);

			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			//select the database
			$conn->select_db($db);
				
			//run a query
			if ($save_status == 'Save'){
				$sql = "UPDATE yourTrip SET saved = 1 WHERE city = 'NA'";
			}else{
				$sql = "UPDATE yourTrip SET saved = 0 WHERE city = 'NA'";
			}
			$result = $conn->query($sql);

			//close the connection	
			$conn->close();

		?>

	</div>

	<div class="banner">
		Places to visit
	</div>
	<div class="container">
		<div class="block">
				<img class="image" src="images/r15.jpg" alt="Oxbow public market">
				<div class="middle">

					<div class="text">
						<p>This sprawling marketplace features a riverside deck, food vendors, local produce & more.</p>

					</div>
				</div>
				<h3 class="city">Oxbow public market</h3>
			</div>
			<div class="block">
				<img class="image" src="images/wine.jpg" alt="Buena Vista Winery">
				<div class="middle">

					<div class="text">
						<p>Landmark circa-1857 winery with historic buildings, formal gardens, tastings & picnic spots.</p>
					</div>
				</div>
				<h3 class="city">Buena Vista Winery</h3>

			</div>
			<div class="block">
				<img class="image" src="images/car.jpg" alt="Domaine Carneros">
				<div class="middle">

					<div class="text">
						<p>Winery specializing in still & sparkling wines, with a French-inspired chateau & formal gardens. The Domaine Carneros château is a stunning landmark in wine country. </p>
					</div>
				</div>
				<h3 class="city">Domaine Carneros</h3>
			</div>
	</div>

	<div class="banner">
		Places to eat
	</div>
	<div class="container">
			<div class="block">
				<img class="image" src="images/r11.jpg" alt="The Pickled Pig">
				<div class="middle">

					<div class="text">
						<p>The Pickled Pig<br/>
							2450 Foothill Blvd suite g
						</p>

					</div>
				</div>
				<h3 class="city">The Pickled Pig</h3>
			</div>
			<div class="block">
				<img class="image" src="images/r14.jpg" alt="The Model Bakery">
				<div class="middle">

					<div class="text">
						<p>The Model Bakery<br/>
							1357 Main St, St Helena
						</p>
					</div>
				</div>
				<h3 class="city">The Model Bakery</h3>

			</div>
			<div class="block">
				<img class="image" src="images/r13.jpg" alt="Buster's Original Southern BBQ">
				<div class="middle">

					<div class="text">
						<p>Buster's Original Southern BBQ<br/>
							1207 Foothill Blvd, Calistoga</p>

					</div>
				</div>
				<h3 class="city">Buster's Original Southern BBQ</h3>
			</div>
	</div>
		<!--<div class="box">Truluck's Ocean's Finest
			<div>Seafood &amp; Crab</div>
			<div style="font-size:70%">8990 University Center Ln</div>
			<div style="font-size:70%">American, Seafood, Vegetarian Friendly</div>
		</div>
		<div class="box">Hodad's Downtown
			<div style="font-size:70%">945 Broadway</div>
			<div style="font-size:70%">American, Bar</div>
		</div>
		<div class="box">Parma Cucina Italiana
			<div style="font-size:70%">3850 5th Ave</div>
			<div style="font-size:70%">Italian, Northern-Italian, Vegetarian Friendly</div>
		</div>-->

	<div id="wrapper">
		<div class = "info" id = "temp">Loading...</div>
		<div class = "info" id="sr">Loading...</div>
		<div class = "info" id="ss">Loading...</div>
		<div class = "info" id="dayl">Loading...</div>
		<div class = "info" id = "info">Loading...</div>
	</div>

	<footer id="footer">
        <div class="container-fluid">
            <i class="footer-icon fab fa-twitter"></i>
            <i class="footer-icon fab fa-facebook"></i>
            <i class="footer-icon fab fa-instagram"></i>
            <i class="footer-icon fas fa-envelope"></i>
            <p>© Copyright 2021 California Road Trip</p>
        </div>
    </footer>

</body>
</html>
