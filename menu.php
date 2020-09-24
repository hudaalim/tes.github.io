<!--Menu Navbar-->

<nav class="navbar navbar-default">
	<div class="container">
	<ul class="nav navbar-nav">
		<li><a href="index.php">Home</a></li>
		<li><a href="keranjang.php">Keranjang</a></li>
		<!-- skrip jika pelanggan sudah login -->
		<?php if (isset($_SESSION["pelanggan"])): ?>
		<li><a href="logout.php">Logout</a></li>
		<li><a href="riwayat.php">Riwayat Belanja</a></li>
		<!-- skrip jika pelangga belum login -->
		<?php else: ?>
			<li><a href="login.php">Login</a></li>
			<li><a href="daftar.php">Daftar</a></li>
		<?php endif ?>
		
		<li><a href="checkout.php">Checkout</a></li>
	</ul>


	<form action="pencarian.php" method="get" class="navbar-form navbar-right">
		<input type="text" class="form-control" name="keyword">
		<button class="btn btn-primary">Cari</button>
	</form>
</div>
</nav>