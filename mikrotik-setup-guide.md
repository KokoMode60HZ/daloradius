# Panduan Setup MikroTik CHR (Cloud Hosted Router)

## 1. Download MikroTik CHR
- Kunjungi: https://mikrotik.com/download
- Download "Cloud Hosted Router" untuk Windows
- File: `chr-7.12.1.vmdk` (atau versi terbaru)

## 2. Install VirtualBox (jika belum ada)
- Download VirtualBox: https://www.virtualbox.org/
- Install VirtualBox

## 3. Setup CHR di VirtualBox
1. Buka VirtualBox
2. Klik "New" → beri nama "MikroTik CHR"
3. Type: Linux, Version: Other Linux (64-bit)
4. Memory: 256 MB (minimum)
5. Hard disk: Use existing → pilih file .vmdk yang didownload
6. Network: Adapter 1 → Bridged Adapter (untuk akses dari XAMPP)

## 4. Konfigurasi Network
- Start VM MikroTik CHR
- Login default: admin (tanpa password)
- Set IP address: `/ip address add address=192.168.1.1/24 interface=ether1`
- Set gateway: `/ip route add gateway=192.168.1.1`

## 5. Enable API
- `/ip service enable api`
- `/ip service set api port=8728`

## 6. Buat User untuk API
- `/user add name=api-user password=api123 group=full`

## 7. Test Koneksi
- Dari XAMPP, ping ke 192.168.1.1
- Pastikan bisa akses router dari browser: http://192.168.1.1

## Informasi untuk Integrasi:
- **Router IP**: 192.168.1.1
- **API Port**: 8728
- **Username**: api-user
- **Password**: api123
