<?php
require_once("php/session.php");
include('header.php');
include('dbconect.php');
/* validacion de acceso para administradores de aplicativos */
#$connexion = mysqli_connect("localhost", "rhsalian", "¨root*", "rhsalian_PQR");
$tipo = $_SESSION['administrator'];
$user_name = $_SESSION['username'];
$validacion = " SELECT * FROM user WHERE username='$user_name'";
$busqueda = mysqli_query($con, $validacion);
if ($registro = mysqli_fetch_array($busqueda)) {
    $tipo = $registro['administrator'];
}
if ($tipo == '2') {
    echo "<script>alert('Acceso Restringido! No tiene permisos para ingresar a esta opción')</script>";
    echo "<script>window.location='inicio.php'</script>";
}
# ELIMINAR REGISTROS DESDE HISTORIAL Y DESDE CADA TABLA SEGUN ID DE ARCHIVO
if (isset($_POST["enviar"])) {
    $id = $_POST['enviar'];
    if (stripos($id, 'THU_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM talento_humano where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_citas where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_pqr where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_incidencia where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_oportunidad where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_pqr_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAC_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sac_pqr_proceso where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_realizados_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_realizados_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_diagnosticos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_presion where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_fallidos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_uso_de_ci where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P7_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_prom_fase where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P8_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_costo_oportunidad where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'DIA_P9_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_prefase where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAL_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_adherencia_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAL_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_adherencia_aseg where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAL_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_pac_adh_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'SAL_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_pac_adh_aseg where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_inv_mascaras where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_inv_equipos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_mtto_recuperable where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_mtto_no_recuperable where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_balance_equi where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_balance_masc where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'LOG_P7_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_recuperacion_equ where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_cart_total where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_cart_no_ven where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_edad_cart where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_recaudo_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_recaudo_pac_x_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'CAR_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_fact_pac_x_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_pac_tot where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_nue_pac where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_ordenamientos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_pac_egre where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_pac_prefases where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_pac_pte_aut where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
    if (stripos($id, 'MOD_P7_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM mod_motivo_egre where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = "success";
            $message = "Registros asociados al archivo: " . $id . " eliminados correctamente!";
        } else {
            $type = "error";
            $message = "Hubo un problema al eliminar el archivo";
        }
    }
}
?>
<div class="container">
    <div id="response" class="<?php if (!empty($type)) {
                                    echo $type . " display-block";
                                }
                                ?>"><?php if (!empty($message)) {
                                        echo $message;
                                        switch ($_SESSION["idvista"]) {
                                            case 1:
                                                $id_rol = 1;
                                            case 2:
                                                $id_rol = 2;
                                            case 3:
                                                $id_rol = 3;
                                            case 4:
                                                $id_rol = 4;
                                            case 5:
                                                $id_rol = 5;
                                                break;
                                        }
                                    }
                                    ?></div><?php
                                            $sqlSelect = "SELECT  fecha_cargue, desde, hasta , indicador, id_archivo FROM 
                                    historial join indicador on indicador.id = historial.area order by historial.id desc";
                                            $result = mysqli_query($con, $sqlSelect);
                                            if (mysqli_num_rows($result) > 0) {
                                            ?><table class='tutorial-table'>
            <form action="" method="post">
                <thead>
                    <th colspan="6">
                        <h4>Listado general de archivos cargados</h4>
                    </th>
                    <tr>
                        <th>Fecha Cargue</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Indicador</th>
                        <th>Eliminar Archivo</th>
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
                                <input type="submit" style="width: 110px;" name="enviar" value="<?php echo $row['id_archivo'];
                                                                                                ?>">
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
<script src="assets/jquery-1.12.4-jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
</body>

</html>