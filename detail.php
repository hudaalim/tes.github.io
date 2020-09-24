<?php 
session_start();
//script mengkoneksikan data base
$koneksi = new mysqli ("localhost","root","","indahpermai2");
?>
<?php 
//skrip mendapatkan id_produk dari url
$id_produk = $_GET["id"];

//skrip query ambil data
$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
$detail = $ambil->fetch_assoc();

echo "<pre>";
print_r($detail);
echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Detail Produk</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>

<!-- NAVBAR -->
<?php include 'menu.php'; ?>

<!--Isi/konten-->
<section class="konten">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<img src="foto_produk/<?php echo $detail["foto_produk"]; ?>" alt="" class="img-responsive">
			</div>
			<div class="col-md-6">
				<h2><?php echo $detail["nama_produk"] ?></h2>
				<h4>Rp. <?php echo number_format($detail["harga_produk"]); ?></h4>

				<h5>Stok : <?php echo $detail['stok_produk'] ?></h5>

				<form method="post">
					<div class="form-group">
						<div class="input-group">
							<input type="number" min="1" class="form-control" name="jumlah" max="<?php echo $detail['stok_produk'] ?>">
							<div class="input-group-btn">
								<button class="btn btn-primary" name="beli">Beli</button>
							</div>
						</div>
					</div>
				</form>

				<?php
				//skrip tombol beli
				if (isset($_POST["beli"])) 
				{
					//skrip mendapatkan jumlah produk yang di beli
					$jumlah = $_POST["jumlah"];
					//skrip memasukkan keranjang
					$_SESSION["keranjang"]["$id_produk"] = $jumlah;

					echo "<script>alert('Produk Telah Dimasukkan Ke Keranjang');</script>";
					echo "<script>location='keranjang.php';</script>";
				}
				?>

				<p><?php echo $detail["deskripsi_produk"]; ?></p>
			</div>
		</div>
	</div>
</section>
</body>
</html>