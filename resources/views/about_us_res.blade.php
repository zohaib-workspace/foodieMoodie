<html>
<head>
	<title>About Us</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    /* Global Styles */
body {
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
}

/* Header Styles */
header {
	background-color: #333;
	color: #fff;
	padding: 10px;
}

/* Main Styles */
main {
	margin: 20px;
}
section {
	margin-bottom: 20px;
}
h2 {
	font-size: 24px;
	margin-bottom: 10px;
}

/* Responsive Styles */
@media only screen and (max-width: 600px) {
	h1 {
		font-size: 36px;
	}
	h2 {
		font-size: 20px;
	}
	button {
		font-size: 14px;
	}
}

    </style>
<body>
	
	<main>
		<section>

			<p>        <?php echo $about ['description'] ?>
</p>
		</section>
		
	</main>
	
</body>
</html>
