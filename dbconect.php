<?php
// simple conexion a la base de datos
function connect()
{
       #return new mysqli("localhost", "rhsalian", "RHS2017*", "rhsalian_indicadores"); #produccion
       return new mysqli("192.168.30.110", "automatizaciones", "Maple2021.*", "winautomation"); #pruebas
}
$con = connect();
if (!$con->set_charset("utf8")) { //asignamos la codificación comprobando que no falle
       die("Error cargando el conjunto de caracteres utf8");
}
