# Notafy Pro — Mayar Product Page Copy

> Platform: Mayar.id — Subscription / Membership
> Bahasa: Indonesia
> Target: Staff keuangan & auditor perusahaan

---

## Headline

**Audit ratusan nota expense karyawan tanpa input manual.**

---

## Subheadline

Notafy baca foto dan PDF nota kamu otomatis — dari Shopee, Grab, Gojek, sampai struk Indomaret — lalu keluarkan data terstruktur yang siap masuk ke laporan reimbursement.

---

## Pain Points

Kalau kamu familiar dengan situasi ini, Notafy dibuat untuk kamu:

- 📂 **Tumpukan foto nota tiap akhir bulan.** Karyawan kirim screenshot dari berbagai platform — GoFood, Shopee, GrabCar, struk warung — semua campur aduk di satu folder. Kamu yang harus sortir dan input satu-satu.

- 🔢 **Angka salah baca dari struk thermal yang pudar.** Struk kasir Indomaret atau nota warung sering buram, tintanya tipis, atau fotonya miring. Salah baca total tagihan = salah reimburse = revisi lagi.

- ⏱️ **Rekap manual ke Excel buang waktu berjam-jam.** Copy-paste nominal, tanggal, nama vendor dari foto ke spreadsheet. Kalau ada 50 nota per karyawan × 20 karyawan — itu ratusan baris yang harus diisi tangan setiap bulan.

---

## Solusi

Notafy adalah tool OCR (pembaca teks otomatis) yang dibangun khusus untuk nota dan struk expense Indonesia.

Kamu tinggal **upload foto atau PDF nota** — Notafy langsung baca isinya, kenali platformnya, dan keluarkan data terstruktur: platform, vendor, tanggal, total, metode bayar, dan lainnya. Semua tersimpan rapi di riwayat per user, siap di-export untuk laporan reimbursement.

Tidak perlu ketik ulang. Tidak perlu tebak-tebakan angka dari foto blur. Notafy yang kerjain.

---

## Fitur Utama

### 🔍 Deteksi Platform Otomatis
Notafy otomatis kenali dari mana nota itu berasal — Shopee, Tokopedia, GoFood, GoCar, GoRide, GrabFood, GrabBike, GrabCar, Indomaret, atau nota warung. Tidak perlu pilih manual.

### 📋 Output JSON Terstruktur
Setiap nota menghasilkan field lengkap: `platform`, `vendor_name`, `transaction_id`, `transaction_date`, `subtotal`, `discount`, `delivery_fee`, `total_amount`, `payment_method`. Format konsisten, siap masuk ke sistem apapun.

### ⚠️ Confidence Score — Flag Nota yang Perlu Dicek
Setiap hasil OCR dapat skor kepercayaan 0–100%. Kalau nota buram, miring, atau sulit dibaca, sistem otomatis tandai **"Perlu Dicek Manual"** supaya kamu tidak kecolongan data yang salah.

### 📎 Support Foto HP, Scan, dan PDF
Upload dari mana saja: screenshot aplikasi, foto pakai HP, scan dokumen, atau PDF digital langsung dari platform. Format JPG, PNG, dan PDF semua didukung.

### 📁 Riwayat Nota Per Karyawan
Semua hasil OCR tersimpan di riwayat dengan history lengkap — tanggal upload, platform, nominal, dan status review. Akses kapan saja, tidak ada yang hilang.

### 🤖 Mistral OCR — Akurasi Tinggi untuk Nota Sulit
Untuk struk thermal pudar, foto buram, atau tulisan tangan, gunakan engine Mistral OCR (AI cloud). Akurasi jauh di atas OCR biasa, terutama untuk nota warung dan struk Indomaret. Pro Plan: 300 panggilan/bulan.

### 📊 Export Laporan Reimbursement *(Segera Hadir)*
Export semua nota ke Excel — per karyawan, per bulan, per kategori. Langsung bisa masuk ke workflow approval reimbursement tanpa olah data lagi.

### 🔒 Data Tersimpan Aman
File nota dan hasil ekstraksi disimpan di server dengan akses terbatas. Hanya akun kamu yang bisa lihat data kamu.

---

## Untuk Siapa

### 👩‍💼 Staff Keuangan yang Proses Reimburse Bulanan
Kamu yang setiap bulan nerima puluhan sampai ratusan foto nota dari karyawan, harus verifikasi nominal, lalu input ke sistem. Notafy potong waktu input manual dari berjam-jam jadi menit.

### 🔎 Internal Auditor yang Verifikasi Expense Report
Kamu butuh data yang bisa diverifikasi — bukan tebakan dari foto buram. Notafy kasih confidence score, raw text asli, dan flag otomatis kalau ada yang perlu dicek ulang. Audit trail lengkap di setiap nota.

### 🚀 Startup & UMKM yang Mau Digitalisasi Expense Workflow
Belum punya sistem ERP? Notafy bisa jadi titik awal digitalisasi expense — dari foto nota langsung ke data terstruktur, siap integrasi atau export ke tools yang kamu pakai sekarang.

---

## Contoh Output Nyata

Upload foto struk GoFood ini:

```
GoFood
Terima kasih sudah memesan!
Transaction ID: F-20240315-9871234
Warung Makan Bu Tuti
15 Maret 2024 — 12:34

Subtotal        Rp 42.000
Diskon GoPay    Rp -5.000
Ongkir          Rp 9.000
Biaya Layanan   Rp 2.000
─────────────────────────
Total           Rp 48.000
Dibayar via GoPay
```

Notafy keluarkan:

```json
{
  "platform": "gofood",
  "category": "food",
  "transaction_id": "F-20240315-9871234",
  "transaction_date": "2024-03-15",
  "transaction_time": "12:34",
  "vendor_name": "Warung Makan Bu Tuti",
  "subtotal": 42000,
  "discount": 5000,
  "delivery_fee": 9000,
  "service_fee": 2000,
  "tax": null,
  "total_amount": 48000,
  "payment_method": "GoPay",
  "source_type": "digital_jpg",
  "confidence_score": 0.92,
  "needs_review": false
}
```

Semua nilai dalam **IDR sebagai integer** — langsung bisa dihitung, dibandingkan, atau dimasukkan ke formula Excel tanpa preprocessing.

---

## FAQ

**Seberapa akurat hasilnya?**
Untuk nota digital (PDF atau screenshot dari app) akurasi rata-rata 88–95%. Untuk foto HP yang jelas, 75–90%. Struk thermal Indomaret dan nota warung tulisan tangan 45–75% — tapi semua yang di bawah 75% otomatis di-flag untuk dicek manual, jadi tidak ada yang lolos diam-diam.

**Format apa saja yang didukung?**
JPG, PNG, dan PDF. Bisa foto langsung dari HP, scan dokumen, screenshot aplikasi, atau PDF digital dari Shopee/Tokopedia/Grab. Ukuran maksimal 10MB per file.

**Bagaimana kalau nota tidak terbaca?**
Notafy tetap simpan raw text hasil OCR untuk audit trail. Kalau confidence rendah, sistem kasih flag "Perlu Dicek Manual" supaya kamu tahu mana yang perlu dilihat ulang. Untuk nota yang sangat sulit (struk pudar, tulisan tangan lebat), gunakan Mistral OCR — engine AI yang jauh lebih kuat dari OCR standar.

**Apakah data saya aman?**
Ya. File nota dan hasil ekstraksi hanya bisa diakses oleh akun yang mengupload. Tidak ada data yang dibagikan ke pihak ketiga selain Mistral AI (hanya untuk proses OCR, tidak disimpan di sisi mereka). Akses ke aplikasi dilindungi login dan Google SSO.

---

## Mulai Sekarang

**Hentikan input manual. Biarkan Notafy yang baca notanya.**

