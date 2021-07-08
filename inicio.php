<?php
require_once 'php/session.php';
date_default_timezone_set('America/Bogota');
require 'php/login.php';
include 'dbconect.php';
include 'header.php';
require_once 'vendor/php-excel-reader/excel_reader2.php';
require_once 'vendor/SpreadsheetReader.php';
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
/* use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Phpmailer/PHPMailer-master/src/Exception.php';
require 'Phpmailer/PHPMailer-master/src/PHPMailer.php';  
require 'Phpmailer/PHPMailer-master/src/SMTP.php'; */
if (isset($_POST['import'])) {
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    if (in_array($_FILES['file']['type'], $allowedFileType)) {
        $targetPath = 'subidas/' . $_FILES['file']['name'];
        $nombre_plantilla = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        $Reader = new SpreadsheetReader($targetPath);
        $area = $_POST['area'];
        $sheetCount = count($Reader->sheets());
        ##### SERVICIO AL CLIENTE - INICIO
        if (
            $area == '3' and
            $nombre_plantilla == 'SIS - VALIDACION DE DERECHOS MASIVA.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SIS_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_doc = '';
                    if (isset($Row[0])) {
                        $tipo_doc = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $documento = '';
                    if (isset($Row[1])) {
                        $documento = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $aseguradora = '';
                    if (isset($Row[2])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[2]);
                    }
                    if (
                        !empty($tipo_doc) ||
                        !empty($documento) ||
                        !empty($aseguradora)
                    ) {
                        $query =
                            "insert into agendamiento(id_archivo,tipo_doc,documento,aseguradora,id_asegurador,estado,id_area) 
                        values('" .
                            $codigo .
                            "','" .
                            $tipo_doc .
                            "','" .
                            $documento .
                            "','" .
                            $aseguradora .
                            "', 
                        CASE 
                            WHEN ASEGURADORA = 'ENTIDAD PARTICULAR' THEN 1
                            WHEN ASEGURADORA = 'EPS FAMISANAR  S.A.S' THEN 2
                            WHEN ASEGURADORA = 'MEDIMAS EPS-S S.A.S.' THEN 3
                            WHEN ASEGURADORA = 'NUEVA EMPRESA PROMOTORA DE SALUD  SA. NUEVA EPS SA' THEN 4
                            WHEN ASEGURADORA = 'SALUD TOTAL EPS' THEN 5
                            WHEN ASEGURADORA = 'SANITAS EPS' THEN 6
                        END, 'Solicitada',3
                        )";
                        $delete =
                            "delete from agendamiento where tipo_doc = 'tipo_doc' and documento = 'documento' and aseguradora = 'aseguadora'";
                        $cabecera =
                            "INSERT INTO integrador (id_archivo,id_proceso,estado,fecha) 
                        VALUES ('" .
                            $codigo .
                            "',3,0,now())";
                        $procesar = mysqli_query($con, $cabecera);
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,3,0 from agendamiento 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '4' and
            $nombre_plantilla == 'SIS - CARGUE PACIENTES.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SIS_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_doc = '';
                    if (isset($Row[0])) {
                        $tipo_doc = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $documento = '';
                    if (isset($Row[1])) {
                        $documento = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $nombres = '';
                    if (isset($Row[2])) {
                        $nombres = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $direccion = '';
                    if (isset($Row[3])) {
                        $direccion = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $telefono = '';
                    if (isset($Row[4])) {
                        $telefono = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $celular = '';
                    if (isset($Row[5])) {
                        $celular = mysqli_real_escape_string($con, $Row[5]);
                    }
                    $aseguradora = '';
                    if (isset($Row[6])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[6]);
                    }
                    $tipo_afiliado = '';
                    if (isset($Row[7])) {
                        $tipo_afiliado = mysqli_real_escape_string(
                            $con, $Row[7]
                        );
                    }
                    $email = '';
                    if (isset($Row[8])) {
                        $email = mysqli_real_escape_string($con, $Row[8]);
                    }
                    if (
                        !empty($tipo_doc) ||
                        !empty($documento) ||
                        !empty($nombres) ||
                        !empty($direccion) ||
                        !empty($telefono) ||
                        !empty($celular) ||
                        !empty($aseguradora) ||
                        !empty($tipo_afiliado) ||
                        !empty($email)
                    ) {
                        $query =
                            "INSERT INTO pacientes (
                        ID_ARCHIVO,TIPO_DOC,DOCUMENTO,NOMBRES,DIRECCION,TELEFONO,CELULAR,ASEGURADORA,TIPO_AFILIADO,EMAIL,ID_PROCESO)
                        VALUES ('" .
                            $codigo .
                            "','" .
                            $tipo_doc .
                            "','" .
                            $documento .
                            "','" .
                            $nombres .
                            "','" .
                            $direccion .
                            "','" .
                            $telefono .
                            "','" .
                            $celular .
                            "','" .
                            $aseguradora .
                            "','" .
                            $tipo_afiliado .
                            "','" .
                            $email .
                            "',4)";
                        $delete =
                            "delete from pacientes where tipo_doc = 'tipo_doc' and documento = 'documento'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,4,0 from pacientes 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
            $cabecera = "INSERT INTO integrador (id_archivo,id_proceso,estado,fecha) 
            VALUES ((SELECT ID_ARCHIVO FROM pacientes WHERE ID_ARCHIVO = '$codigo' group by ID_ARCHIVO),4,0,now())";
            $procesar = mysqli_query($con, $cabecera);
        }
        if (
            $area == '5' and
            $nombre_plantilla ==
                'LOG - CARGUE DE DOCUMENTOS LEGALES GOMEDISYS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $documento = '';
                    if (isset($Row[0])) {
                        $documento = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $ingreso = '';
                    if (isset($Row[1])) {
                        $ingreso = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $sede = '';
                    if (isset($Row[2])) {
                        $sede = mysqli_real_escape_string($con, $Row[2]);
                    }
                    if (
                        !empty($documento) ||
                        !empty($ingreso) ||
                        !empty($sede)
                    ) {
                        $query =
                            "insert into documentos (id_archivo,documento,ingreso,sede,id_area) 
                        values('" .
                            $codigo .
                            "','" .
                            $documento .
                            "','" .
                            $ingreso .
                            "','" .
                            $sede .
                            "',5)";
                        $delete =
                            "delete from documentos where documento = 'documento' and ingreso = 'ingreso'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,5,0 from documentos 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '7' and
            $nombre_plantilla == 'LOG - CARGUE DE DOCUMENTOS LEGALES NA-AT.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_doc = '';
                    if (isset($Row[0])) {
                        $tipo_doc = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $documento = '';
                    if (isset($Row[1])) {
                        $documento = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $nombres = '';
                    if (isset($Row[2])) {
                        $nombres = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $direccion = '';
                    if (isset($Row[3])) {
                        $direccion = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $telefono = '';
                    if (isset($Row[4])) {
                        $telefono = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $celular = '';
                    if (isset($Row[5])) {
                        $celular = mysqli_real_escape_string($con, $Row[5]);
                    }
                    $aseguradora = '';
                    if (isset($Row[6])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[6]);
                    }
                    $tipo_afiliado = '';
                    if (isset($Row[7])) {
                        $tipo_afiliado = mysqli_real_escape_string(
                            $con,
                            $Row[7]
                        );
                    }
                    $email = '';
                    if (isset($Row[8])) {
                        $email = mysqli_real_escape_string($con, $Row[8]);
                    }
                    if (
                        !empty($tipo_doc) ||
                        !empty($documento) ||
                        !empty($nombres) ||
                        !empty($direccion) ||
                        !empty($telefono) ||
                        !empty($celular) ||
                        !empty($aseguradora) ||
                        !empty($tipo_afiliado) ||
                        !empty($email)
                    ) {
                        $query =
                            "insert into pacientes (tipo_doc,documento,nombres,direccion,telefono,celular,aseguradora,tipo_afiliado,email,activo,id_proceso) 
                        values('" .
                            $tipo_doc .
                            "','" .
                            $documento .
                            "','" .
                            $nombres .
                            "','" .
                            $direccion .
                            "','" .
                            $telefono .
                            "','" .
                            $celular .
                            "','" .
                            $aseguradora .
                            "','" .
                            $tipo_afiliado .
                            "','" .
                            $email .
                            "',1,1)";
                        $delete =
                            "delete from pacientes where tipo_doc = 'tipo_doc'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,7,0 from pacientes 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '9' and
            $nombre_plantilla == 'DIA - LECTURA DE TARJETA AIRVIEW.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $id_airview = '';
                    if (isset($Row[0])) {
                        $id_airview = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $documento = '';
                    if (isset($Row[1])) {
                        $documento = mysqli_real_escape_string($con, $Row[1]);
                    }
                    if (
                        !empty($id_airview) ||
                        !empty($documento) 
                    ) {
                        $query =
                            "insert into lectura_tarjeta (id_archivo,id_airview,documento,id_area) 
                        values('" .
                            $codigo .
                            "','" .
                            $id_airview .
                            "','" .
                            $documento .
                            "','" .
                            9 .
                            "')";
                        $delete =
                            "delete from lectura_tarjeta where id_airview = 'id_airview'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,9,0 from lectura_tarjeta 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if ($area == '6' and $nombre_plantilla == 'DIA - INACTIVA PACIENTES AIRVIEW.xlsx') {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $id_airview = '';
                    if (isset($Row[0])) {
                        $id_airview = mysqli_real_escape_string($con, $Row[0]);
                    }
                    if (
                        !empty($id_airview) 
                    ) {
                        $query =
                            "insert into airview (id_archivo,id_airview,id_area) 
                        values('" .
                            $codigo .
                            "','" .
                            $id_airview .
                            "','" .
                            6 .
                            "')";
                        $delete =
                            "delete from airview where id_airview = 'id_airview'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,FECHA,AREA,IDVISTA,ID_PROCESO,ESTADO)  
                        (select distinct ID_ARCHIVO,min(FECHA),ID_AREA,ID_AREA,6,0 from airview 
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial)
                        group by id_archivo);";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        ##### SERVICIO AL CLIENTE - FIN
        ##### TALENTO HUMANO - INICIO
        if ($area == '18' and $nombre_plantilla == 'TH - TALENTO HUMANO.xlsx') {
            $random = random_int(111111, 999999);
            $codigo = 'THU_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $ingresos = '';
                    if (isset($Row[0])) {
                        $ingresos = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $voluntarios = '';
                    if (isset($Row[1])) {
                        $voluntarios = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $con_justa_causa = '';
                    if (isset($Row[2])) {
                        $con_justa_causa = mysqli_real_escape_string(
                            $con,
                            $Row[2]
                        );
                    }
                    $sin_justa_causa = '';
                    if (isset($Row[3])) {
                        $sin_justa_causa = mysqli_real_escape_string(
                            $con,
                            $Row[3]
                        );
                    }
                    $termino_cto = '';
                    if (isset($Row[4])) {
                        $termino_cto = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $vacantes_p = '';
                    if (isset($Row[5])) {
                        $vacantes_p = mysqli_real_escape_string($con, $Row[5]);
                    }
                    $requisiciones_p = '';
                    if (isset($Row[6])) {
                        $requisiciones_p = mysqli_real_escape_string(
                            $con,
                            $Row[6]
                        );
                    }
                    $procesos_d = '';
                    if (isset($Row[7])) {
                        $procesos_d = mysqli_real_escape_string($con, $Row[7]);
                    }
                    $resultado_p = '';
                    if (isset($Row[8])) {
                        $resultado_p = mysqli_real_escape_string($con, $Row[8]);
                    }
                    $resultado_n = '';
                    if (isset($Row[9])) {
                        $resultado_n = mysqli_real_escape_string($con, $Row[9]);
                    }
                    $pendiente_m = '';
                    if (isset($Row[10])) {
                        $pendiente_m = mysqli_real_escape_string(
                            $con,
                            $Row[10]
                        );
                    }
                    $pendiente_r = '';
                    if (isset($Row[11])) {
                        $pendiente_r = mysqli_real_escape_string(
                            $con,
                            $Row[11]
                        );
                    }
                    $recuperados = '';
                    if (isset($Row[12])) {
                        $recuperados = mysqli_real_escape_string(
                            $con,
                            $Row[12]
                        );
                    }
                    $aislamiento = '';
                    if (isset($Row[13])) {
                        $aislamiento = mysqli_real_escape_string(
                            $con,
                            $Row[13]
                        );
                    }
                    $ausentismo_d = '';
                    if (isset($Row[14])) {
                        $ausentismo_d = mysqli_real_escape_string(
                            $con,
                            $Row[14]
                        );
                    }
                    /* $desde = "";
                    if (isset($Row[15])) {
                        $desde = mysqli_real_escape_string($con, $Row[15]);
                    }
                    $hasta = "";
                    if (isset($Row[16])) {
                        $hasta = mysqli_real_escape_string($con, $Row[16]);
                    } */
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($area) ||
                        !empty($ingresos) ||
                        !empty($voluntarios) ||
                        !empty($con_justa_causa) ||
                        !empty($sin_justa_causa) ||
                        !empty($termino_cto) ||
                        !empty($vacantes_p) ||
                        !empty($requisiciones_p) ||
                        !empty($procesos_d) ||
                        !empty($resultado_p) ||
                        !empty($resultado_n) ||
                        !empty($pendiente_m) ||
                        !empty($pendiente_r) ||
                        !empty($recuperados) ||
                        !empty($aislamiento) ||
                        !empty($ausentismo_d)
                    ) {
                        $query =
                            "insert into talento_humano (id_archivo,desde,hasta,area,ingresos,voluntarios,con_justa_causa,sin_justa_causa,termino_cto,
                        vacantes_p,requisiciones_p,procesos_d,resultado_p,resultado_n,pendiente_m,pendiente_r,recuperados,aislamiento,ausentismo_d) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $ingresos .
                            "','" .
                            $voluntarios .
                            "','" .
                            $con_justa_causa .
                            "'
                        ,'" .
                            $sin_justa_causa .
                            "','" .
                            $termino_cto .
                            "','" .
                            $vacantes_p .
                            "','" .
                            $requisiciones_p .
                            "','" .
                            $procesos_d .
                            "'
                        ,'" .
                            $resultado_p .
                            "','" .
                            $resultado_n .
                            "','" .
                            $pendiente_m .
                            "','" .
                            $pendiente_r .
                            "','" .
                            $recuperados .
                            "'
                        ,'" .
                            $aislamiento .
                            "','" .
                            $ausentismo_d .
                            "')";
                        $delete = "delete from talento_humano where ingresos = 0 and voluntarios = 0 and con_justa_causa = 0 and sin_justa_causa = 0
                        and termino_cto = 0 and vacantes_p = 0 and requisiciones_p = 0 and procesos_d = 0 and resultado_p = 0 and resultado_n = 0 and
                        pendiente_m = 0 and pendiente_r = 0 and recuperados = 0 and aislamiento = 0 and ausentismo_d = 0";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA,IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA,IDVISTA from talento_humano
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        ##### TALENTO HUMANO - FIN
        ##### DX - INICIO
        if (
            $area == '7' and
            $nombre_plantilla == 'DX - ESTUDIOS DE DIAGNOSTICO POR SEDE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $sede = '';
                    if (isset($Row[0])) {
                        $sede = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $basal = '';
                    if (isset($Row[1])) {
                        $basal = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $poligrafia = '';
                    if (isset($Row[2])) {
                        $poligrafia = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $titulacion = '';
                    if (isset($Row[3])) {
                        $titulacion = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $autotitulacion = '';
                    if (isset($Row[4])) {
                        $autotitulacion = mysqli_real_escape_string(
                            $con,
                            $Row[4]
                        );
                    }
                    $noche_partida = '';
                    if (isset($Row[5])) {
                        $noche_partida = mysqli_real_escape_string(
                            $con,
                            $Row[5]
                        );
                    }
                    $desde = '';
                    if (isset($Row[6])) {
                        $desde = mysqli_real_escape_string($con, $Row[6]);
                    }
                    $hasta = '';
                    if (isset($Row[7])) {
                        $hasta = mysqli_real_escape_string($con, $Row[7]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($sede) ||
                        !empty($basal) ||
                        !empty($poligrafia) ||
                        !empty($titualacion) ||
                        !empty($autotitulacion) ||
                        !empty($noche_partida)
                    ) {
                        $query =
                            "insert into dx_est_realizados_sede (id_archivo,desde,hasta,sede,area,basal,poligrafia,titulacion,
                        autotitulacion,noche_partida) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $sede .
                            "',
                        '" .
                            $area .
                            "','" .
                            $basal .
                            "','" .
                            $poligrafia .
                            "'
                            ,'" .
                            $titulacion .
                            "','" .
                            $autotitulacion .
                            "','" .
                            $noche_partida .
                            "')";
                        $delete =
                            "delete from dx_est_realizados_sede where sede = 'sede'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_est_realizados_sede
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '6' and
            $nombre_plantilla == 'DX - ESTUDIOS DE DIAGNOSTICO POR EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $basal = '';
                    if (isset($Row[1])) {
                        $basal = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $poligrafia = '';
                    if (isset($Row[2])) {
                        $poligrafia = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $titulacion = '';
                    if (isset($Row[3])) {
                        $titulacion = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $autotitulacion = '';
                    if (isset($Row[4])) {
                        $autotitulacion = mysqli_real_escape_string(
                            $con,
                            $Row[4]
                        );
                    }
                    $noche_partida = '';
                    if (isset($Row[5])) {
                        $noche_partida = mysqli_real_escape_string(
                            $con,
                            $Row[5]
                        );
                    }
                    $desde = '';
                    if (isset($Row[6])) {
                        $desde = mysqli_real_escape_string($con, $Row[6]);
                    }
                    $hasta = '';
                    if (isset($Row[7])) {
                        $hasta = mysqli_real_escape_string($con, $Row[7]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($basal) ||
                        !empty($poligrafia) ||
                        !empty($titulacion) ||
                        !empty($autotitulacion) ||
                        !empty($noche_partida)
                    ) {
                        $query =
                            "insert into dx_est_realizados_eps (id_archivo,desde,hasta,area,aseguradora,basal,poligrafia,
                        titulacion,autotitulacion,noche_partida) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $basal .
                            "','" .
                            $poligrafia .
                            "',
                            '" .
                            $titulacion .
                            "','" .
                            $autotitulacion .
                            "','" .
                            $noche_partida .
                            "')";
                        $delete =
                            "delete from dx_est_realizados_eps where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_est_realizados_eps
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '3' and
            $nombre_plantilla == 'DX - ESTUDIOS DIAGNOSTICOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P3_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_estudio = '';
                    if (isset($Row[0])) {
                        $tipo_estudio = mysqli_real_escape_string(
                            $con,
                            $Row[0]
                        );
                    }
                    $estudios_diag = '';
                    if (isset($Row[1])) {
                        $estudios_diag = mysqli_real_escape_string(
                            $con,
                            $Row[1]
                        );
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($tipo_estudio) ||
                        !empty($estudios_diag)
                    ) {
                        $query =
                            "insert into dx_est_diagnosticos (id_archivo,desde,hasta,area,tipo_estudio,estudios_diag) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $tipo_estudio .
                            "','" .
                            $estudios_diag .
                            "')";
                        $delete =
                            "delete from dx_est_diagnosticos where tipo_estudio = 'tipo_estudio'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_est_diagnosticos
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '5' and
            $nombre_plantilla == 'DX - ESTUDIOS PARA DEFINIR PRESION.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P4_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_estudio = '';
                    if (isset($Row[0])) {
                        $tipo_estudio = mysqli_real_escape_string(
                            $con,
                            $Row[0]
                        );
                    }
                    $estudios_presion = '';
                    if (isset($Row[1])) {
                        $estudios_presion = mysqli_real_escape_string(
                            $con,
                            $Row[1]
                        );
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($tipo_estudio) ||
                        !empty($estudios_diag)
                    ) {
                        $query =
                            "insert into dx_est_presion (id_archivo,desde,hasta,area,tipo_estudio,estudios_presion) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $tipo_estudio .
                            "','" .
                            $estudios_presion .
                            "')";
                        $delete =
                            "delete from dx_est_presion where tipo_estudio = 'tipo_estudio'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_est_presion
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '4' and
            $nombre_plantilla == 'DX - ESTUDIOS FALLIDOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P5_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_estudio = '';
                    if (isset($Row[0])) {
                        $tipo_estudio = mysqli_real_escape_string(
                            $con,
                            $Row[0]
                        );
                    }
                    $estudios_fallidos = '';
                    if (isset($Row[1])) {
                        $estudios_fallidos = mysqli_real_escape_string(
                            $con,
                            $Row[1]
                        );
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($tipo_estudio) ||
                        !empty($estudios_fallidos)
                    ) {
                        $query =
                            "insert into dx_est_fallidos (id_archivo,desde,hasta,area,tipo_estudio,estudios_fallidos) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $tipo_estudio .
                            "','" .
                            $estudios_fallidos .
                            "')";
                        $delete =
                            "delete from dx_est_fallidos where tipo_estudio = 'tipo_estudio'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_est_fallidos
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '1' and
            $nombre_plantilla == 'DX - PORCENTAJE DE USO DE CI.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P6_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $laboratorio = '';
                    if (isset($Row[1])) {
                        $laboratorio = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $poligrafia = '';
                    if (isset($Row[2])) {
                        $poligrafia = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $autotitulacion = '';
                    if (isset($Row[3])) {
                        $autotitulacion = mysqli_real_escape_string(
                            $con,
                            $Row[3]
                        );
                    }
                    $desde = '';
                    if (isset($Row[4])) {
                        $desde = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $hasta = '';
                    if (isset($Row[5])) {
                        $hasta = mysqli_real_escape_string($con, $Row[5]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($laboratorio) ||
                        !empty($poligrafia) ||
                        !empty($autotitulacion)
                    ) {
                        $query =
                            "insert into dx_uso_de_ci (id_archivo,desde,hasta,area,aseguradora,laboratorio,poligrafia,autotitulacion) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "',
                        '" .
                            $laboratorio .
                            "','" .
                            $poligrafia .
                            "','" .
                            $autotitulacion .
                            "')";
                        $delete =
                            "delete from dx_uso_de_ci where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_uso_de_ci
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '9' and
            $nombre_plantilla == 'DX - PACIENTES POR FASE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P7_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $prefase_2 = '';
                    if (isset($Row[2])) {
                        $prefase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $fase_2 = '';
                    if (isset($Row[3])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $prefase_3 = '';
                    if (isset($Row[4])) {
                        $prefase_3 = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $ordenamientos = '';
                    if (isset($Row[5])) {
                        $ordenamientos = mysqli_real_escape_string(
                            $con,
                            $Row[5]
                        );
                    }
                    $desde = '';
                    if (isset($Row[6])) {
                        $desde = mysqli_real_escape_string($con, $Row[6]);
                    }
                    $hasta = '';
                    if (isset($Row[7])) {
                        $hasta = mysqli_real_escape_string($con, $Row[7]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($fase_1) ||
                        !empty($prefase_2) ||
                        !empty($fase_2) ||
                        !empty($prefase_3) ||
                        !empty($ordenamientos)
                    ) {
                        $query =
                            "insert into dx_prom_fase (id_archivo,desde,hasta,aseguradora,area,fase_1,prefase_2,fase_2,
                        prefase_3,ordenamientos) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $aseguradora .
                            "','" .
                            $area .
                            "','" .
                            $fase_1 .
                            "','" .
                            $prefase_2 .
                            "',
                            '" .
                            $fase_2 .
                            "','" .
                            $prefase_3 .
                            "','" .
                            $ordenamientos .
                            "')";
                        $delete =
                            "delete from dx_prom_fase where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_prom_fase
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '2' and
            $nombre_plantilla == 'DX - COSTO NO OPORTUNIDAD.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P8_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $fase_2 = '';
                    if (isset($Row[2])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $desde = '';
                    if (isset($Row[3])) {
                        $desde = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $hasta = '';
                    if (isset($Row[4])) {
                        $hasta = mysqli_real_escape_string($con, $Row[4]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($fase_1) ||
                        !empty($fase_2)
                    ) {
                        $query =
                            "insert into dx_costo_oportunidad (id_archivo,desde,hasta,aseguradora,fase_1,fase_2,area) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $aseguradora .
                            "','" .
                            $fase_1 .
                            "','" .
                            $fase_2 .
                            "','" .
                            $area .
                            "')";
                        $delete =
                            "delete from dx_costo_oportunidad where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_costo_oportunidad
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if ($area == '8' and $nombre_plantilla == 'DX - TIEMPO POR FASE.xlsx') {
            $random = random_int(111111, 999999);
            $codigo = 'DIA_P9_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $fase_2 = '';
                    if (isset($Row[2])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $desde = '';
                    if (isset($Row[3])) {
                        $desde = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $hasta = '';
                    if (isset($Row[4])) {
                        $hasta = mysqli_real_escape_string($con, $Row[4]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($fase_1) ||
                        !empty($fase_2)
                    ) {
                        $query =
                            "insert into dx_prefase (id_archivo,desde,hasta,area,aseguradora,fase_1,fase_2) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "',
                            '" .
                            $fase_1 .
                            "','" .
                            $fase_2 .
                            "')";
                        $delete =
                            "delete from dx_prefase where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from dx_prefase
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        ##### DX - FIN
        ##### SALUD - INICIO
        if (
            $area == '17' and
            $nombre_plantilla == 'SAL - PROMEDIO ADHERENCIA SEDE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SAL_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $sede = '';
                    if (isset($Row[0])) {
                        $sede = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $adherencia = '';
                    if (isset($Row[1])) {
                        $adherencia = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($sede) ||
                        !empty($adherencia)
                    ) {
                        $query =
                            "insert into sal_adherencia_sede (id_archivo,desde,hasta,area,sede,adherencia) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $sede .
                            "','" .
                            $adherencia .
                            "')";
                        $delete =
                            "delete from sal_adherencia_sede where sede = 'sede'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from sal_adherencia_sede
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '16' and
            $nombre_plantilla == 'SAL - PROMEDIO ADHERENCIA EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SAL_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $adherencia = '';
                    if (isset($Row[1])) {
                        $adherencia = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($adherencia)
                    ) {
                        $query =
                            "insert into sal_adherencia_aseg (id_archivo,desde,hasta,area,aseguradora,adherencia) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $adherencia .
                            "')";
                        $delete =
                            "delete from sal_adherencia_aseg where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from sal_adherencia_aseg
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '15' and
            $nombre_plantilla == 'SAL - PACIENTES ADHERIDOS TTO SEDE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SAL_P3_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $sede = '';
                    if (isset($Row[0])) {
                        $sede = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $adherencia = '';
                    if (isset($Row[1])) {
                        $adherencia = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($adherencia)
                    ) {
                        $query =
                            "insert into sal_pac_adh_sede (id_archivo,desde,hasta,area,sede,adherencia) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $sede .
                            "','" .
                            $adherencia .
                            "')";
                        $delete =
                            "delete from sal_pac_adh_sede where sede = 'sede'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from sal_pac_adh_sede
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '14' and
            $nombre_plantilla == 'SAL - PACIENTES ADHERIDOS TTO EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'SAL_P4_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $adherencia = '';
                    if (isset($Row[1])) {
                        $adherencia = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($adherencia)
                    ) {
                        $query =
                            "insert into sal_pac_adh_aseg (id_archivo,desde,hasta,area,aseguradora,adherencia) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $adherencia .
                            "')";
                        $delete =
                            "delete from sal_pac_adh_aseg where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from sal_pac_adh_aseg
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        ##### SALUD - FIN
        ##### LOGISTICA INICIO
        if (
            $area == '19' and
            $nombre_plantilla == 'LOG - INVENTARIO DE MASCARAS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_mascara = '';
                    if (isset($Row[0])) {
                        $tipo_mascara = mysqli_real_escape_string(
                            $con,
                            $Row[0]
                        );
                    }
                    $disponibles = '';
                    if (isset($Row[1])) {
                        $disponibles = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $por_nacionalizar = '';
                    if (isset($Row[2])) {
                        $por_nacionalizar = mysqli_real_escape_string(
                            $con,
                            $Row[2]
                        );
                    }
                    $transp_internacional = '';
                    if (isset($Row[3])) {
                        $transp_internacional = mysqli_real_escape_string(
                            $con,
                            $Row[3]
                        );
                    }
                    $pte_despacho = '';
                    if (isset($Row[4])) {
                        $pte_despacho = mysqli_real_escape_string(
                            $con,
                            $Row[4]
                        );
                    }
                    $desde = '';
                    if (isset($Row[5])) {
                        $desde = mysqli_real_escape_string($con, $Row[5]);
                    }
                    $hasta = '';
                    if (isset($Row[6])) {
                        $hasta = mysqli_real_escape_string($con, $Row[6]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($tipo_mascara) ||
                        !empty(
                            $disponibles ||
                                !empty($por_nacionalizar) ||
                                !empty($transp_internacional) ||
                                !empty($pte_despacho)
                        )
                    ) {
                        $query =
                            "insert into log_inv_mascaras (id_archivo,desde,hasta,area,tipo_mascara,disponibles,por_nacionalizar,
                        transp_internacional,pendiente_despacho) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $tipo_mascara .
                            "','" .
                            $disponibles .
                            "','" .
                            $por_nacionalizar .
                            "' ,
                            '" .
                            $transp_internacional .
                            "','" .
                            $pte_despacho .
                            "')";
                        $delete =
                            "delete from log_inv_mascaras where tipo_mascara = 'tipo_mascara'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_inv_mascaras
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '20' and
            $nombre_plantilla == 'LOG - INVENTARIO DE EQUIPOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $tipo_equipo = '';
                    if (isset($Row[0])) {
                        $tipo_equipo = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $disponibles = '';
                    if (isset($Row[1])) {
                        $disponibles = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $por_nacionalizar = '';
                    if (isset($Row[2])) {
                        $por_nacionalizar = mysqli_real_escape_string(
                            $con,
                            $Row[2]
                        );
                    }
                    $transp_internacional = '';
                    if (isset($Row[3])) {
                        $transp_internacional = mysqli_real_escape_string(
                            $con,
                            $Row[3]
                        );
                    }
                    $pte_despacho = '';
                    if (isset($Row[4])) {
                        $pte_despacho = mysqli_real_escape_string(
                            $con,
                            $Row[4]
                        );
                    }
                    $desde = '';
                    if (isset($Row[5])) {
                        $desde = mysqli_real_escape_string($con, $Row[5]);
                    }
                    $hasta = '';
                    if (isset($Row[6])) {
                        $hasta = mysqli_real_escape_string($con, $Row[6]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($tipo_equipo) ||
                        !empty(
                            $disponibles ||
                                !empty($por_nacionalizar) ||
                                !empty($transp_internacional) ||
                                !empty($pte_despacho)
                        )
                    ) {
                        $query =
                            "insert into log_inv_equipos (id_archivo,desde,hasta,area,tipo_equipo,disponibles,por_nacionalizar,
                        transp_internacional,pendiente_despacho) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $tipo_equipo .
                            "','" .
                            $disponibles .
                            "','" .
                            $por_nacionalizar .
                            "' ,
                            '" .
                            $transp_internacional .
                            "','" .
                            $pte_despacho .
                            "')";
                        $delete =
                            "delete from log_inv_equipos where tipo_equipo = 'tipo_equipo'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_inv_equipos
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '21' and
            $nombre_plantilla == 'LOG - MTTO RECUPERABLE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P3_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $fabricante = '';
                    if (isset($Row[0])) {
                        $fabricante = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $recuperable = '';
                    if (isset($Row[1])) {
                        $recuperable = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($fabricante) ||
                        !empty($recuperable)
                    ) {
                        $query =
                            "insert into log_mtto_recuperable (id_archivo,desde,hasta,area,fabricante,recuperable) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $fabricante .
                            "','" .
                            $recuperable .
                            "')";
                        $delete =
                            "delete from log_mtto_recuperable where fabricante = 'fabricante'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_mtto_recuperable
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '22' and
            $nombre_plantilla == 'LOG - MTTO NO RECUPERABLE.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P4_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $estado = '';
                    if (isset($Row[0])) {
                        $estado = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $no_recuperable = '';
                    if (isset($Row[1])) {
                        $no_recuperable = mysqli_real_escape_string(
                            $con,
                            $Row[1]
                        );
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($estado) ||
                        !empty($no_recuperable)
                    ) {
                        $query =
                            "insert into log_mtto_no_recuperable (id_archivo,desde,hasta,area,estado,no_recuperable) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $estado .
                            "','" .
                            $no_recuperable .
                            "')";
                        $delete =
                            "delete from log_mtto_no_recuperable where estado = 'estado'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_mtto_no_recuperable
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '23' and
            $nombre_plantilla == 'LOG - BALANCE DE EQUIPOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P5_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into log_balance_equi (id_archivo,desde,hasta,area,item,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from log_balance_equi where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_balance_equi
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '24' and
            $nombre_plantilla == 'LOG - BALANCE DE MASCARAS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P6_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into log_balance_masc (id_archivo,desde,hasta,area,item,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from log_balance_masc where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_balance_masc
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '25' and
            $nombre_plantilla == 'LOG - RECUPERACION DE EQUIPOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'LOG_P7_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into log_recuperacion_equ (id_archivo,desde,hasta,area,item,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from log_recuperacion_equ where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from log_recuperacion_equ
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        ##### LOGISTICA FIN
        ##### CARTERA INICIO
        if (
            $area == '26' and
            $nombre_plantilla == 'CAR - CARTERA TOTAL POR EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_cart_total (id_archivo,desde,hasta,area,aseguradora,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_cart_total where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_cart_total
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '27' and
            $nombre_plantilla == 'CAR - CARTERA NO VENCIDA.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_cart_no_ven (id_archivo,desde,hasta,area,aseguradora,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_cart_no_ven where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_cart_no_ven
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '28' and
            $nombre_plantilla == 'CAR - EDADES DE CARTERA.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P3_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_edad_cart (id_archivo,desde,hasta,area,item,cantidad) values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "'
                            ,'" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_edad_cart where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_edad_cart
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '29' and
            $nombre_plantilla == 'CAR - RECAUDOS POR EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P4_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_recaudo_eps (id_archivo,desde,hasta,area,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_recaudo_eps where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_recaudo_eps
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '30' and
            $nombre_plantilla == 'CAR - RECAUDOS POR PACIENTE POR EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P5_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_recaudo_pac_x_eps (id_archivo,desde,hasta,area,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_recaudo_pac_x_eps where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_recaudo_pac_x_eps
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '31' and
            $nombre_plantilla == 'CAR - FACTURACION POR PACIENTE POR EPS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'CAR_P6_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into car_fact_pac_x_eps (id_archivo,desde,hasta,area,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from car_fact_pac_x_eps where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from car_fact_pac_x_eps
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        #Cartera fin
        #Modelos Inicio
        if (
            $area == '32' and
            $nombre_plantilla == 'MOD - PACIENTES TOTALES.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P1_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $fase = '';
                    if (isset($Row[0])) {
                        $fase = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $aseguradora = '';
                    if (isset($Row[1])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $cantidad = '';
                    if (isset($Row[2])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $desde = '';
                    if (isset($Row[3])) {
                        $desde = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $hasta = '';
                    if (isset($Row[4])) {
                        $hasta = mysqli_real_escape_string($con, $Row[4]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($fase) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into mod_pac_tot (id_archivo,desde,hasta,area,fase,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $fase .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from mod_pac_tot where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_pac_tot
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '33' and
            $nombre_plantilla == 'MOD - NUEVOS PACIENTES.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P2_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $fase_2 = '';
                    if (isset($Row[2])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $fase_3 = '';
                    if (isset($Row[3])) {
                        $fase_3 = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $desde = '';
                    if (isset($Row[4])) {
                        $desde = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $hasta = '';
                    if (isset($Row[5])) {
                        $hasta = mysqli_real_escape_string($con, $Row[5]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($fase_1) ||
                        !empty($fase_2) ||
                        !empty($fase_3)
                    ) {
                        $query =
                            "insert into mod_nue_pac (id_archivo,desde,hasta,area,aseguradora,fase_1,fase_2,fase_3) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $fase_1 .
                            "','" .
                            $fase_2 .
                            "','" .
                            $fase_3 .
                            "')";
                        $delete =
                            "delete from mod_nue_pac where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_nue_pac
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '34' and
            $nombre_plantilla == 'MOD - ORDENAMIENTO VS ENTREGA EQUIPO.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P3_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into mod_ordenamientos (id_archivo,desde,hasta,area,item,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from mod_ordenamientos where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_ordenamientos
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '35' and
            $nombre_plantilla == 'MOD - PACIENTES EGRESADOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P4_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $fase_2 = '';
                    if (isset($Row[2])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $fase_3 = '';
                    if (isset($Row[3])) {
                        $fase_3 = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $desde = '';
                    if (isset($Row[4])) {
                        $desde = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $hasta = '';
                    if (isset($Row[5])) {
                        $hasta = mysqli_real_escape_string($con, $Row[5]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($fase_1) ||
                        !empty($fase_2) ||
                        !empty($fase_3)
                    ) {
                        $query =
                            "insert into mod_pac_egre (id_archivo,desde,hasta,area,aseguradora,fase_1,fase_2,fase_3) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $fase_1 .
                            "','" .
                            $fase_2 .
                            "','" .
                            $fase_3 .
                            "')";
                        $delete =
                            "delete from mod_pac_egre where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_pac_egre
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '36' and
            $nombre_plantilla == 'MOD - PACIENTES EN PREFASES.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P5_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into mod_pac_prefases (id_archivo,desde,hasta,area,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from mod_pac_prefases where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_pac_prefases
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '37' and
            $nombre_plantilla == 'MOD - PACIENTES PENDIENTES AUTORIZACION.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P6_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $aseguradora = '';
                    if (isset($Row[0])) {
                        $aseguradora = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $cantidad = '';
                    if (isset($Row[1])) {
                        $cantidad = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $desde = '';
                    if (isset($Row[2])) {
                        $desde = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $hasta = '';
                    if (isset($Row[3])) {
                        $hasta = mysqli_real_escape_string($con, $Row[3]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($aseguradora) ||
                        !empty($cantidad)
                    ) {
                        $query =
                            "insert into mod_pac_pte_aut (id_archivo,desde,hasta,area,aseguradora,cantidad) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $aseguradora .
                            "','" .
                            $cantidad .
                            "')";
                        $delete =
                            "delete from mod_pac_pte_aut where aseguradora = 'aseguradora'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_pac_pte_aut
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        if (
            $area == '38' and
            $nombre_plantilla == 'MOD - MOTIVOS DE EGRESOS.xlsx'
        ) {
            $random = random_int(111111, 999999);
            $codigo = 'MOD_P7_' . $random;
            for ($i = 0; $i < $sheetCount; $i++) {
                $Reader->ChangeSheet($i);
                foreach ($Reader as $Row) {
                    $item = '';
                    if (isset($Row[0])) {
                        $item = mysqli_real_escape_string($con, $Row[0]);
                    }
                    $fase_1 = '';
                    if (isset($Row[1])) {
                        $fase_1 = mysqli_real_escape_string($con, $Row[1]);
                    }
                    $fase_2 = '';
                    if (isset($Row[2])) {
                        $fase_2 = mysqli_real_escape_string($con, $Row[2]);
                    }
                    $fase_3 = '';
                    if (isset($Row[3])) {
                        $fase_3 = mysqli_real_escape_string($con, $Row[3]);
                    }
                    $desde = '';
                    if (isset($Row[4])) {
                        $desde = mysqli_real_escape_string($con, $Row[4]);
                    }
                    $hasta = '';
                    if (isset($Row[5])) {
                        $hasta = mysqli_real_escape_string($con, $Row[5]);
                    }
                    if (
                        !empty($desde) ||
                        !empty($hasta) ||
                        !empty($item) ||
                        !empty($fase_1) ||
                        !empty($fase_2) ||
                        !empty($fase_3)
                    ) {
                        $query =
                            "insert into mod_motivo_egre (id_archivo,desde,hasta,area,item,fase_1,fase_2,fase_3) 
                        values('" .
                            $codigo .
                            "','" .
                            $desde .
                            "','" .
                            $hasta .
                            "','" .
                            $area .
                            "','" .
                            $item .
                            "','" .
                            $fase_1 .
                            "','" .
                            $fase_2 .
                            "','" .
                            $fase_3 .
                            "')";
                        $delete =
                            "delete from mod_motivo_egre where item = 'item'";
                        $resultados = mysqli_query($con, $query);
                        $borrado = mysqli_query($con, $delete);
                        $actualizacion = "insert into historial (ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA)  
                        (select distinct ID_ARCHIVO,DESDE,HASTA,AREA, IDVISTA from mod_motivo_egre
                        where ID_ARCHIVO not in (select distinct ID_ARCHIVO from historial))";
                        $actualizar = mysqli_query($con, $actualizacion);
                        if (!empty($resultados)) {
                            $type = 'success';
                            $mensaje = 'Excel importado correctamente';
                            rename(
                                'subidas/' . $_FILES['file']['name'],
                                'definitivos/' . $codigo . '.xlsx'
                            );
                        } else {
                            $type = 'error';
                            $mensaje = 'Hubo un problema al importar registros';
                        }
                    }
                }
            }
        }
        #Modelos Fin
    } else {
        $type = 'error';
        $mensaje =
            'El archivo enviado es invalido. Por favor vuelva a intentarlo';
    }
    /*     $to = "analista.sistemas@maplerespiratory.co";
    $subject = "Indicadores Maple Respiratory";
    $message .= "Apreciado(a) usuario:";
    $message .= "\r\n";
    $message .= "\r\n";
    $message .= "se realizaron actualizaciones en la plataforma de Indicadores.";
    $message .= "\r\n";
    $message .= "\r\n";
    $message .= "Cordialmente,";
    $message .= "\r\n";
    $message .= "Indicadores Maple Respiratory";
    $message .= "\r\n";
    $message .= "\r\n";
    $headers = "From: Analista Sistemas <analista.sistemas@maplerespiratory.co>" . "\r\n" . "CC:";
    mail($to, $subject, $message, $headers); */
}
# ELIMINAR REGISTROS DESDE HISTORIAL Y DESDE CADA TABLA SEGUN ID DE ARCHIVO
if (isset($_POST['enviar'])) {
    $id = $_POST['enviar'];
    if (stripos($id, 'SIS_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM agendamiento where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $message =
                'Registros asociados al archivo: ' .
                $id .
                ' eliminados correctamente!';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'SIS_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM pacientes where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM documentos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM pacientes where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM lectura_tarjeta where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM dx_est_realizados_sede where id_archivo = '" .
            $id .
            "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM dx_est_realizados_eps where id_archivo = '" .
            $id .
            "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM dx_est_diagnosticos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_presion where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_est_fallidos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_uso_de_ci where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P7_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_prom_fase where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P8_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM dx_costo_oportunidad where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'DIA_P9_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM dx_prefase where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'SAL_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM sal_adherencia_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'SAL_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM sal_adherencia_aseg where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'SAL_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_pac_adh_sede where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'SAL_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM sal_pac_adh_aseg where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM documentos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_inv_equipos where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM log_mtto_recuperable where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM log_mtto_no_recuperable where id_archivo = '" .
            $id .
            "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_balance_equi where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM log_balance_masc where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'LOG_P7_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM log_recuperacion_equ where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P1_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_cart_total where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P2_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_cart_no_ven where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P3_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_edad_cart where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P4_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 = "DELETE FROM car_recaudo_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P5_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM car_recaudo_pac_x_eps where id_archivo = '" .
            $id .
            "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    if (stripos($id, 'CAR_P6_') !== false) {
        $sql = "DELETE FROM historial where id_archivo= '" . $id . "'";
        $borrado = mysqli_query($con, $sql);
        $sql2 =
            "DELETE FROM car_fact_pac_x_eps where id_archivo = '" . $id . "'";
        $borrado2 = mysqli_query($con, $sql2);
        if (!empty($borrado)) {
            $type = 'success';
            $mensaje = 'Registros eliminados correctamente';
        } else {
            $type = 'error';
            $mensaje = 'Hubo un problema al eliminar el archivo';
        }
    }
    #
}
?>
<!--  PLANTILLA PRINCIPAL - INICIO -->
<?php $mysqli = new mysqli(
    '192.168.30.110',
    'automatizaciones',
    'Maple2021.*',
    'winautomation'
);
#LOCAL
#$mysqli = new mysqli('localhost', 'rhsalian', 'RHS2017*', 'rhsalian_indicadores'); #PRODUCCION
?>
<div class="container">
    <h3 class="mt-0">Cargue de archivos a procesar</h3>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="outer-container">
                <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div class="form-group"><label for="">Proceso al que pertenece el archivo a cargar.</label>
                        <select required name="area" id="area" class="browser-default custom-select">
                            <option value="">Seleccione:</option>
                            <?php
                            $id_indicador = $_SESSION['idvista'];
                            $query = $mysqli->query(
                                "SELECT * FROM indicador where estado = 1 and idarea in ( $id_indicador ) order by indicador asc"
                            );
                            while ($valores = mysqli_fetch_array($query)) {
                                $id = $valores['ID'];
                                $indicador = $valores['INDICADOR'];
                                echo '<option value="' .
                                    $id .
                                    '">' .
                                    $indicador .
                                    '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!--                     <div class="form-group"><label for="">Desde:</label><input class="form-control" type="date" name="desde" value="<?php echo date(
                        'Y-m-d'
                    ); ?>" id="desde" required></div>
                    <div class="form-group"><label for="">Hasta:</label><input class="form-control" type="date" name="hasta" value="<?php echo date(
                        'Y-m-d'
                    ); ?>" id="hasta" required></div>
 -->
                    <div>
                        <div><label>Elija Archivo Excel</label>
                            <br><input type="file" name="file" id="file" accept=".xls,.xlsx">
                            <br><br>
                            <button type="submit" id="submit" name="import" class="btn-submit">Importar Archivo</button>
                        </div>
                </form>
            </div>
            <div id="response" class="<?php if (!empty($type)) {
                echo $type . ' display-block';
            } ?>"><?php if (!empty($mensaje)) {
    echo $mensaje;
    switch ($_SESSION['idvista']) {
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
} ?></div><?php
$id_rol = $_SESSION['idvista'];
$sqlSelect = "SELECT 
                                                    id_archivo,fecha,indicador
                                                 FROM
                                                     historial
                                                         JOIN
                                                     indicador ON indicador.id = historial.area
                                                 WHERE
                                                     historial.idvista IN ($id_rol)
                                                 ORDER BY historial.id DESC
                                                 LIMIT 10";
$result = mysqli_query($con, $sqlSelect);
if (mysqli_num_rows($result) > 0) { ?><table class='tutorial-table'>
                <form action="" method="post">
                    <thead>
                        <th colspan="6" rowspan="1">
                            <h4>Listado de archivos cargados</h4>
                        </th>
                        <tr>
                            <th>Fecha de Cargue</th>
                            <th>Id Archivo</th>
                            <th>Proceso</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead><?php while (
                        $row = mysqli_fetch_array($result)
                    ) { ?><tbody>
                        <tr>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['id_archivo']; ?></td>
                            <td><?php echo $row['indicador']; ?></td>
                            <td>
                                <!-- Ajuste de id de archivo por error al momento de eliminar un archivo por el $ID -->
                                <input type="submit" disabled="disabled" style="width: 110px;" name="enviar" value="<?php echo $row[
                                    'id_archivo'
                                ]; ?>">
                            </td>
                        </tr>
                        <?php } ?>
                </form>
                </tbody>
            </table><?php }
?>
        </div>
    </div>
</div>
<script src="assets/jquery-1.12.4-jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>