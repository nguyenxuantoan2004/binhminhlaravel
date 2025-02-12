<?php

namespace App\Enums;

enum StatusInvoice
{
    // pending: Chờ duyệt, chờ xử lý
    // On loan: Đang cho mượn
    // confirmed: Đã xác nhận
    // delivering: Đang giao xe
    // waiting_for_pickup: Chờ nhận xe
    // picked_up: Đã nhận xe
    // completed: Đã hoàn thành
    
    // cancelled: Đã hủy

    case pending = "chờ xử lý";
    case on_loan = "Đang cho mượn";
    case confirmed = "Đã xác nhận";
    case delivering = "Đang giao xe";
    case waiting_for_pickup = "Chờ nhận xe";
    case picked_up = "Đã nhận xe";
    case completed = "Đã hoàn thành";
    
    case cancelled = "Đã hủy";
}
