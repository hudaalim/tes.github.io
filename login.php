<?php 
session_start();
//Mengkoneksikan database
$koneksi = new mysqli("localhost","root","","indahpermai2");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Toko Bangunan Indah Permai II</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
<!-- NAVBAR -->
<?php include 'menu.php'; ?>

<!--- isi konten -->
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Login Pelanggan</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" name="email">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="password">
						</div>
						<button class="btn btn-primary" name="login">Login</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
//skrip apabila tombol login ditekan
if (isset($_POST["login"]))
{

	$email = $_POST["email"];
	$password = $_POST["password"];
	//skrip mengecek akun pelanggan di database
	$ambil = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan='$email' AND password_pelanggan='$password'");

	//skrip menghitung akun yang cocok
	$akunyangcocok = $ambil->num_rows;

	//skrip jika ada akun yang cocok, maka akan berhasil login
	if ($akunyangcocok==1)
	{
		//Anda Berhasil Login
		//skrip mendapatkan akun dalam bentuk array
		$akun = $ambil->fetch_assoc();
		//skrip menyimpan akun pelanggan dalam session pelanggan
		$_SESSION["pelanggan"] = $akun;
		echo "<script>alert('Anda Berhasil Login');</script>";

		//skrip jika sudah belanja
		if (isset($_SESSION["keranjang"]) OR !empty($_SESSION["keranjang"])) 
		{
			echo "<script>location='checkout.php';</script>";	
		}
		else
		{
			echo "<script>location='riwayat.php';</script>";	
		}
	}
	else
	{
		//Anda Gagagl Login
		echo "<script>alert('Anda Tidak Berhasil Login');</script>";
		echo "<script>location='login.php';</script>";
	}
}
?>
</body>
</html>