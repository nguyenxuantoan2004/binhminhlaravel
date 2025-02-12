<?php

namespace App\Enums;

enum StatusGlobal
{
    //// maintenance: Đang Bảo trì
    // draft: nháp
    // public: công khai
    // pending: Chờ duyệt, chờ xử lý
    // private: Không công khai
   

    case draft = "Nháp";
    case public = "Công khai";
    case pending = "Chờ duyệt";
    case private = "Không công khai";
    

    case maintenance = "Đang Bảo trì";
}
