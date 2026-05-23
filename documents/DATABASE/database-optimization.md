# Database Optimization — Siliwangi Rental

**Nama File:** `database-optimization.md`  
**Lokasi:** `documents/DATABASE/`  
**Tujuan:** Panduan optimasi database MySQL untuk sistem Siliwangi Rental.

---

## 1. MySQL Configuration

```ini
# my.cnf / my.ini — disarankan untuk server production

[mysqld]
# InnoDB buffer pool (set ke 70% RAM tersedia)
innodb_buffer_pool_size = 1G

# Log slow queries
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 1

# Query cache (MySQL 8: deprecated, gunakan application cache)
# Gunakan Redis/File cache di Laravel

# Connection
max_connections = 150
wait_timeout = 60
interactive_timeout = 60

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci
```

---

## 2. Soft Delete Strategy

Model yang menggunakan `SoftDeletes`:

- `Car` — kendaraan dihapus soft (masih bisa di-restore)
- `User` — akun tidak langsung dihapus
- `Driver` — driver dinonaktifkan, bukan dihapus

Query otomatis menambahkan `WHERE deleted_at IS NULL`.

Untuk query termasuk soft-deleted:

```php
Car::withTrashed()->where('id', $id)->first();
Car::onlyTrashed()->get(); // hanya yang dihapus
```

---

## 3. Analisis Query Lambat

```sql
-- Aktifkan EXPLAIN untuk query lambat
EXPLAIN SELECT * FROM bookings 
WHERE car_id = 5 
  AND status IN ('pending','confirmed')
  AND start_date <= '2026-06-01'
  AND end_date >= '2026-05-20';

-- Cek index yang digunakan
SHOW INDEX FROM bookings;
```

---

## 4. Maintenance Rutin

```bash
# Analisa table statistics (update optimizer stats)
php artisan tinker
>>> DB::statement('ANALYZE TABLE bookings, cars, payments');

# Optimize tables (defrag InnoDB)
>>> DB::statement('OPTIMIZE TABLE cars');
```

---

## 5. Backup Strategy

```bash
# Backup harian otomatis via cron
0 2 * * * mysqldump -u root -p{password} siliwangi_rental \
  | gzip > /backups/db_$(date +\%Y\%m\%d).sql.gz

# Simpan 30 hari terakhir, hapus yang lebih lama
find /backups -name "db_*.sql.gz" -mtime +30 -delete
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
