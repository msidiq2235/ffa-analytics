## Cara Menjalankan Project

1. Pastikan **XAMPP** sudah aktif.
2. Buat database di **phpMyAdmin** dengan nama:

   ```text
   ffa_analytics
   ```
3. Jalankan migration di terminal:

   ```bash
   php artisan migrate
   ```
4. Buka **2 terminal** di VS Code dan jalankan masing-masing:

   **Terminal 1**

   ```bash
   npm run dev
   ```

   **Terminal 2**

   ```bash
   php artisan serve
   ```
