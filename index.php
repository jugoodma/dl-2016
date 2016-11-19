<!DOCTYPE html>

<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<meta charset="UTF-8">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
		<!-- https://www.bootstrapcdn.com/bootswatch/ for themes -->
		<link type="image/x-icon" rel="shortcut icon" href="favicon.ico"/>
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="page.css"/>
		
		<title>Search Data!</title>
	</head>
	
	<body style="font-family: 'Quicksand', sans-serif; background-color: #EDE1D1;">
		<div class="main" style="margin-top: 100px;">
			<h1 style="text-align: center;">Search Our Data!</h1>
		</div>
		<div style="margin-top: 50px;">
			<p id="output" style="text-align: center;"></p>
		</div>
		
		<script>		
		var popDen, temperature, aqi, humidity, health, wind, precip, uv;
		health = 33.907; // This is a constant as our web app works only for the U.S. (for now)
		var result;
		
		var weather = httpGet("http://api.wunderground.com/api/f1788c000dba0d84/conditions/q/20742.json");
		var AQ = httpGet("http://www.airnowapi.org/aq/forecast/zipCode/?format=application/json&zipCode=20742&date=2016-11-18&distance=25&API_KEY=69EA226A-9582-4679-82F4-6B280BDD5C85");
		var state = httpGet("https://maps.googleapis.com/maps/api/geocode/json?address=20742&key=AIzaSyAe4pqY96_TwGm-j9iqf3vlAC9IwUYDc2Y");
		var zip = httpGet("states.json");
		obj1 = JSON.parse(weather);
		obj2 = JSON.parse(AQ);
		obj3 = JSON.parse(state);
		obj4 = JSON.parse(zip);

		//from weather
		humidity = parseInt(obj1.current_observation.relative_humidity);
		wind = obj1.current_observation.wind_mph / 253 * 100;
		temperature = obj1.current_observation.temp_c;
			if (temperature >= 30 && temperature <= 37) {
				temperature = 100;
			} else if (temperature > 37) {
				temperature = 100 - ((temperature - 37) / 84) * 100;
			} else {
				temperature = 100 - ((30 - temperature) / 50) * 100;
			}
		precip = obj1.current_observation.precip_today_in / 50 * 100;
		uv = 100 - (obj1.current_observation.UV / 11) * 100;
		
		//from AQ
		aqi = obj2.AQI / 500 * 100;
		
		//from state and zip
		popDen = obj3.results[0].address_components[3].long_name;
		popDen = obj4.state[popDen] / 9292816.65 * 100;
		
		result = (popDen * 0.20) + (temperature * 0.10) + (aqi * 0.20) + (humidity * 0.2) + (health * 0.10) + (wind * 0.10) + (precip * 0.05) + (uv * 0.05);
		document.getElementById("output").innerHTML = "Estimated Risk of Airborne Illness Affecting the Population: " + result + "%";

		function httpGet(theUrl)
		{
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		</script>
	</body>
</html>