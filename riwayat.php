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


?>


<!DOCTYPE html>
<html>
<head>
	<title>Toko Bangunan Indah Permai II</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
<pre>
	<?php print_r($_SESSION["pelanggan"]); ?>
</pre>

<!-- NAVBAR -->
<?php include 'menu.php'; ?>

<!--isi/konten-->
<section class="riwayat">
	<div class="container">
		<h3>Riwayat Belanja <?php echo $_SESSION["pelanggan"]["nama_pelanggan"] ?></h3>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal Belanja</th>
					<th>Status Belanja</th>
					<th>Total Belanja</th>
					<th>Opsi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$nomor=1;
				//skrip mendapatkan id_pelanggan yang sedang login
				$id_pelanggan = $_SESSION["pelanggan"]['id_pelanggan'];

				$ambil = $koneksi->query("SELECT * FROM pembelian WHERE id_pelanggan='$id_pelanggan'");
				WHILE ($pecah = $ambil->fetch_assoc()) { 
				?>
				<tr>
					<td><?php echo $nomor; ?></td>
					<td><?php echo $pecah["tanggal_pembelian"] ?></td>
					<td>
						<?php echo $pecah["status_pembelian"] ?>
						<br>
						<?php if (!empty($pecah['resi'])): ?>
						No Resi : <?php echo $pecah['resi']; ?>
						<?php endif ?>
					</td>
					<td>Rp. <?php echo number_format($pecah["total_pembelian"]) ?></td>
					<td>
						<a href="nota.php?id=<?php echo $pecah["id_pembelian"] ?>" class="btn btn-info" >Nota</a>

							<?php if ($pecah["status_pembelian"]=="pending"): ?>
							<a href="pembayaran.php?id=<?php echo $pecah["id_pembelian"]; ?>" class="btn btn-success" >
								Konfrimasi Pembayaran
							</a>
							<?php else: ?>
							<a href="lihat_pembayaran.php?id=<?php echo $pecah["id_pembelian"]; ?>" class="btn btn-warning">
								Lihat Pembayaran
							</a>
					  		<?php endif?>

						
					</td>
				</tr>
				<?php $nomor++; ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</section>

</body>
</html>