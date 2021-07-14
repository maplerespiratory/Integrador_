<html>

<head>
	<title>Integrador Archivos Maple</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
	<div class="form-group" style="position: fixed; top:50%; left:50%; transform:translate(-50%,-50%);">
		<div class="row">
			<div>
				<img src="imagenes/logo.png" alt="">
				<BR></BR>
				<form role="form" name="login" action="php/login.php" method="POST">
					<div class="form-group">
						<label for="username">Usuario</label>
						<input type="text" class="form-control" id="username" name="username"
							placeholder="Nombre de usuario">
					</div>
					<div class="form-group">
						<label for="password">Contrase&ntilde;a</label>
						<input type="password" class="form-control" id="password" name="password"
							placeholder="Contrase&ntilde;a">
					</div>
					<center>
						<button type="submit" class="btn btn-primary">Ingresar</button>
					</center>
				</form>
			</div>
		</div>
	</div>
	<script src="js/valida_login.js"></script>
</body>

</html>