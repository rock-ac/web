<?php ?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rock Anti-Cheat - API Demo</title>

    <script src="../application/public/js/jquery.min.js"></script>
	<script src="../application/public/js/jquery.form.min.js"></script>
	<script src="../application/public/js/jquery.flot.min.js"></script>
	<script src="../application/public/js/jquery.flot.time.min.js"></script>

    <link type="image/png" sizes="16x16" rel="icon" href="/application/public/img/ico/16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="/application/public/img/ico/32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="/application/public/img/ico/96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="/application/public/img/ico/120.png">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@700,600,500&display=swap");

        * {
            margin: 0;
            padding: 0;
        }

        html {
            font-family: "Inter", sans-serif;

            font-weight: 700;
        }

        .container {
			display:flex;

			margin-left: auto;
			margin-right: auto;
			margin-top: 5%;
			margin-bottom: auto;

			align-items: center;
			justify-content: center;
			flex-direction: column;

			width: 50%;
		}
        .item {
			text-align: center;
			padding: 1%;
			width: 30%;

			margin-bottom: 5%;
		}
        .header {
			opacity: 0.8;
			margin-bottom: 5%;
			text-transform: uppercase;
		}
        .brand a img{
			transition: 0.2s;
		}
		.brand a img:hover {
			opacity: 0.75;
			transition: 0.2s;
		}
        button {
            width: 20%;
            border-radius: 9px;
        }
    </style>
</head>
<body>
    <div class="container" id="form">
        <div class="item brand">
			<a href="/">
				<img src="../application/public/img/logo.svg"></img>
			</a>
		</div>
        <p class="header">Rock Anti-Cheat - API Demo</p>
			
		<input class="item" disabled type="hidden" name="api-key" value="test">
		<input class="item" disabled type="text" name="request" value="/api/getUser.php">
        <input class="item" type="text" id="params" placeholder="Parameters">
		<button id="btnSubmit" class="item" type="submit">Send</button>

		<div class="item"><input disabled id="output" type="text" placeholder="Output"></input></div>
	</div>
    <script>
        $(document).ready(function() {
            $("#btnSubmit").click(function(){
                var value = $('#params').val();

                $.ajax({ 
                    url: '/api/getUser.php?' + value,
                    dataType: 'text',
                    success: function(data) {
                        $('#output').val(data);
                        $('#btnSubmit').prop('disabled', true);
                        setTimeout("$('#btnSubmit').prop('disabled', false)", 1500);
                    }
                });
            }); 
        });
		</script>
</body>
</html>