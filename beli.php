<?php
session_start();
//skrip mendapatkan id produk dari URL
$id_produk = $_GET['id'];

//skrip menambah jumlah produk, apabila produk sudah ada di keranjang
if (isset($_SESSION['keranjang'][$id_produk]))
{
	$_SESSION['keranjang'][$id_produk]+=1;
}
//skrip apabila produk belom ada di keranjang
else
{
	$_SESSION['keranjang'][$id_produk] = 1;
}

//skrip notifikasi apabila klik tombol beli
echo "<script>alert('Produk Yang Anda Beli Telah Masuk Ke Kernajang Belanja');</script>";
echo "<script>location='keranjang.php'</script>";
?>