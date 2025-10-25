<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

if (isset($_POST["submit"])) {
    $nim = htmlentities(strip_tags(trim($_POST["nim"])));
    $name = htmlentities(strip_tags(trim($_POST["name"])));
    $birth_city = htmlentities(strip_tags(trim($_POST["birth_city"])));
    $faculty = htmlentities(strip_tags(trim($_POST["faculty"])));
    $department = htmlentities(strip_tags(trim($_POST["department"])));
    $gpa = htmlentities(strip_tags(trim($_POST["gpa"])));
    $birth_date = htmlentities(strip_tags(trim($_POST["birth_date"])));
    $birth_month = htmlentities(strip_tags(trim($_POST["birth_month"])));
    $birth_year = htmlentities(strip_tags(trim($_POST["birth_year"])));

    $error_message = "";

    if (empty($nim)) {
        $error_message .= "- NIM belum diisi <br>";
    } else if (!preg_match("/^[0-9]{8}$/", $nim)) {
        $error_message .= "- NIM harus berupa 8 digit angka <br>";
    }

    $nim_check = mysqli_real_escape_string($connection, $nim);
    $query = "SELECT * FROM student WHERE nim='$nim_check'";
    $query_result = mysqli_query($connection, $query);
    $data_amount = mysqli_num_rows($query_result);
    if ($data_amount >= 1) {
        $error_message .= "- NIM yang sama sudah digunakan <br>";
    }

    if (empty($name)) {
        $error_message .= "- Nama belum diisi <br>";
    }
    if (empty($birth_city)) {
        $error_message .= "- Tempat lahir belum diisi <br>";
    }
    if (empty($department)) {
        $error_message .= "- Jurusan belum diisi <br>";
    }

    $select_ftib = "";
    $select_fteic = "";
    switch ($faculty) {
        case 'FTIB':
            $select_ftib = "selected";
            break;
        case 'FTEIC':
            $select_fteic = "selected";
            break;
    }

    if (!is_numeric($gpa) or ($gpa <= 0)) {
        $error_message .= "- IPK harus diisi dengan angka";
    }

    if ($error_message === "") {
        $nim = mysqli_real_escape_string($connection, $nim);
        $name = mysqli_real_escape_string($connection, $name);
        $birth_city = mysqli_real_escape_string($connection, $birth_city);
        $faculty = mysqli_real_escape_string($connection, $faculty);
        $department = mysqli_real_escape_string($connection, $department);
        $birth_date = mysqli_real_escape_string($connection, $birth_date);
        $birth_month = mysqli_real_escape_string($connection, $birth_month);
        $birth_year = mysqli_real_escape_string($connection, $birth_year);

        $gpa = (float) $gpa;
        $birth_date_full = $birth_year . "-" . $birth_month . "-" . $birth_date;

        $query = "INSERT INTO student VALUES ";
        $query .= "('$nim', '$name', '$birth_city', ";
        $query .= "'$birth_date_full','$faculty','$department',$gpa)";

        $result = mysqli_query($connection, $query);
        if ($result) {
            $message = "Mahasiswa dengan nama = \"<b>$name</b>\" sudah berhasil di tambah";
            $message = urlencode($message);
            header("Location: student_view.php?message={$message}");
            exit;
        } else {
            die("Query gagal dijalankan: " . mysqli_errno($connection) . " -\n" . mysqli_error($connection));
        }
    }
} else {
    // default values when form not submitted
    $error_message = "";
    $nim = "";
    $name = "";
    $birth_city = "";
    $select_ftib = "selected";
    $select_fteic = "";
    $department = "";
    $gpa = "";
    $birth_date = 1;
    $birth_month = "1";
    $birth_year = 1996;
}

$arr_month = [
    "1" => "Januari",
    "2" => "Februari",
    "3" => "Maret",
    "4" => "April",
    "5" => "Mei",
    "6" => "Juni",
    "7" => "Juli",
    "8" => "Agustus",
    "9" => "September",
    "10" => "Oktober",
    "11" => "Nopember",
    "12" => "Desember"
];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1 id="logo">Data Mahasiswa</h1>
        </div>
        <hr>
        <nav>
            <ul>
                <li><a href="student_view.php">Tampil</a></li>
                <li><a href="student_add.php">Tambah</a>
                <li><a href="logout.php">Logout</a>
            </ul>
        </nav>

        <h2>Tambah Data Mahasiswa</h2>

        <?php if ($error_message !== "") {
            echo "<div class='error'>$error_message</div>";
        }
        ?>

        <form action="student_add.php" method="POST">
            <table>
                <tr>
                    <td>NIM</td>
                    <td><input type="text" name="nim" value="<?php echo htmlspecialchars($nim); ?>" /></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" /></td>
                </tr>
                <tr>
                    <td>Tempat Lahir</td>
                    <td><input type="text" name="birth_city" value="<?php echo htmlspecialchars($birth_city); ?>" /></td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td>
                        <select name="birth_date">
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <option value="<?php echo $d; ?>" <?php echo ($birth_date == $d) ? 'selected' : ''; ?>><?php echo $d; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="birth_month">
                            <?php foreach ($arr_month as $mval => $mname) : ?>
                                <option value="<?php echo $mval; ?>" <?php echo ($birth_month == $mval) ? 'selected' : ''; ?>><?php echo $mname; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="birth_year">
                            <?php for ($y = 1980; $y <= 2010; $y++) : ?>
                                <option value="<?php echo $y; ?>" <?php echo ($birth_year == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Fakultas</td>
                    <td>
                        <select name="faculty">
                            <option value="FTIB" <?php echo $select_ftib; ?>>FTIB</option>
                            <option value="FTEIC" <?php echo $select_fteic; ?>>FTEIC</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td><input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>" /></td>
                </tr>
                <tr>
                    <td>IPK</td>
                    <td><input type="text" name="gpa" value="<?php echo htmlspecialchars($gpa); ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="submit" value="Simpan" /></td>
                </tr>
            </table>
        </form>

    </div>
</body>

</html>

<?php
// close DB connection
mysqli_close($connection);
?>