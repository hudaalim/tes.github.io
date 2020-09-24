<?php
session_start();
//script mengkoneksikan data base
$koneksi = new mysqli ("localhost","root","","indahpermai2");

//skrip jika pelanggan belum login maka akan di larikan ke halaman login
if (!isset($_SESSION["pelanggan"])) 
{
	echo "<script>alert('Anda Tidak Dapat Mengakses Halaman Ini, Silahkan Login Dahulu !');</script>";
	echo "<script>location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Checkout</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
<body>

<!-- NAVBAR -->
<?php include 'menu.php'; ?>

<!-- isi/konten-->
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
				</tr>
			</thead>
			<tbody>
				<?php $nomor=1; ?>
				<?php $totalbelanja = 0; ?>
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
				 <?php $nomor++; ?>
				 <?php $totalbelanja+=$totalharga; ?>
				 <?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4">Total Belanja</th>
					<th>Rp. <?php echo number_format($totalbelanja) ?></th>
				</tr>
			</tfoot>
		</table>

		
		<form method="post">
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['nama_pelanggan'] ?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['telepon_pelanggan'] ?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<select class="form-control" name="id_ongkir">
						<option value="">Pilih Daerah Kirim</option>
						<?php 
						$ambil = $koneksi->query("SELECT * FROM ongkir");
						WHILE ($perongkir = $ambil->fetch_assoc()){
						?>
						<option value="<?php echo $perongkir['id_ongkir'] ?>">
							<?php echo $perongkir['nama_daerah'] ?> -
							Rp. <?php echo number_format($perongkir['tarif']) ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Alamat Lengkap Pengiriman</label>
				<textarea class="form-control" name="alamat_pengiriman" placeholder="Masukkan Alamat Lengkap Pengiriman"></textarea>
			</div>
			<button class="btn btn-primary" name="bayar">Bayar</button>
		</form>

		<?php
			if (isset($_POST["bayar"])) 
			{
				$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
				$id_ongkir = $_POST["id_ongkir"];
				$tanggal_pembelian = date("Y-m-d");
				$alamat_pengiriman = $_POST['alamat_pengiriman'];

				$ambil = $koneksi->query("SELECT * FROM ongkir WHERE id_ongkir='$id_ongkir'");
				$arrayongkir = $ambil->fetch_assoc();
				$tarif = $arrayongkir['tarif'];

				$total_pembelian = $totalbelanja + $tarif;

				//1. skrip menyimpan data ke database tabel pembelian
				$koneksi->query("INSERT INTO pembelian (id_pelanggan,id_ongkir,tanggal_pembelian,total_pembelian,alamat_pengiriman) VALUES ('$id_pelanggan','$id_ongkir','$tanggal_pembelian','$total_pembelian','$alamat_pengiriman') ");

				//2. skrip mendapatkan id_pembelian yang baru
				$id_pembelian_baru = $koneksi->insert_id;

				foreach ($_SESSION["keranjang"] as $id_produk => $jumlah)
				{
					//skrip mendapatkan data produk berdasarkan id_produk
					$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
					$perproduk = $ambil->fetch_assoc();

					$nama = $perproduk['nama_produk'];
					$harga = $perproduk['harga_produk'];
					$berat = $perproduk['berat_produk'];

					$subberat = $perproduk['berat_produk']*$jumlah;
					$subharga = $perproduk['harga_produk']*$jumlah;
					$koneksi->query("INSERT INTO pembelian_produk (id_pembelian,id_produk,nama,harga,berat,subberat,subharga,jumlah) 
						VALUES ('$id_pembelian_baru','$id_produk','$nama','$harga','$berat','$subberat','$subharga','$jumlah') ");

					//skrip update stok
					$koneksi->query("UPDATE produk SET stok_produk=stok_produk -$jumlah WHERE id_produk='$id_produk'");
				}

				//3. skrip mengkosongkan keranjang, setelahnota keluar
				unset($_SESSION["keranjang"]);

				//4. skrip mengalihkan tampilan ke halaman nota pembelian yang baru terjadi
				echo "<script>alert('Pembelian Anda Sukses');</script>";
				echo "<script>location='nota.php?id=$id_pembelian_baru';</script>";

			}
		?>

	</div>
</section>
<pre><?php print_r($_SESSION['pelanggan']) ?></pre>
<pre><?php print_r($_SESSION["keranjang"]) ?></pre>
</body>
</html>