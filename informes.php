<?php
require_once("php/session.php");
include('header.php');
include('dbconect.php');
/* validacion de acceso para administradores de aplicativos */
$tipo = $_SESSION['administrator'];
$user_name = $_SESSION['username'];
$visualizar = $_SESSION["visualizar"];
$validacion = " SELECT * FROM user WHERE username='$user_name'";
$busqueda = mysqli_query($con, $validacion);
if ($registro = mysqli_fetch_array($busqueda)) {
    $tipo = $registro['administrator'];
    $ver = $registro['visualizar'];
}
if ($ver == '') {
    echo "<script>alert('Acceso Restringido! No tiene permisos para ingresar a esta opción')</script>";
    echo "<script>window.location='inicio.php'</script>";
}
# ELIMINAR REGISTROS DESDE HISTORIAL Y DESDE CADA TABLA SEGUN ID DE ARCHIVO
?>
<div class="container">
    <iframe width="1140" height="700"
        src="https://app.powerbi.com/reportEmbed?reportId=c75130bc-f277-4f42-9353-335851fef17b&groupId=18590f33-f5aa-4ba2-b573-b5f2ef77219d&autoAuth=true&ctid=4cb616a6-b7d1-4b7b-8e45-9b58c12eb55c&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly93YWJpLWNhbmFkYS1jZW50cmFsLXJlZGlyZWN0LmFuYWx5c2lzLndpbmRvd3MubmV0LyJ9"
        frameborder="0" allowFullScreen="true"></iframe>
</div>
</div>
</div>
</div>
<script src=" assets/jquery-1.12.4-jquery.min.js"> </script>
<script src="dist/js/bootstrap.min.js"></script>
</body>

</html>