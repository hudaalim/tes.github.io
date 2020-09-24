<?php
session_start();
echo "<pre>";
print_r($_SESSION['keranjang']);
echo "</pre>";
//mengkoneksikan ke database
$koneksi = new mysqli("localhost","root","","indahpermai2");

if (empty($_SESSION["keranjang"]) OR !isset($_SESSION["keranjang"])) 
{
	echo "<script>alert('Keranjang Kosong, Silahkan Belanja Terlebih Dahulu');</script>";
	echo "<script>location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Keranjang Belanja</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>


<!-- NAVBAR -->
<?php include 'menu.php'; ?>

	<!-- ISI/KONTEN -->
<section class="konten">
	<div class="container">
		<h1>Keranjang Belanja</h1>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Produk</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Total Harga</th>
					<th>Tindakan</th>
				</tr>
			</thead>
			<tbody>
				<?php $nomor=1; ?>
				<?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>
					<!-- menampilkan produk berdasarkan id_produk -->
					<?php 
					$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
					$pecah = $ambil->fetch_assoc();
					$totalharga = $pecah["harga_produk"]*$jumlah;
					?>
				<tr>
					<td><?php echo $nomor; ?></td>
					<td><?php echo $pecah["nama_produk"]; ?></td>
					<td>Rp. <?php echo number_format($pecah["harga_produk"]); ?></td>
					<td><?php echo $jumlah; ?></td>
					<td>Rp. <?php echo number_format($totalharga); ?></td>
					<td><a href="hapuskeranjang.php?id=<?php echo $id_produk ?>" class="btn btn-danger btn-xs">Hapus Produk</a></td>
				</tr>
				 <?php $nomor++; ?>
				 <?php endforeach ?>
			</tbody>
		</table>

		<a href="index.php" class="btn btn-default">Lanjut Belanja</a>
		<a href="checkout.php" class="btn btn-primary">Lanjut Bayar</a>
	</div>
</section>

</body>
</html>