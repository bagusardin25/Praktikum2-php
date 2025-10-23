<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "praktikum2";
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$connection) {
  die("Koneksi dengan database gagal: " . mysqli_connect_errno() . " -
" . mysqli_connect_error());
}
