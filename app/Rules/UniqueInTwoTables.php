<?php

namespace App\Rules;

use Closure;
use DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueInTwoTables implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        // Kiểm tra trong bảng `customers`
        $existsInCustomers = DB::table('customers')->where('email', $value)->exists();

        // Kiểm tra trong bảng `employees`
        $existsInEmployees = DB::table('employees')->where('email', $value)->exists();

        // Nếu email tồn tại trong bất kỳ bảng nào, báo lỗi
        if ($existsInCustomers || $existsInEmployees) {
            $fail("The $attribute must be unique across customers and employees.");
        }
    }
}
