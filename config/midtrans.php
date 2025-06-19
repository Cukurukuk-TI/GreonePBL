<?php

return [
    /**
     * Kredensial dari dashboard Midtrans.
     */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /**
     * Tipe environment:
     * `false` untuk Sandbox (testing).
     * `true` untuk Production (live).
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * Aktifkan fitur keamanan bawaan Midtrans.
     * Sebaiknya selalu `true`.
     */
    'is_sanitized' => true,

    /**
     * Aktifkan 3D Secure untuk transaksi kartu kredit.
     * Sebaiknya selalu `true`.
     */
    'is_3ds' => true,
];
