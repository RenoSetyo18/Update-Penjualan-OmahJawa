%%{init: {"theme":"base", "themeVariables": {"actorBorder":"#333","actorBackground":"#eef","primaryColor":"#4f83cc","lineColor":"#333"}}}%%
usecaseDiagram
    actor Pengguna
    actor Pelayan
    actor Kasir
    actor Koki
    actor Admin

    Pengguna --> (Login)
    Pengguna --> (Register)
    Pengguna --> (Logout)

    Pelayan --> (Lihat Menu)
    Pelayan --> (Buat Pesanan)
    Pelayan --> (Update Status Transaksi)
    Pelayan --> (Lihat Dashboard)

    Kasir --> (Lihat Dashboard)
    Kasir --> (Proses Pembayaran)
    Kasir --> (Cetak Struk)
    Kasir --> (Cetak Rekap Kasir)
%%{init: {"theme":"base", "themeVariables": {"actorBorder":"#333","actorBackground":"#eef","primaryColor":"#4f83cc","lineColor":"#333"}}}%%
usecaseDiagram
    actor Pengguna
    actor Pelayan
    actor Kasir
    actor Koki
    actor Admin

    Pengguna --> (Login)
    Pengguna --> (Register)
    Pengguna --> (Logout)

    Pelayan --> (Lihat Menu)
    Pelayan --> (Buat Pesanan)
    Pelayan --> (Update Status Transaksi)
    Pelayan --> (Lihat Dashboard)

    Kasir --> (Lihat Dashboard)
    Kasir --> (Proses Pembayaran)
    Kasir --> (Cetak Struk)
    Kasir --> (Cetak Rekap Kasir)

    Koki --> (Lihat Dashboard)
    Koki --> (Lihat Antrian Koki)
    Koki --> (Update Status Transaksi)

    Admin --> (Lihat Dashboard)
    Admin --> (Lihat Laporan)
    Admin --> (Cetak Laporan)
    Admin --> (Kelola Karyawan)
    Admin --> (Kelola Jenis Menu)
    Admin --> (Kelola Master Menu)
    Admin --> (Kelola Barang)

    (Lihat Antrian Koki) ..> (Lihat Dashboard) : <<include>>
    (Update Status Transaksi) ..> (Lihat Menu) : <<extend>>
    (Cetak Laporan) ..> (Lihat Laporan) : <<include>>
    Koki --> (Lihat Dashboard)
    Koki --> (Lihat Antrian Koki)
    Koki --> (Update Status Transaksi)

    Admin --> (Lihat Dashboard)
    Admin --> (Lihat Laporan)
    Admin --> (Cetak Laporan)
    Admin --> (Kelola Karyawan)
    Admin --> (Kelola Jenis Menu)
    Admin --> (Kelola Master Menu)
    Admin --> (Kelola Barang)

    (Lihat Antrian Koki) ..> (Lihat Dashboard) : <<include>>
    (Update Status Transaksi) ..> (Lihat Menu) : <<extend>>
    (Cetak Laporan) ..> (Lihat Laporan) : <<include>>