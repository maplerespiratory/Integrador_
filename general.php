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
    <div id="response" class="<?php if (!empty($tipo)) {
                                    echo $tipo . " display-block";
                                }
                                ?>"><?php if (!empty($message)) {
                                        echo $message;
                                    }
                                    ?></div><?php
                                            $sqlSelect = "SELECT  fecha_cargue, desde, hasta , indicador, id_archivo
                                            FROM historial join indicador on indicador.id = historial.area 
                                            where idvista in ($visualizar) order by historial.id desc limit 50";
                                            $result = mysqli_query($con, $sqlSelect);
                                            if (mysqli_num_rows($result) > 0) {
                                            ?><table class='tutorial-table'>
        <form action="" method="post">
            <thead>
                <th colspan="6">
                    <h4>Consulta y descarga de archivos asociados</h4>
                </th>
                <tr>
                    <th>Fecha Cargue</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Indicador</th>
                    <th>Acciones</th>
                </tr>
            </thead><?php while ($row = mysqli_fetch_array($result)) {
                        ?><tbody>
                <tr>
                    <td><?php echo $row['fecha_cargue'];
                                ?></td>
                    <td><?php echo $row['desde'];
                                ?></td>
                    <td><?php echo $row['hasta'];
                                ?></td>
                    <td><?php echo $row['indicador'];
                                ?></td>
                    <td>
                        <input type="hidden" readonly name="enviar" value="<?php echo $row['id_archivo'];
                                                                                    ?>">
                        <a href="definitivos/<?php echo $row['id_archivo'] . '.xlsx' ?>">Descargar Archivo </a>
                    </td>
                </tr>
                <?php
                                                }
                    ?>
        </form>
        </tbody>
    </table><?php
                                            }
                ?>
</div>
</div>
</div>
</div>
<script src=" assets/jquery-1.12.4-jquery.min.js"> </script>
<script src="dist/js/bootstrap.min.js"></script>
</body>

</html>