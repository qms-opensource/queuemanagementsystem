<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAA7RV0LHo:APA91bFhdLZeXePELZGLSiBjd_KULDqf6G91BUMlVY0jm-KEM_fMXOLOUK3iJC3G4qKm3JV2ahKkH5792beNd_kpm2NPRR0I3uQ5LDVgUHFN9VzeJfpZgHRdiORQ6WzKsyPdNT7DPC4-'),
        'sender_id' => env('FCM_SENDER_ID', '1018267184250'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
