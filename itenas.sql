-- Membuat database itenas
CREATE DATABASE itenas;

-- Menggunakan database itenas
USE itenas;

-- Membuat tabel mahasiswa
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat TEXT
);
