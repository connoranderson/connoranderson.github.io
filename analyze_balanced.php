<html>
<head>
	<title>MoneyMap</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
</head>
<body>

	<!-- Page Wrapper -->
	<div id="page-wrapper">

		<!-- Header -->
		<header id="header">
			<h1><a href="index.html">MoneyMap</a></h1>
			<nav id="nav">
				<ul>
					<li class="special">
						<a href="#menu" class="menuToggle"><span>Menu</span></a>
						<div id="menu">
							<ul>
								<li><a href="index.html">Home</a></li>
								<li><a href="generic.html">Generic</a></li>
								<li><a href="elements.html">Elements</a></li>
								<li><a href="#">Sign Up</a></li>
								<li><a href="#">Log In</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>
		</header>

		<!-- Main -->
		<article id="main">
			<header>
				<h2>Results</h2>
				<p>Scroll down to see your results</p>
			</header>
			<section class="wrapper style5">
				<div class="inner">

					<p>

						<center><h3>Net Worth</h3></center>

						<script src="Chart.min.js"></script>
						<h3 id = 'ylabel'>Y Label</h3>
						<canvas id="myChart" width="1200" height="600"></canvas>

						<script type="text/javascript">
							// Load relevant variables
							var start_age = parseInt("<?php echo $_POST["start"] ?>");
							var retirement_age = parseInt("<?php echo $_POST["retirement"] ?>");
							var starting_salary = parseInt("<?php echo $_POST["salary"] ?>");
							var salary_appreciation = parseFloat("<?php echo $_POST["salary_appreciation"] ?>")/100;
							var house_cost = parseInt("<?php echo $_POST["house_cost"] ?>");
							var rent = parseInt("<?php echo $_POST["rent"] ?>");
							var monthly_spending = parseInt("<?php echo $_POST["monthly_spending"] ?>");
							var investment = parseFloat("<?php echo $_POST["investment"] ?>");
							marketRate = investment / 100; // Value entered is a percentage
							var years = [];
							var duration = retirement_age-start_age; // Iterate 1 to final age							

							for (var i = 0; i < duration; i++) {
								years[i] = i + start_age;
							};



							function analyze() {
								var netWorth = [];
								var mortgage = [];
								var salary = [];
								var interest = 0;
								var hasPurchased = 0;
								netWorth[0] = 0;
								salary[0] = starting_salary;
								var housePaidOffYear = 999;
								var housePurchasedYear = 999;



								for (var i = 0; i < duration; i++) {
									if (i==0){
										netWorth[i] = 0;
										mortgage[0] = house_cost;
									}else{
										salary[i] = salary[i-1]*(1+salary_appreciation);

										if (netWorth[i-1] > mortgage[i-1]*0.2 && hasPurchased == 0){
											hasPurchased = 1;
											housePurchasedYear = i+start_age;
											netWorth[i] = netWorth[i-1] - mortgage[i-1]*0.2 + netWorth[i-1]*marketRate + salary[i] - monthly_spending - rent;
											mortgage[i] = mortgage[i-1] - mortgage[i-1]*0.2;
										}else if(hasPurchased == 1 && mortgage[i-1] > 0){ //assumes while you pay off house, you don't invest
											var interest = mortgage[i-1] * 0.0377; // average mortgage rate
											mortgage[i] = mortgage[i-1] - salary[i] + monthly_spending +interest;
											netWorth[i] = netWorth[i-1] + netWorth[i-1]*marketRate;
											if(mortgage[i] < 0){
												mortgage[i] = 0;
											}

										}else if(hasPurchased == 1){ //if house is payed off, you don't pay rent
										housePaidOffYear = i + start_age;
										netWorth[i] = netWorth[i-1] + netWorth[i-1]*marketRate + salary[i] - monthly_spending;
										mortgage[i] = mortgage[i-1];
									}else{
										netWorth[i] = netWorth[i-1]+ netWorth[i-1]*marketRate + salary[i] - monthly_spending - rent;
										mortgage[i] = mortgage[i-1];
									}
								}


							};

							var out = [];
							out[0] = years;
							out[1] = netWorth;
							out[2] = mortgage;
							out[3] = salary;

							return out;
						}
						var output = analyze();
						var orderOfMagnitude = 1;

						while(orderOfMagnitude > 0){
							if(output[1][duration-1]/(Math.pow(10,orderOfMagnitude)) < 10){
								break;
							}else{
								orderOfMagnitude += 1;
							}
						}
						if (orderOfMagnitude == 1){
							document.getElementById("ylabel").innerHTML = 'Value (Tens of Dollars)';
						}else if (orderOfMagnitude == 2){
							document.getElementById("ylabel").innerHTML = 'Value (Hundreds)';
						}else if(orderOfMagnitude == 3){
							document.getElementById("ylabel").innerHTML = 'Value (Thousands)';
						}else if(orderOfMagnitude == 4){
							document.getElementById("ylabel").innerHTML = 'Value (Ten Thousands)';
						}else if(orderOfMagnitude == 5){
							document.getElementById("ylabel").innerHTML = 'Value (Hundred Thousands)';
						}else if(orderOfMagnitude == 6){
							document.getElementById("ylabel").innerHTML = 'Value (Millions)';
						}else if(orderOfMagnitude == 7){
							document.getElementById("ylabel").innerHTML = 'Value (Ten Millions)';
						}else if(orderOfMagnitude == 8){
							document.getElementById("ylabel").innerHTML = 'Value (Hundred Millions)';
						}else{
							document.getElementById("ylabel").innerHTML = 'Value 10^' + orderOfMagnitude;
						}

						var precision = 3;

						for(var i=0; i<output[1].length; i++) {
							output[1][i] /= Math.pow(10,orderOfMagnitude-precision);
							output[1][i] = Math.round(output[1][i]);
							output[1][i] /= Math.pow(10,precision);
						}	

						var data1 = {
							labels : years,
							datasets : [
							{
								label: "Net Worth",
								fillColor : "rgba(172,194,132,0.4)",
								strokeColor : "#ACC26D",
								pointColor : "#fff",
								pointStrokeColor : "#9DB86D",
								data : output[1],
							},
							]
						};						
						</script>
						<center><h3>Age</h3></center>

					</p>

					<hr />

					<center><h3>Mortgage Value</h3></center>
					<h3 id = 'ylabel'>Mortgage</h3>
					<script src="Chart.min.js"></script>
					<canvas id="mortgageID" width="1200" height="600"></canvas>

					<script type="text/javascript">
						var data2 = {
							labels : years,
							datasets : [
							{
								label: "Mortgage",
								fillColor : "rgba(172,194,132,0.4)",
								strokeColor : "#ACC26D",
								pointColor : "#fff",
								pointStrokeColor : "#9DB86D",
								data : output[2],
							},
							]
						};

						var myChart = document.getElementById('myChart').getContext('2d');
						var mortgageChart = document.getElementById('mortgageID').getContext('2d');


						window.onload = function(){
						    
						    new Chart(myChart).Line(data1,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
							});		

						    new Chart(mortgageChart).Line(data2,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
							});	
						}

					</script>
					<center><h3>Age</h3></center>

					<hr />


					<center><h3>Salary Over Time</h3></center>
					<h3 id = 'ylabel'>Salary</h3>
					<script src="Chart.min.js"></script>
					<canvas id="salaryID" width="1200" height="600"></canvas>

					<script type="text/javascript">
						var data3 = {
							labels : years,
							datasets : [
							{
								label: "Salary",
								fillColor : "rgba(172,194,132,0.4)",
								strokeColor : "#ACC26D",
								pointColor : "#fff",
								pointStrokeColor : "#9DB86D",
								data : output[3],
							},
							]
						};

						var myChart = document.getElementById('myChart').getContext('2d');
						var mortgageChart = document.getElementById('mortgageID').getContext('2d');
						var salaryChart = document.getElementById('salaryID').getContext('2d');



						window.onload = function(){
						    
						    new Chart(myChart).Line(data1,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
							});		

						    new Chart(mortgageChart).Line(data2,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
							});	

							new Chart(salaryChart).Line(data3,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
							});	
						}

					</script>
					<center><h3>Age</h3></center>

					<hr />

					
				</div>
			</section>
		</article>

		<!-- Footer -->
		<footer id="footer">
			<ul class="icons">
				<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
				<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
				<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
				<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
			</ul>
			<ul class="copyright">
				<li>&copy; Connor Anderson</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
			</ul>
		</footer>

	</div>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrollex.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>

</body>
</html>