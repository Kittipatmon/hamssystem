<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$code = 'RA-260408';
$a = \App\Models\housing\ResidenceAgreement::where('agreement_code', $code)->first();
if ($a) {
    echo "AGREEMENT $code:\n";
    echo "  EMP_ID (Applicant): " . $a->emp_id . "\n";
    echo "  SEND_STATUS: " . $a->send_status . "\n";
} else {
    echo "AGREEMENT $code NOT FOUND\n";
}

$u = \App\Models\User::where('emp_code', '11668')->first();
echo "USER 11668 EMP_CODE: " . $u->emp_code . "\n";
if ($a && $a->emp_id == $u->emp_code) {
    echo "MATCH: User 11668 IS the applicant.\n";
} else {
    echo "NO MATCH: User 11668 is NOT the applicant.\n";
}
