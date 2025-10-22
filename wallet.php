<?php
// Database connectie
$host = 'localhost';
$db   = 'crypto_app';
$user = 'root'; // pas aan indien nodig
$pass = '';     // pas aan indien nodig

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Database-verbinding mislukt: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM wallet ORDER BY created_at DESC");
$wallet = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mijn Wallet</title>
  <link rel="stylesheet" href="styling.css">
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</head>
<body>

  <header class="site-header">
    <div class="nav-container">
      <h1 class="logo">💰 Crypto</h1>
      <nav class="nav-menu">
        <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">Coins</a>
        <a href="wallet.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='wallet.php'?'active':'' ?>">Wallet</a>
      </nav>
    </div>
  </header>

  <div class="page-overlay"></div>

  <div class="content" style="min-height: 100vh; display: flex; justify-content: center; align-items: flex-start;">
    <div style="text-align: center; width: 100%;">
      <h1>Mijn Wallet</h1>

      <div class="container">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Coin</th>
              <th>Symbol</th>
              <th>Prijs (USD)</th>
              <th>Aantal</th>
              <th>Totaalwaarde</th>
              <th>Datum toegevoegd</th>
              <th>Acties</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($wallet) > 0): ?>
              <?php foreach ($wallet as $i => $coin): ?>
                <tr data-id="<?= $coin['id'] ?>">
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($coin['NAME']) ?></td>
                  <td><?= htmlspecialchars($coin['symbol']) ?></td>
                  <td class="price" data-price="<?= $coin['price_usd'] ?>">$<?= number_format($coin['price_usd'], 2) ?></td>
                  <td>
                    <input type="number" class="coin-amount" 
                          value="<?= (float)$coin['amount'] ?>" 
                          min="0" step="any"
                          style="width:90px; text-align:center;">
                  </td>
                  <td class="total-value">$<?= number_format($coin['total_value'], 2) ?></td>
                  <td><?= date('d-m-Y H:i', strtotime($coin['created_at'])) ?></td>
                  <td>
                    <button class="btn btn-primary save-btn">Save</button>
                    <button class="btn btn-danger delete-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8">Nog geen coins toegevoegd aan je wallet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  $(document).ready(function() {
    // ✅ Live update totaalwaarde
    $(".coin-amount").on("input", function() {
      const row = $(this).closest("tr");
      const price = parseFloat(row.find(".price").data("price"));
      const amount = parseFloat($(this).val()) || 0;
      const total = price * amount;
      row.find(".total-value").text(`$${total.toFixed(2)}`);
    });

    // ✅ Save knop
    $(".save-btn").on("click", function() {
      const row = $(this).closest("tr");
      const id = row.data("id");
      const amount = parseFloat(row.find(".coin-amount").val()) || 0;
      const total_value = parseFloat(row.find(".price").data("price")) * amount;

      $.ajax({
        url: "save_coin_db.php",
        type: "POST",
        data: { id, amount, total_value },
        success: function(res) {
          alert("✅ Coin succesvol bijgewerkt!");
        },
        error: function() {
          alert("❌ Er is iets misgegaan bij het opslaan.");
        }
      });
    });

    // 🗑️ Delete knop
    $(".delete-btn").on("click", function() {
      const row = $(this).closest("tr");
      const id = row.data("id");

      if (confirm("Weet je zeker dat je deze coin wilt verwijderen?")) {
        $.ajax({
          url: "delete_coin_db.php",
          type: "POST",
          data: { id },
          success: function(res) {
            if (res.trim() === "success") {
              row.fadeOut(300, function() { $(this).remove(); });
            } else {
              alert("❌ Verwijderen mislukt.");
            }
          },
          error: function() {
            alert("❌ Er is iets misgegaan bij het verwijderen.");
          }
        });
      }
    });
  });
  </script>

</body>
</html>
