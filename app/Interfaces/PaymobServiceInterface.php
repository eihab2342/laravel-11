<?php

namespace App\Interfaces;

interface PaymobServiceInterface
{
    public function authenticate();
    public function createOrder($amount);
    public function createIntention($orderId, $validatedData);
}
