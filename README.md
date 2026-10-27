# FFA Analytics

## Installation & Setup

Ikuti langkah berikut untuk menjalankan project **FFA Analytics** di local environment.

### 1. Clone Repository

```bash
git clone <URL-REPOSITORY>
cd ffa-analytics
```

### 2. Install Dependencies

Install dependency PHP:

```bash
composer install
```

Install dependency JavaScript:

```bash
npm install
```

### 3. Setup Database

Pastikan **XAMPP** sudah aktif, terutama **Apache** dan **MySQL**.

Buka **phpMyAdmin**, kemudian buat database baru dengan nama:

```text
ffa_analytics
```

### 4. Konfigurasi Environment

Buat file `.env` dari `.env.example`:

```bash
cp .env.example .env
```

Kemudian sesuaikan konfigurasi database pada file `.env`:

```env
DB_DATABASE=ffa_analytics
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Migrasi Database

Jalankan migration untuk membuat tabel database:

```bash
php artisan migrate --seed
```

### 6. Jalankan Project

Buka **2 terminal** di VS Code.

**Terminal 1 — Vite**

```bash
npm run dev
```

**Terminal 2 — Laravel**

```bash
php artisan serve
```

Jika berhasil, buka aplikasi melalui:

```text
http://127.0.0.1:8000
```

---

## Tech Stack

* **Laravel**
* **PHP**
* **MySQL**
* **Tailwind CSS**
* **Vite**
* **JavaScript**
* **XAMPP**

## Development

Pastikan kedua terminal tetap aktif selama proses development:

```bash
npm run dev
```

```bash
php artisan serve
```

Setelah keduanya berjalan, project siap digunakan.
