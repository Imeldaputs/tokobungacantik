<?php
date_default_timezone_set("Asia/Jakarta");

// Buat kode transaksi unik
$transactionID = "TX" . rand(100000, 999999);

// Ambil data dari form & URL
$customerName = $_POST['fullname'] ?? "Imel";
$product = $_GET['product'] ?? "Pink Bouquet";
$price = $_GET['price'] ?? 150000;
$qty = $_GET['qty'] ?? 1;
$deliveryType = $_POST['deliveryType'] ?? "Flower delivery instan - Rp 20.000";

// Hitung total
$subtotal = $price * $qty;
$deliveryCost = ($deliveryType == "Flower delivery express - Rp 10.000") ? 10000 : 20000;
$total = $subtotal + $deliveryCost;

// Simpan ke database
$conn = new mysqli("localhost", "root", "", "flower_store");
$conn->query("INSERT INTO sales (transaction_id, customer_name, product_name, delivery_type, total_paid)
VALUES ('$transactionID', '$customerName', '$product', '$deliveryType', '$total')");

// Link tracking QR code (otomatis pakai hostname + folder encoded)
$host = $_SERVER['HTTP_HOST'];
$encodedPath = rawurlencode("Web toko bunga");
$qrLink = "http://$host/{$encodedPath}/t.php?id=$transactionID";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembayaran</title>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
  <style>
    body { font-family: Arial; padding: 20px; background-color: #fef1f6; }
    .receipt {
      background: #fff;
      padding: 20px;
      border: 1px solid #ccc;
      max-width: 400px;
      margin: auto;
    }
    .barcode-container, .qrcode-container {
      text-align: center;
      margin: 15px 0;
    }
    svg { margin: auto; display: block; }
    canvas.qr {
      width: 150px;
      height: 150px;
      margin: auto;
      display: block;
    }
    .btn-print {
      margin-top: 20px;
      display: block;
      padding: 10px;
      background: #d63384;
      color: white;
      text-align: center;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    small.link {
      font-size: 10px;
      color: #666;
      word-break: break-all;
      display: block;
      margin-top: 5px;
    }
  </style>
</head>
<body>

<div class="receipt">
  <div class="barcode-container">
    <svg id="barcode"></svg>
  </div>

  <h2>Beauty Flower Store</h2>
  <p><strong>Date:</strong> <?= date("d M Y") ?></p>
  <p><strong>Transaction ID:</strong> <?= $transactionID ?></p>
  <p><strong>Name:</strong> <?= $customerName ?></p>
  <p><strong>Product:</strong> <?= $product ?></p>
  <p><strong>Quantity:</strong> <?= $qty ?></p>
  <p><strong>Delivery:</strong> <?= $deliveryType ?></p>
  <p><strong>Total Paid:</strong> Rp <?= number_format($total, 0, ',', '.') ?></p>

  <div class="qrcode-container">
    <p><strong>Scan QR untuk lacak pengiriman:</strong></p>
    <canvas id="qrcode" class="qr"></canvas>
    <small class="link"><?= $qrLink ?></small>
  </div>

  <button class="btn-print" onclick="window.print()">🖨️ Print Receipt</button>
</div>

<script>
  JsBarcode("#barcode", "<?= $transactionID ?>", {
    format: "CODE128",
    width: 2,
    height: 50,
    displayValue: true
  });

  new QRious({
    element: document.getElementById("qrcode"),
    value: "<?= $qrLink ?>",
    size: 200
  });
</script>

</body>
</html>
