<?php

use Ligdicash\Ligdicash;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$ligdicash  = new Ligdicash('MYAPP_GN');
// 224620542569
// $payment = $ligdicash->deposit(['phone' => 22657474578, 'amount' => 500, 'bash' => '20230223I01922', 'otp' => 304141 ]);
// $payment = $ligdicash->deposit(['phone' => 22660565103, 'amount' => 100, 'bash' => '20231010_002']);
// $payment = $ligdicash->deposit_withRedirect(['phone' => 224620542569, 'amount' => 500, 'bash' => '20231010_006']);
// print_r($payment);

// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTU0OTMzMjAiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxMjo1NDo0NSIsImV4cGlyeV9kYXRlIjoxNjc3MzI2MDg1fQ.DHZ3CxIEOYvx9i-2NzoLL_MzepyfBnIHRy72tRcsqXs"; // completed
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTU0OTUxMDUiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxMzo0MjowNCIsImV4cGlyeV9kYXRlIjoxNjc3MzI4OTI0fQ.k9N-PhyQ6n5LXCdB-kf7R4iAur3IXEs0kc9b5iBh4LI"; // pending
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTU0OTk3NTEiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNToyOTozNyIsImV4cGlyeV9kYXRlIjoxNjc3MzM1Mzc3fQ.vxABx9vs1qBrQJUXmsjyASlHpcdLWUz_P5c2hiBEFCQ";
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTU1MDA5MzEiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNTo1ODoyOCIsImV4cGlyeV9kYXRlIjoxNjc3MzM3MTA4fQ.n7uWzaHBhLVM5sGpXauqhuX1ZpNL-0-oM-vPn5E5bGM";

// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMjc5MDYxNDkiLCJzdGFydF9kYXRlIjoiMjAyMy0xMC0xMCAxODowMToyNSIsImV4cGlyeV9kYXRlIjoxNjk3MDQwMDg1fQ.6_BGiS7QClIY-BgunNuv0AkZkCkkZKTbXctroUVKKow";
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMjc5MDY0NTMiLCJzdGFydF9kYXRlIjoiMjAyMy0xMC0xMCAxODowNjoyNCIsImV4cGlyeV9kYXRlIjoxNjk3MDQwMzg0fQ.xhrXJfR8lmuw5PcH6AgYDNCT-KgfCebWtZtcmuq_Lx0";
// $statusPayment = $ligdicash->depositStatus($token);
// print_r($statusPayment); 


// $transfert = $ligdicash->withdraw(['phone' => 22657474578, 'amount' => 100, 'bash' => 'D20230224I01452' ]);
// $transfert = $ligdicash->withdraw(['phone' => 22662773863, 'amount' => 100, 'bash' => 'W20230224I01454' ]);
// print_r($transfert);

// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9maW5hbmNlIjoiMjE3NDA3ODUiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNDo1MjoyNiIsImV4cGlyeV9kYXRlIjoxNjc3MzMzMTQ2fQ.Y1P4zEsk8Opl96lbXp7AFP8Gmr3xJoe1smsHf3s2vZQ";
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9maW5hbmNlIjoiMjE3NDM2OTEiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNTozMjowOSIsImV4cGlyeV9kYXRlIjoxNjc3MzM1NTI5fQ.8TxosS2Y1C6IlZUVaFVZYm2F--FaL0MhMK1h0kpSlCE";
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9maW5hbmNlIjoiMjE3NDU4MjEiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNjowMjoxMCIsImV4cGlyeV9kYXRlIjoxNjc3MzM3MzMwfQ.hjk0d29NS6DVzq0COftntCEq3W823jkajACWf2d9QQg";
// // $statusTransfert = $ligdicash->withdrawStatus($token);
// print_r($statusTransfert); 


// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTU0OTMzMjAiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxMjo1NDo0NSIsImV4cGlyeV9kYXRlIjoxNjc3MzI2MDg1fQ.DHZ3CxIEOYvx9i-2NzoLL_MzepyfBnIHRy72tRcsqXs";
// $status = $ligdicash->operationStatus($token, 'deposit');
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9maW5hbmNlIjoiMjE3NDU4MjEiLCJzdGFydF9kYXRlIjoiMjAyMy0wMi0yNCAxNjowMjoxMCIsImV4cGlyeV9kYXRlIjoxNjc3MzM3MzMwfQ.hjk0d29NS6DVzq0COftntCEq3W823jkajACWf2d9QQg";
// $status = $ligdicash->operationStatus($token, 'withdraw');
// print_r($status);


?>