<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crypto</title>
  <link rel="stylesheet" href="styling.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header class="site-header">
    <div class="nav-container">
      <h1 class="logo">💰 Crypto</h1>
      <nav class="nav-menu">
        <a href="index.php" class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>">Coins</a>
        <a href="wallet.php" class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'wallet.php') echo 'active'; ?>">Wallet</a>
      </nav>
    </div>
  </header>

  <div class="page-overlay"></div>

  <div class="content">
    <div style="text-align: center;">
      <h1>Crypto</h1>
      <div class="container">
        <table class="table" id="all-coins-table">
          <thead>
            <tr>
              <th>Short</th>
              <th>Coin</th>
              <th>Price</th>
              <th>Market Cap</th>
              <th>%24hr</th>
              <th>More info</th>
              <th>Add</th>
            </tr>
          </thead>
          <tbody>
            <template id="js-coin-template">
              {{#data}}
              <tr>
                <td>
                  <img src="https://static.coincap.io/assets/icons/{{symbolLowerCase}}@2x.png" alt="{{symbol}}" class="cc-icon">
                  {{symbol}}
                </td>
                <td>{{name}}</td>
                <td>${{priceUsd}}</td>
                <td>${{marketCapUsd}}</td>
                <td>{{changePercent24Hr}}%</td>
                <td>
                  <button class="coin-info-btn btn btn-primary btn-sm"
                          data-id="{{id}}"
                          data-symbol="{{symbol}}"
                          data-name="{{name}}"
                          data-price="{{priceUsd}}"
                          data-marketcap="{{marketCapUsd}}"
                          data-change="{{changePercent24Hr}}"
                          data-history='{{{sparkline}}}'>
                    Info
                  </button>
                </td>
                <td>
                  <button class="coin-wallet-btn btn btn-success btn-sm"
                          data-id="{{id}}"
                          data-symbol="{{symbol}}"
                          data-name="{{name}}"
                          data-price="{{priceUsd}}">
                    Add to Wallet
                  </button>
                </td>
              </tr>
              {{/data}}
            </template>
          </tbody>
        </table>
      </div>

      <div class="modal fade" id="coinModal" tabindex="-1" aria-labelledby="coinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md coin-modal">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="coinModalLabel">Coin Info</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p><strong>Symbol:</strong> <span id="modal-symbol"></span></p>
              <p><strong>Name:</strong> <span id="modal-name"></span></p>
              <p><strong>Price:</strong> $<span id="modal-price"></span></p>
              <p><strong>Market Cap:</strong> $<span id="modal-marketcap"></span></p>
              <p><strong>24h Change:</strong> <span id="modal-change"></span>%</p>
              <canvas id="coin-history-chart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="walletModalLabel">Add to Wallet</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <h5 id="wallet-coin-name"></h5>
              <p>Current Price: $<span id="wallet-coin-price"></span></p>
              <label>Aantal:</label>
              <input type="number" id="wallet-coin-amount" class="form-control" value="1" min="0" step="any">
              <p>Total Price: $<span id="wallet-total-price"></span></p>
              <button id="wallet-add-btn" class="btn btn-primary mt-2">Add</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/4.1.0/mustache.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="app.js"></script>

  <script>
    // ✅ Verberg modals bij het laden van de pagina
    $(document).ready(function() {
      $("#coinModal, #walletModal").modal('hide');
    });
  </script>
</body>
</html>
