<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$emp_code = '11668';
$u = \App\Models\User::where('emp_code', $emp_code)->first();
if (!$u) {
    echo "User $emp_code not found\n";
    exit;
}

echo "USER ID: " . $u->id . "\n";
echo "DEPT: " . ($u->dept_id ?? 'NULL') . "\n";
echo "ROLE: " . ($u->role ?? 'NULL') . "\n";
echo "IS_HAMS_EDITOR: " . ($u->is_hams_editor ? 'YES' : 'NO') . "\n";

$userId = $u->id;

// Check Agreement RA-260408
$agreement = \App\Models\housing\ResidenceAgreement::where('agreement_code', 'RA-260408')->first();
if ($agreement) {
    echo "AGREEMENT RA-260408 FOUND:\n";
    echo "  Status: " . $agreement->send_status . "\n";
    echo "  Commander ID: " . ($agreement->commander_id ?? 'NULL') . "\n";
    echo "  ManagerHams ID: " . ($agreement->managerhams_id ?? 'NULL') . "\n";
    echo "  Committee ID: " . ($agreement->Committee_id ?? 'NULL') . "\n";
} else {
    echo "AGREEMENT RA-260408 NOT FOUND\n";
}

// Calculate counts similar to nav
$pAgreements = \App\Models\housing\ResidenceAgreement::where(function ($q) use ($userId) {
    $q->where(function ($sq) use ($userId) {
        $sq->where('send_status', 0)->where('commander_id', $userId);
    })
    ->orWhere(function ($sq) use ($userId) {
        $sq->where('send_status', 1)->where('managerhams_id', $userId);
    })
    ->orWhere(function ($sq) use ($userId) {
        $sq->where('send_status', 2)->where('Committee_id', $userId);
    });
})->count();

echo "P_AGREEMENTS COUNT FOR $userId: $pAgreements\n";
