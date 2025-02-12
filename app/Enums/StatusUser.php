<?php

namespace App\Enums;

enum StatusUser
{
    //
    // active: Hoạt động
    // inactive: Không hoạt động
    // temporary lock: khóa tạm thời
    // permanently locked: khóa vĩnh viễn
    case active = "Hoạt động";
    case temporary_lock = "Khóa tạm thời";
    case permanently_locked = "Khóa vĩnh viễn";
}
