<?php
if (!empty($_POST)) {
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		if ($_POST["username"] != "" && $_POST["password"] != "") {
			include "conexion.php";
			$user_id = null;
			$sql1 = "select * from user where (username=\"$_POST[username]\" or email=\"$_POST[username]\") and password=\"$_POST[password]\" ";
			$query = $con->query($sql1);
			while ($r = $query->fetch_array()) {
				$user_id = $r["username"];
				$id_vista = $r["idvista"];
				$fullname = $r["fullname"];
				$administrador = $r["administrator"];
				$visualizar = $r["visualizar"];
				break;
			}
			if ($user_id == null) {
				print "<script>alert(\"Acceso invalido.\");window.location='../index.php';</script>";
			} else {
				session_start();
				$_SESSION["idvista"] = $id_vista;
				$_SESSION["username"] = $user_id;
				$_SESSION["fullname"] = $fullname;
				$_SESSION["administrator"] = $administrador;
				$_SESSION["visualizar"] = $visualizar;
				print "<script>window.location='../inicio.php';</script>";
			}
		}
	}
}
