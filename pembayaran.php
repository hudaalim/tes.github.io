<?php
session_start();
//script mengkoneksikan data base
$koneksi = new mysqli ("localhost","root","","indahpermai2");


//skrip mengamankan halaman riwayat
if (!isset($_SESSION["pelanggan"]) OR empty($_SESSION["pelanggan"])) 
{
	echo "<script>alert('Silahkan Login Terlebih Dahulu');</script>";
	echo "<script>location='login.php';</script>";
	exit();	
}


//skrip mendapatkan id_pembelian dari url
$idpem = $_GET["id"];
$ambil = $koneksi->query("SELECT * FROM pembelian WHERE id_pembelian='$idpem'");
$detpem = $ambil->fetch_assoc();

//skrip mendapatkan id_pelanggan yang sedang beli
$id_pelanggan_beli = $detpem["id_pelanggan"];
//skrip mendapatkan id_pelanggan yang login
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_login !==$id_pelanggan_beli)
{
	echo "<script>alert('Anda tidak berhak mengakses halaman ini !');</script>";
	echo "<script>location='riwayat.php';</script>";
	exit();		
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Pembayaran</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
	<!-- NAVBAR -->
	<?php include 'menu.php'; ?>

	<!--isi/konten-->
	<div class="container">
		<h2>Konfirmasi Pembayaran</h2>
		<p>Kirim Bukti Pembayaran Anda disini</p>
		<div class="alert alert-info">Total Tagihan Anda <strong>Rp. <?php echo number_format($detpem["total_pembelian"]) ?></strong></div>

		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Nama Penyetor</label>
				<input type="text" class="form-control" name="nama">
			</div>
			<div class="form-group">
				<label>Bank</label>
				<input type="text" class="form-control" name="bank">
			</div>
			<div class="form-group">
				<label>Jumlah</label>
				<input type="number" class="form-control" name="jumlah" min="1">
			</div><div class="form-group">
				<label>Foto Bukti Pembayaran</label>
				<input type="file" class="form-control" name="bukti">
				<p class="text-danger">Pastikan bentuk file foto .JPG dan maksimal 2MB</p>
			</div>
			<button class="btn btn-primary" name="kirim">Kirim</button>
		</form>
	</div>

	<?php
	//skrip tombol kirim
	if (isset($_POST["kirim"])) 
	{
		//skrip upload foto bukti
		$namabukti = $_FILES ["bukti"]["name"];
		$lokasibukti = $_FILES ["bukti"]["tmp_name"];
		$namafile = date("YmdHis").$namabukti;
		move_uploaded_file($lokasibukti, "bukti_pembayaran/$namafile");

		//skrip memasukkan data ke database
		$nama = $_POST["nama"];
		$bank = $_POST["bank"];
		$jumlah = $_POST["jumlah"];
		$tanggal = date("Y-m-d");

		$koneksi->query("INSERT INTO konfirmasi_pembayaran(id_pembelian,nama,bank,jumlah,tanggal,bukti) 
			VALUES ('$idpem','$nama','$bank','$jumlah','$tanggal','$namafile')");


		//skkrip update status pembelian
		$koneksi->query("UPDATE pembelian SET status_pembelian='sudah kirim pembayaran'
			WHERE id_pembelian='$idpem'");

		echo "<script>alert('Bukti Pembayaran Sudah Dikirim');</script>";
		echo "<script>location='riwayat.php';</script>";
				
	}
	?>

</body>
</html>