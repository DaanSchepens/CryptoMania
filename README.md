# CryptoMania

A modern cryptocurrency tracking and wallet management application built with PHP, JavaScript, and MySQL.

## Features

### 📊 Real-Time Cryptocurrency Data
- View live cryptocurrency prices from CoinCap API
- Display market capitalization, 24-hour price changes
- Interactive price charts for the last 7 days
- Formatted large numbers (K, M, B, T)

### 💼 Personal Wallet Management
- Add cryptocurrencies to your personal wallet
- Track holdings with real-time value calculations
- Edit amounts and update total values
- Delete coins from your wallet
- Persistent storage in MySQL database

### 🎨 Modern UI/UX
- Responsive design that works on desktop and mobile
- Bootstrap-based styling with custom CSS
- Smooth animations and hover effects
- Modal dialogs for detailed coin information and wallet actions

### 🛠️ Technical Stack
- **Frontend**: HTML5, CSS3, JavaScript (jQuery, Chart.js, Mustache.js)
- **Backend**: PHP 7+
- **Database**: MySQL
- **API**: CoinCap API v3
- **Styling**: Bootstrap 5, Custom CSS

## Installation

### Prerequisites
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/MAMP (recommended for local development)

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/DaanSchepens/CryptoMania.git
   cd CryptoMania
   ```

2. **Database Setup:**
   - Create a MySQL database named `crypto_app`
   - Import the following table structure:

   ```sql
   CREATE TABLE wallet (
       id INT AUTO_INCREMENT PRIMARY KEY,
       coin_id VARCHAR(50) NOT NULL,
       symbol VARCHAR(10) NOT NULL,
       name VARCHAR(100) NOT NULL,
       price_usd DECIMAL(20,8) NOT NULL,
       amount DECIMAL(20,8) NOT NULL,
       total_value DECIMAL(20,8) GENERATED ALWAYS AS (price_usd * amount) STORED,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

3. **Configure Database Connection:**
   - Update database credentials in the PHP files if needed:
     - `add_to_wallet.php`
     - `save_coin_db.php`
     - `delete_coin_db.php`
     - `wallet.php`

4. **Web Server Configuration:**
   - Place the project files in your web server's document root
   - Ensure PHP and MySQL are running
   - Access the application at `http://localhost/CryptoMania`

## Usage

### Viewing Cryptocurrencies
- Navigate to the main page (`index.php`)
- Browse the table of cryptocurrencies with real-time data
- Click "Info" to view detailed information and price charts
- Click "Add to Wallet" to add coins to your portfolio

### Managing Your Wallet
- Go to the Wallet page (`wallet.php`)
- View your holdings with current values
- Edit amounts and save changes
- Delete coins you no longer hold

## API Integration

The application uses the CoinCap API v3 for cryptocurrency data:
- Endpoint: `https://rest.coincap.io/v3/assets`
- Authentication: Bearer token included in requests
- Rate limiting: Respect API limits to avoid being blocked

## File Structure

```
CryptoMania/
├── index.php          # Main page with cryptocurrency list
├── wallet.php         # Wallet management page
├── app.js             # Frontend JavaScript logic
├── styling.css        # Custom CSS styles
├── add_to_wallet.php  # API endpoint for adding coins
├── save_coin_db.php   # API endpoint for updating amounts
├── delete_coin_db.php # API endpoint for deleting coins
└── README.md          # This file
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open source and available under the [MIT License](LICENSE).

## Disclaimer

This application is for educational purposes only. Cryptocurrency prices are highly volatile, and this tool should not be used as financial advice. Always do your own research before making investment decisions.