--------------------------------------------------DIgiKArt-------------------------------------------------
------------------------------------------Digital Marketing Solutions--------------------------------------

-- User Information Table
CREATE TABLE IF NOT EXISTS user_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    email VARCHAR(100) DEFAULT NULL UNIQUE,
    phone VARCHAR(255) DEFAULT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    otp_code VARCHAR(10),
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--create table gift_cards
CREATE TABLE gift_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50),
    name VARCHAR(100),
    price DECIMAL(10,2),
    duration VARCHAR(50),
    image VARCHAR(255)
);
-- Cart Table (Updated)
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50),
    target_info VARCHAR(255),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_info(id)
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_info(id)
);
ALTER TABLE orders ADD payment_status VARCHAR(50) DEFAULT 'Pending';
ALTER TABLE orders ADD tran_id VARCHAR(100);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    duration VARCHAR(50),
    target_info VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

ALTER TABLE order_items ADD COLUMN secret_code VARCHAR(20);
ALTER TABLE order_items ADD COLUMN status VARCHAR(20) DEFAULT 'active';


-- BATA inser
INSERT INTO gift_cards (brand, name, price, duration, image) VALUES
('bata', 'Basic Package', 500.00, '1 Week', 'images/bata.png'),
('bata', 'Standard Package', 1200.00, '1 Month', 'images/bata.png'),
('bata', 'Premium Package', 5000.00, '1 Year', 'images/bata.png');

-- APEX insert
INSERT INTO gift_cards (brand, name, price, duration, image) VALUES
('apex', 'Basic Package', 700.00, '1 Week', 'images/apex.png'),
('apex', 'Standard Package', 1500.00, '1 Month', 'images/apex.png'),
('apex', 'Premium Package', 5500.00, '1 Year', 'images/apex.png');

-- GOOGLE PLAY insert
INSERT INTO gift_cards (brand, name, price, duration, image) VALUES
('google', 'Basic Package', 1000.00, '1 Week', 'images/google.png'),
('google', 'Standard Package', 2000.00, '1 Month', 'images/google.png'),
('google', 'Premium Package', 7000.00, '1 Year', 'images/google.png');

-- ARONG insert
INSERT INTO gift_cards (brand, name, price, duration, image) VALUES
('arong', 'Basic Package', 800.00, '1 Week', 'images/arong.png'),
('arong', 'Standard Package', 1600.00, '1 Month', 'images/arong.png'),
('arong', 'Premium Package', 6000.00, '1 Year', 'images/arong.png');

-- NORDVPN insert
INSERT INTO gift_cards (brand, name, price, duration, image) VALUES
('nordvpn', 'Basic Package', 900.00, '1 Week', 'images/nordvpn.png'),
('nordvpn', 'Standard Package', 1800.00, '1 Month', 'images/nordvpn.png'),
('nordvpn', 'Premium Package', 7500.00, '1 Year', 'images/nordvpn.png');

-- create table game_topups
CREATE TABLE game_topups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game VARCHAR(50),
    name VARCHAR(100),
    price DECIMAL(10,2),
    duration VARCHAR(50),
    image VARCHAR(255)
);
-- Free Fire insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('freefire', 'Free Fire 100 Diamonds', 120, 'Instant', 'images/freefire.png'),
('freefire', 'Free Fire 500 Diamonds', 550, 'Instant', 'images/freefire.png'),
('freefire', 'Free Fire 1000 Diamonds', 1000, 'Instant', 'images/freefire.png');

-- PUBG insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('pubg', 'PUBG 60 UC', 110, 'Instant', 'images/pubg.jpg'),
('pubg', 'PUBG 180 UC', 330, 'Instant', 'images/pubg.jpg'),
('pubg', 'PUBG 600 UC', 1050, 'Instant', 'images/pubg.jpg');

-- Call of Duty insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('callofduty', 'Call of Duty - 200 CP', 200, 'Instant', 'images/cod.png'),
('callofduty', 'Call of Duty - 500 CP', 480, 'Instant', 'images/cod.png'),
('callofduty', 'Call of Duty - 1100 CP', 950, 'Instant', 'images/cod.png'),
('callofduty', 'Call of Duty - 2400 CP', 1850, 'Instant', 'images/cod.png'),
('callofduty', 'Call of Duty - 5000 CP', 3600, 'Instant', 'images/cod.png'),
('callofduty', 'Call of Duty - 10000 CP', 7200, 'Instant', 'images/cod.png');

-- GTA insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('gta', 'GTA Red Shark Card (GTA$100,000)', 120, 'Instant', 'images/gta.png'),
('gta', 'GTA Tiger Shark Card (GTA$200,000)', 200, 'Instant', 'images/gta.png'),
('gta', 'GTA Bull Shark Card (GTA$500,000)', 450, 'Instant', 'images/gta.png'),
('gta', 'GTA Great White Shark Card (GTA$1,250,000)', 850, 'Instant', 'images/gta.png'),
('gta', 'GTA Whale Shark Card (GTA$3,500,000)', 2200, 'Instant', 'images/gta.png'),
('gta', 'GTA Megalodon Shark Card (GTA$8,000,000)', 4500, 'Instant', 'images/gta.png');

-- Valorant insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('valorant', 'Valorant 300 Points', 300, 'Instant', 'images/valorant.png'),
('valorant', 'Valorant 600 Points', 600, 'Instant', 'images/valorant.png'),
('valorant', 'Valorant 1000 Points', 1000, 'Instant', 'images/valorant.png');

-- Clash Royale insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('clashroyale', 'Clash Royale Gems 500', 450, 'Instant', 'images/clashroyl.png'),
('clashroyale', 'Clash Royale Gems 1200', 950, 'Instant', 'images/clashroyl.png');

-- Clash of Clans insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('clashofclans', 'Clash of Clans Gems 500', 400, 'Instant', 'images/clashofclans.png'),
('clashofclans', 'Clash of Clans Gems 1200', 850, 'Instant', 'images/clashofclans.png');

-- Minecraft insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('minecraft', 'Minecraft Coins 500', 300, 'Instant', 'images/minecraft.png'),
('minecraft', 'Minecraft Coins 1200', 650, 'Instant', 'images/minecraft.png');

-- Roblox insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('roblox', 'Roblox Robux 400', 450, 'Instant', 'images/roblox.png'),
('roblox', 'Roblox Robux 800', 850, 'Instant', 'images/roblox.png');

-- E-Football insert
INSERT INTO game_topups (game, name, price, duration, image) VALUES
('efootball', 'E-Football Points 2000', 500, 'Instant', 'images/efootball.jpg'),
('efootball', 'E-Football Points 5000', 1100, 'Instant', 'images/efootball.jpg');

--create table subscription_packages
CREATE TABLE subscription_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service VARCHAR(50),
    name VARCHAR(100),
    price DECIMAL(10,2),
    duration VARCHAR(50),
    image VARCHAR(255)
);

INSERT INTO subscription_packages (service, name, price, duration, image) VALUES
-- Netflix
('netflix', 'Netflix 1 Month', 350, '1 Month', 'images/netflix.png'),
('netflix', 'Netflix 3 Months', 950, '3 Months', 'images/netflix.png'),
('netflix', 'Netflix 1 Year', 3600, '1 Year', 'images/netflix.png'),

-- Bongo Bd
('bongo', 'Bongo 1 Month', 200, '1 Month', 'images/bongo.png'),
('bongo', 'Bongo 3 Months', 550, '3 Months', 'images/bongo.png'),
('bongo', 'Bongo 1 Year', 2100, '1 Year', 'images/bongo.png'),

-- Hoichoi
('hoichoi', 'Hoichoi 1 Month', 300, '1 Month', 'images/hoicoi.png'),
('hoichoi', 'Hoichoi 6 Months', 1600, '6 Months', 'images/hoicoi.png'),
('hoichoi', 'Hoichoi 1 Year', 2500, '1 Year', 'images/hoicoi.png'),

-- Chorki
('chorki', 'Chorki 1 Month', 250, '1 Month', 'images/chorki.png'),
('chorki', 'Chorki 3 Months', 700, '3 Months', 'images/chorki.png'),
('chorki', 'Chorki 1 Year', 2700, '1 Year', 'images/chorki.png'),

-- Canva Pro
('canva', 'Canva Pro 1 Month', 450, '1 Month', 'images/canva.png'),
('canva', 'Canva Pro 6 Months', 2500, '6 Months', 'images/canva.png'),
('canva', 'Canva Pro 1 Year', 4500, '1 Year', 'images/canva.png'),

-- Telegram Premium
('telegram', 'Telegram Premium 1 Month', 300, '1 Month', 'images/telegram.png'),
('telegram', 'Telegram Premium 6 Months', 1600, '6 Months', 'images/telegram.png'),
('telegram', 'Telegram Premium 1 Year', 3000, '1 Year', 'images/telegram.png'),

-- Crunchyroll
('crunchyroll', 'Crunchyroll 1 Month', 400, '1 Month', 'images/crunchyroll.png'),
('crunchyroll', 'Crunchyroll 6 Months', 2200, '6 Months', 'images/crunchyroll.png'),
('crunchyroll', 'Crunchyroll 1 Year', 4200, '1 Year', 'images/crunchyroll.png'),

-- YouTube Premium
('youtube', 'YouTube Premium 1 Month', 500, '1 Month', 'images/youtube.png'),
('youtube', 'YouTube Premium 6 Months', 2800, '6 Months', 'images/youtube.png'),
('youtube', 'YouTube Premium 1 Year', 5000, '1 Year', 'images/youtube.png');
-- if insert any item just write as shown in below
('netflix', 'Netflix HD Plan', 480, '1 Month', 'images/netflix.png'),
