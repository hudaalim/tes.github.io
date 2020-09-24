<?php 
session_start();
//script mengkoneksikan data base
$koneksi = new mysqli ("localhost","root","","indahpermai2");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Nota Pembelian</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
<!-- NAVBAR -->
<?php include 'menu.php'; ?>

<!--isi/konten-->
<section class="konten">
	<div class="container">
		
	<h2>Detail Pembelian</h2>
	<?php
	$ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan=pelanggan.id_pelanggan WHERE pembelian.id_pembelian= '$_GET[id]'");
	$detail = $ambil->fetch_assoc();
	?>


<!--skrip mengamankan nota-->
<?php 
//skrip mendapatkan id_pelanggan yang punya nota
$idpelangganyangmemilikinota = $detail["id_pelanggan"];

//skrip mendapatkan id_pelanggan yang login
$idpelangganyanglogin = $_SESSION["pelanggan"]["id_pelanggan"];

if ($idpelangganyangmemilikinota!==$idpelangganyanglogin)
{
	echo "<script>alert('Anda Tidak Seharusnya Mengakses Halaman ini !');</script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}

?>


		<div class="row">
			<div class="col-md-4">
				<h3>Pembelian</h3>
				<strong>No. Pembelian : <?php echo $detail['id_pembelian'] ?></strong><br>
				Tanggal : <?php echo $detail['tanggal_pembelian']; ?><br>
				Total Pembelian : Rp.<?php echo number_format($detail['total_pembelian']) ?>
			</div>
			<div class="col-md-4">
				<h3>Pelanggan</h3>
				<strong><?php echo $detail['nama_pelanggan']; ?></strong> <br>
				<p>
					<?php echo $detail['telepon_pelanggan']; ?>
					<?php echo $detail['email_pelanggan']; ?>
				</p>
			</div>
			<div class="col-md-4">
				<h3>Pengiriman</h3>
				Alamat : <strong><?php echo $detail['alamat_pengiriman'] ?></strong>
			</div>
		</div>

				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Produk</th>
							<th>Harga Produk</th>
							<th>Berat Produk</th>
							<th>Jumlah Produk</th>
							<th>SubBerat Produk</th>
							<th>SubHarga Produk</th>
						</tr>
					</thead>
					<tbody>
						<?php $nomor=1; ?>
						<?php $ambil=$koneksi->query("SELECT * FROM pembelian_produk WHERE id_pembelian='$_GET[id]'"); ?>
						<?php while($pecah=$ambil->fetch_assoc()){ ?>
						<tr>
							<th><?php echo $nomor; ?></th>
							<th><?php echo $pecah['nama']; ?></th>
							<th>Rp.<?php echo number_format($pecah['harga']); ?></th>
							<th><?php echo $pecah['berat']; ?> gr</th>
							<th><?php echo $pecah['jumlah']; ?></th>
							<th><?php echo $pecah['subberat']; ?> gr</th>
							<th>Rp.<?php echo number_format($pecah['subharga']); ?></th>
						</tr>
						<?php $nomor++; ?>
						<?php } ?>
					</tbody>
				</table>


		<div class="row">
			<div class="col-md-7">
				<div class="alert alert-info">
					<p>
						Silahkan melakukan pembayaran sebesar Rp. <?php echo number_format($detail['total_pembelian']); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

</body>
</html>