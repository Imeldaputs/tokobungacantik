<?php
$conn = new mysqli("localhost", "root", "", "flower_store");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tracking Pengiriman</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <style>
    body { font-family: Arial; padding: 20px; background-color: #fef1f6; }
    .result { background: #fff; padding: 20px; border: 1px solid #ccc; margin-top: 20px; }
  </style>
</head>
<body>

<h2>Tracking Pengiriman - Beauty Flower Store</h2>

<form method="GET" action="">
  <label>Masukkan atau Scan Kode Transaksi:</label>
  <input type="text" name="id" placeholder="Contoh: TX1750601956617" required />
  <button type="submit">Lacak</button>
</form>

<h3>Atau Scan Barcode di Sini:</h3>
<div id="reader" style="width: 300px;"></div>

<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $result = $conn->query("SELECT * FROM sales WHERE transaction_id = '$id'");

  if ($row = $result->fetch_assoc()) {
    $arrival_time = date("H:i", strtotime($row['order_time']) + 3600);
    echo "<div class='result'>";
    echo "<h3>Tracking Info</h3>";
    echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";
    echo "<p><strong>Customer:</strong> {$row['customer_name']}</p>";
    echo "<p><strong>Product:</strong> {$row['product_name']}</p>";
    echo "<p><strong>Delivery:</strong> {$row['delivery_type']}</p>";
    echo "<p><strong>Estimated Arrival:</strong> Sekitar jam $arrival_time</p>";
    echo "</div>";
  } else {
    echo "<p style='color:red;'>❌ Tracking ID tidak ditemukan.</p>";
  }
}
?>

<script>
function onScanSuccess(decodedText) {
  window.location.href = "track.php?id=" + encodeURIComponent(decodedText);
}

const html5QrCode = new Html5Qrcode("reader");
Html5Qrcode.getCameras().then(devices => {
  if (devices.length) {
    html5QrCode.start(devices[0].id, { fps: 10, qrbox: 250 }, onScanSuccess);
  }
}).catch(err => {
  document.getElementById("reader").innerText = "❌ Kamera tidak ditemukan.";
});
</script>

</body>
</html>
