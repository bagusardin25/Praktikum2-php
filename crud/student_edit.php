<?php
include("connection.php");

if (!isset($_GET['nim'])) {
  header("Location: student_view.php?message=NIM tidak ditemukan!");
  exit;
}

$nim = $_GET['nim'];
$query = "SELECT * FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  header("Location: student_view.php?message=Data tidak ditemukan!");
  exit;
}
if (isset($_POST['update'])) {
  $name = $_POST['name'];
  $birth_city = $_POST['birth_city'];
  $birth_date = $_POST['birth_date'];
  $faculty = $_POST['faculty'];
  $department = $_POST['department'];
  $gpa = $_POST['gpa'];

  $update = "UPDATE student SET 
              name='$name', 
              birth_city='$birth_city', 
              birth_date='$birth_date', 
              faculty='$faculty', 
              department='$department', 
              gpa='$gpa'
              WHERE nim='$nim'";
  $result = mysqli_query($connection, $update);

  if ($result) {
    header("Location: student_view.php?message=Data berhasil diupdate!");
  } else {
    echo "Gagal update data: " . mysqli_error($connection);
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Mahasiswa</title>
</head>
<body>
  <h2>Edit Data Mahasiswa</h2>
  <form method="POST">
    <p>NIM: <?= $data['nim']; ?></p>
    <p>Nama: <input type="text" name="name" value="<?= $data['name']; ?>" required></p>
    <p>Tempat Lahir: <input type="text" name="birth_city" value="<?= $data['birth_city']; ?>" required></p>
    <p>Tanggal Lahir: <input type="date" name="birth_date" value="<?= $data['birth_date']; ?>" required></p>
    <p>Fakultas: <input type="text" name="faculty" value="<?= $data['faculty']; ?>" required></p>
    <p>Jurusan: <input type="text" name="department" value="<?= $data['department']; ?>" required></p>
    <p>IPK: <input type="number" step="0.01" name="gpa" value="<?= $data['gpa']; ?>" required></p>
    <button type="submit" name="update">Simpan</button>
    <a href="student_view.php">Kembali</a>
  </form>
</body>
</html>