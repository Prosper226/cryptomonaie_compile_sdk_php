<?php

use Ligdicash\Ligdicash;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$ligdicash  = new Ligdicash('MYAPP');

$payment = $ligdicash->payment(['phone' => 22657474578, 'amount' => 1000, 'bash' => '202306I003', 'otp' => 371483 ]);
print_r($payment);
// $token1 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MTc0MjAiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNTowNTo1NyIsImV4cGlyeV9kYXRlIjoxNjczMTAwMzU3fQ.Nv1cj-OnMjXAr-UNlu96hagdPB_9hKlyfYp4fsa-oA8";

// $payment = $ligdicash->payment(['phone' => 22657474578, 'amount' => 500, 'bash' => '202306I002', 'otp' => 727445 ]);
// print_r($payment);
// $token2 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MTg3MTYiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNToxNjozNiIsImV4cGlyeV9kYXRlIjoxNjczMTAwOTk2fQ.Sar8XMT2ht64V-bxOX2H-p_j9vfFU8mWZfwrwNHehIc";


// $payment = $ligdicash->payment(['phone' => 22660565103, 'amount' => 500, 'bash' => '202306I002' ]);
// print_r($payment);

// $token3 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MjI0OTYiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNTo1MToxOCIsImV4cGlyeV9kYXRlIjoxNjczMTAzMDc4fQ.tFOxhkzq7wohS7al-zEzXWWjVXeIRXXuAQPvoCe3laY";
// $statusPayment = $ligdicash->paymentStatus($token3);
// print_r($statusPayment); 

?>