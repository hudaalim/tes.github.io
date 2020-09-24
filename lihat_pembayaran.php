<?php
session_start();
//script mengkoneksikan data base
$koneksi = new mysqli ("localhost","root","","indahpermai2");

//Skrip mengambil id_pembelian
$id_pembelian = $_GET['id'];

$ambil = $koneksi->query("SELECT * FROM konfirmasi_pembayaran 
	LEFT JOIN pembelian ON konfirmasi_pembayaran.id_pembelian=pembelian.id_pembelian 
	WHERE pembelian.id_pembelian='$id_pembelian'");

$detpem = $ambil->fetch_assoc();

//echo "<pre>";
//print_r($detpem);
//echo "</pre>";

//skrip validasi jika belum ada data atau belum mengkonfirmasi pembayaran
if (empty($detpem)) 
{
	echo "<script>alert('Data Pembayaran Kosong atau Konfirmasi Pembayaran Anda Terlebih Dahulu !');</script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}

//skrip validasi id_pelanggan tidak sesuai dengan login pada data pembayaran
if ($_SESSION["pelanggan"]['id_pelanggan']!==$detpem["id_pelanggan"]) 
{
	echo "<script>alert('Anda Tidak Berhak Mengakses Halaman ini !');</script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Lihat Pembayaran</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
	<!--navbar-->
	<?php include 'menu.php' ?>


	<!--isi/konten-->
	<div class="container">
		<h3>Lihat Pembayaran</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table">
					<tr>
						<th>Nama</th>
						<td><?php echo $detpem["nama"] ?></td>
					</tr>
					<tr>
						<th>Bank</th>
						<td><?php echo $detpem["bank"] ?></td>
					</tr>
					<tr>
						<th>Tanggal</th>
						<td><?php echo $detpem["tanggal"] ?></td>
					</tr>
					<tr>
						<th>Jumlah</th>
						<td>Rp. <?php echo number_format($detpem["jumlah"]) ?></td>
					</tr>
				</table>
			</div>
			<div class="col-md-6">
				<img src="bukti_pembayaran/<?php echo $detpem["bukti"] ?>" alt="" class="img-responsive">
			</div>
		</div>
	</div>
</body>
</html>