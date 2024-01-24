<?php
session_start();
session_unset(); // Membersihkan semua variabel sesi
session_destroy(); // Menghancurkan sesi
header("location: login.php"); // Mengarahkan ke halaman login
exit();
