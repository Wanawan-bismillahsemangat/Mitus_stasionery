<?php
// checkout_snapbi.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../backend/php/midtrans/Midtrans.php';
require_once __DIR__ . '/../backend/php/midtrans/SnapBi/SnapBiConfig.php';
require_once __DIR__ . '/../backend/php/midtrans/SnapBi/SnapBi.php';

// Konfigurasi SnapBI (ISI DENGAN DATA ANDA)
\SnapBi\SnapBiConfig::$isProduction = false; // true jika live
\SnapBi\SnapBiConfig::$snapBiClientId = 'ISI_CLIENT_ID';
\SnapBi\SnapBiConfig::$snapBiPrivateKey = 'ISI_PRIVATE_KEY';
\SnapBi\SnapBiConfig::$snapBiClientSecret = 'ISI_CLIENT_SECRET';
\SnapBi\SnapBiConfig::$snapBiPartnerId = 'ISI_PARTNER_ID';
\SnapBi\SnapBiConfig::$snapBiChannelId = 'ISI_CHANNEL_ID';

// Ambil data order dari session/database (contoh dummy)
$order_id = 'ORDER-BI-' . time();
$gross_amount = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 100000;
$customer_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Nama User';
$customer_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'user@email.com';
$customer_phone = isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : '08123456789';

$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $gross_amount,
    ],
    'customer_details' => [
        'first_name' => $customer_name,
        'email' => $customer_email,
        'phone' => $customer_phone,
    ],
];

// Buat transaksi SnapBI VA (atau ganti ke qris/directDebit jika perlu)
$snapBi = \SnapBi\SnapBi::va()
    ->withClientId(\SnapBi\SnapBiConfig::$snapBiClientId)
    ->withPrivateKey(\SnapBi\SnapBiConfig::$snapBiPrivateKey)
    ->withClientSecret(\SnapBi\SnapBiConfig::$snapBiClientSecret)
    ->withPartnerId(\SnapBi\SnapBiConfig::$snapBiPartnerId)
    ->withChannelId(\SnapBi\SnapBiConfig::$snapBiChannelId)
    ->withBody($params);
$response = $snapBi->createPayment($order_id);

// Ambil token/URL SnapBI dari $response (cek dokumentasi Midtrans SnapBI untuk field yang benar)
$snapBiToken = isset($response->token) ? $response->token : '';
// Atau jika URL: $snapBiUrl = isset($response->redirect_url) ? $response->redirect_url : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran SnapBI</title>
    <script src="https://app.sandbox.midtrans.com/snapbi/snapbi.js" data-client-key="ISI_CLIENT_KEY_SNAPBI_ANDA"></script>
</head>
<body>
    <button id="pay-bi-button">Bayar dengan SnapBI</button>
    <script type="text/javascript">
      document.getElementById('pay-bi-button').onclick = function(){
        snapbi.pay('<?= $snapBiToken ?>', {
          onSuccess: function(result){ window.location.href = 'order_confirm.php?order_id=<?= $order_id ?>'; },
          onPending: function(result){ alert('Transaksi pending'); },
          onError: function(result){ alert('Pembayaran gagal'); }
        });
      };
    </script>
</body>
</html>
