CREATE DATABASE IF NOT EXISTS rental_dvd;
USE rental_dvd;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','petugas','pemilik','pelanggan') NOT NULL DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori_dvd (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT
);

CREATE TABLE dvd (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    id_kategori INT,
    tahun_rilis YEAR,
    stok INT DEFAULT 0,
    harga_sewa DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_kategori) REFERENCES kategori_dvd(id)
);

CREATE TABLE pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transaksi_sewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(20) NOT NULL UNIQUE,
    id_pelanggan INT NOT NULL,
    tgl_sewa DATE NOT NULL,
    tgl_jatuh_tempo DATE NOT NULL,
    tgl_kembali DATE NULL,
    total DECIMAL(10,2) DEFAULT 0,
    status ENUM('pinjam','kembali','terlambat') DEFAULT 'pinjam',
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id)
);

CREATE TABLE detail_sewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_dvd INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    harga DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi_sewa(id),
    FOREIGN KEY (id_dvd) REFERENCES dvd(id)
);

INSERT INTO users (nama_lengkap, username, password, role) VALUES
('Administrator',    'admin',     'admin123',     'admin'),
('Petugas Kasir',    'petugas',   'petugas123',   'petugas'),
('Pemilik Toko',     'pemilik',   'pemilik123',   'pemilik'),
('Pelanggan Contoh', 'pelanggan', 'pelanggan123', 'pelanggan');

INSERT INTO pelanggan (user_id, nama_pelanggan, alamat, telepon, email) VALUES
(4, 'Pelanggan Contoh', 'Jl. Contoh No.1', '0800000000', 'pelanggan@example.com');
