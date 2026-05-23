# Backup & Recovery — Siliwangi Rental

**Nama File:** `backup-recovery.md`  
**Lokasi:** `documents/SECURITY/`  
**Tujuan:** Dokumentasi strategi backup dan recovery data sistem Siliwangi Rental.

---

## 1. Backup Strategy

### 1.1 Database Backup

 | Frekuensi | Metode | Retensi |
|---|---|---|
 | Harian (02:00 WIB) | mysqldump + gzip | 30 hari |
 | Mingguan | mysqldump + gzip | 3 bulan |
 | Bulanan | mysqldump + gzip | 1 tahun |

```bash
# Cron harian — /etc/cron.d/siliwangi-backup
0 2 * * * www-data /usr/local/bin/backup-db.sh

# Script backup-db.sh
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/siliwangi"
DB_NAME="siliwangi_rental"

mkdir -p $BACKUP_DIR

mysqldump -u root -p"${MYSQL_ROOT_PASSWORD}" $DB_NAME \
  | gzip > $BACKUP_DIR/db_${DATE}.sql.gz

# Hapus backup lebih dari 30 hari
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +30 -delete

echo "Backup selesai: db_${DATE}.sql.gz"
```

### 1.2 File Storage Backup

```bash
# Backup folder storage (KTP, SIM, foto kendaraan)
tar -czf /var/backups/siliwangi/storage_${DATE}.tar.gz \
  /var/www/rental_project/storage/app/private/
```

---

## 2. Recovery Procedure

### 2.1 Restore Database

```bash
# Pilih file backup
ls /var/backups/siliwangi/db_*.sql.gz

# Restore
gunzip -c /var/backups/siliwangi/db_20260514_020000.sql.gz \
  | mysql -u root -p siliwangi_rental

echo "Database berhasil direstore"
```

### 2.2 Restore Storage

```bash
tar -xzf /var/backups/siliwangi/storage_20260514_020000.tar.gz \
  -C /var/www/rental_project/

php artisan storage:link
```

---

## 3. Recovery Time Objective (RTO)

 | Skenario | Target Recovery |
|---|---|
 | Database corruption | < 2 jam |
 | File storage loss | < 4 jam |
 | Server crash | < 4 jam |
 | Full server failure | < 24 jam |

---

## 4. Backup Testing

- Backup restore di-test setiap **bulan** di environment staging.
- Hasil test dicatat dan dilaporkan ke Owner.

---

Versi: 1.0.0 | Tanggal: 2026-05-14
