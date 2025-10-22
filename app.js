let allCoins = []; // globale variabele

function formatLargeNumber(num) {
    num = parseFloat(num);
    if (isNaN(num)) return "0";
    if (num >= 1e12) return (num / 1e12).toFixed(2) + "T";
    if (num >= 1e9) return (num / 1e9).toFixed(2) + "B";
    if (num >= 1e6) return (num / 1e6).toFixed(2) + "M";
    if (num >= 1e3) return (num / 1e3).toFixed(2) + "K";
    return num.toFixed(2);
}

function getAllCoins() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "https://rest.coincap.io/v3/assets",
        headers: {'Authorization': 'Bearer 40f17b2f6424059623e221d3c1dcf8287c4789ec7b10ff630dda2e3427198835'},
        success: function(data) {

          allCoins = data.data.map(coin => {
                // Voeg symbolLowerCase toe
                coin.symbolLowerCase = coin.symbol.toLowerCase();

                coin.priceUsd = parseFloat(coin.priceUsd).toFixed(2);
                coin.marketCapUsd = formatLargeNumber(coin.marketCapUsd);
                coin.changePercent24Hr = parseFloat(coin.changePercent24Hr).toFixed(2);
                return coin;
            });

            console.log(data);
            allCoins = data.data; // vul globale array

            var coinTemplate = $("#js-coin-template").html();
            var renderTemplate = Mustache.render(coinTemplate, { data: allCoins }); // mustache verwacht {data: ...}
            $("#all-coins-table tbody").append(renderTemplate);
        }
    });
}

let currentChart = null;

function generateChart(label, value) {
    const ctx = document.getElementById('coin-history-chart').getContext('2d');

    if (currentChart) currentChart.destroy();

    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: label, 
            datasets: [{
                label: "Price (USD)",
                data: value,
                borderColor: '#3e95cd',
                borderWidth: 2,
                fill: false,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: { display: true, text: 'Laatste 7 dagen' }
                },
                y: {
                    display: true,
                    title: { display: true, text: 'Price (USD)' }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            elements: { point: { radius: 0 } }
        }
    });
}

function getCoin(btn) {
    let coinId = $(btn).data("id");
    let coin = allCoins.find(c => c.id === coinId);
    if (!coin) return;

    // Vul modal
        $("#modal-symbol").text(coin.symbol);
    $("#modal-name").text(coin.name);
    $("#modal-price").text(parseFloat(coin.priceUsd).toFixed(2));
    $("#modal-marketcap").text(coin.marketCapUsd);
    $("#modal-change")
        .text(parseFloat(coin.changePercent24Hr).toFixed(2) + "%")
        .css("color", coin.changePercent24Hr >= 0 ? "limegreen" : "red");

    // Toon modal
    var myModal = new bootstrap.Modal(document.getElementById('coinModal'));
    myModal.show();

    // Chart: fictieve 7-daagse prijzen
    const value = [];
    const currentPrice = parseFloat(coin.priceUsd);

    const labels = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('nl-NL', { weekday: 'short', day: 'numeric', month: 'short' })); 
        // vb: "ma 7 okt"
        
        let variation = currentPrice * (Math.random() * 0.1 - 0.05); // ±5%
        value.push(parseFloat((currentPrice + variation).toFixed(2)));
    }

    generateChart(labels, value);
}


$(document).ready(function() {
    getAllCoins();

    // Info-knop
    $("#all-coins-table").on("click", ".coin-info-btn", function(e) {
        e.stopPropagation(); // voorkomt dat andere events ook afgaan
        getCoin(this);
    });

    // Add to Wallet-knop
    $("#all-coins-table").on("click", ".coin-wallet-btn", function(e) {
        e.stopPropagation(); // voorkomt dat ook Info getriggerd wordt
        const coinId = $(this).data("id");
        const coin = allCoins.find(c => c.id === coinId);
        if (!coin) return;

        // Vul modal
        $("#wallet-coin-name").text(`${coin.name} (${coin.symbol})`);
        $("#wallet-coin-price").text(parseFloat(coin.priceUsd).toFixed(2));
        $("#wallet-coin-amount").val(1);
        $("#wallet-total-price").text(parseFloat(coin.priceUsd).toFixed(2));

        // Toon modal
        const walletModal = new bootstrap.Modal(document.getElementById('walletModal'));
        walletModal.show();

        // Update total bij input
        $("#wallet-coin-amount").off("input").on("input", function() {
            const amount = parseFloat($(this).val()) || 0;
            const total = (amount * parseFloat(coin.priceUsd)).toFixed(2);
            $("#wallet-total-price").text(total);
        });

        // Klik op Add knop
        $("#wallet-add-btn").off("click").on("click", function() {
            const amount = parseFloat($("#wallet-coin-amount").val()) || 0;
            const total = parseFloat($("#wallet-total-price").text());

            const data = {
                id: coin.id,
                symbol: coin.symbol,
                name: coin.name,
                price: parseFloat(coin.priceUsd),
                amount: amount
            };

            fetch('add_to_wallet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    alert(`✅ ${amount} ${coin.symbol} toegevoegd aan wallet!`);
                    walletModal.hide();
                } else {
                    alert("❌ Fout bij opslaan: " + (response.error || "onbekend probleem"));
                }
            })
            .catch(err => {
                console.error(err);
                alert("❌ Er ging iets mis bij het verzenden naar de server.");
            });
        });
    });
});



