<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

// Validate NIM parameter exists
if (!isset($_GET['nim'])) {
    header("Location: student_view.php?message=NIM tidak ditemukan!");
    exit;
}

// Get NIM and escape it for safe SQL
$nim = mysqli_real_escape_string($connection, $_GET['nim']);
$query = "DELETE FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);

if ($result) {
    header("Location: student_view.php?message=Data berhasil dihapus!");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($connection);
}

mysqli_close($connection);
?>
