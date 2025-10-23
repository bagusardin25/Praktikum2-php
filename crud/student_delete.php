<?php
include("connection.php");

if (!isset($_GET['nim'])) {
  header("Location: student_view.php?message=NIM tidak ditemukan!");
  exit;
}

$nim = $_GET['nim'];
$query = "DELETE FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);

if ($result) {
  header("Location: student_view.php?message=Data berhasil dihapus!");
} else {
  echo "Gagal menghapus data: " . mysqli_error($connection);
}
?>
