<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('emp_code')->first();
if ($u) {
    echo "ID: {$u->id} | Code: {$u->emp_code} | PWD: {$u->password}\n";
} else {
    echo "User not found\n";
}
