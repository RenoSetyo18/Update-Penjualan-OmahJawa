# Use Case Diagram

The application now uses a direct order-to-cashier flow:

```mermaid
flowchart LR
    Pelayan --> Menu[Input pesanan dari Menu]
    Menu --> Pending[Pesanan tersimpan di daftar Kasir: Belum Bayar]
    Kasir --> Pending
    Kasir --> Payment[Konfirmasi pembayaran]
    Payment --> Paid[Status berubah menjadi Lunas]
    Admin --> DailyReport[Lihat laporan pendapatan harian]
    DailyReport --> Print[Cetak laporan]
```

Order statuses are limited to **Belum Bayar** and **Lunas**. The cashier can print
the daily revenue report for a selected date; it includes only paid transactions.
