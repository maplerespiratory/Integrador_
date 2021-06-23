<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Integrador Maple</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/sticky-footer-navbar.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link rel="icon" href="imagenes/favicon.ico" type="image/gif" sizes="16x16">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <a href="#" class="navbar-brand">
                <img src="imagenes/logo.png" height="60" alt="Maple Respiratory">
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="inicio.php" class="nav-item nav-link active">Inicio</a>
                    <a href="plantillas.php" class="nav-item nav-link">Plantillas </a>
                    <!-- <a href="general.php" class="nav-item nav-link">Consultar Archivos</a>
                    <a href="informes.php" class="nav-item nav-link">Informes</a>
                    <a href="administracion.php" class="nav-item nav-link">Administrador</a> -->
                </div>
                <div class="navbar-nav ml-auto">
                    <a href="php/logout.php">
                        <button type="button"
                            class="btn btn-danger"><?php echo 'Cerrar Sesión: ' . $_SESSION["fullname"] ?></button>
                    </a>
                </div>
            </div>
        </nav>
    </header>