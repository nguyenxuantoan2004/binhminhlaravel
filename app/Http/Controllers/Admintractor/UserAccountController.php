<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserAccountController extends Controller
{
    //
    public function showProfile()
    {
        return view('backend.user_account.profile');
    }

    public function updateProfile(Request $request, string $employeeID)
    {

        //
        $employee = Employee::find($employeeID);

        // dd($employee->id, $employeeID);
        if (!$employee) {
            return redirect()->route("employee.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tại người dùng có id = $employeeID",
                ]
            );
        }
        $validatedData = $request->validate(
            [
                "pass-current" => 'required|string',
                "pass-new" => 'required|string|min:8',
                "pass-confirm" => 'required|string|min:8',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
            ],
            [
                "pass-current" => '<strong>mật khẩu hiện tại</strong>',
                "pass-new" => '<strong>mật khẩu mới</strong>',
                "pass-confirm" => '<strong>nhập lại mật khẩu</strong>',
            ]
        );

        if(!Hash::check($validatedData['pass-current'], Auth::guard('web')->user()->password)){
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Mật khẩu hiện tại không đúng.",
                ]
            );
        }

        if($validatedData['pass-new'] != $validatedData['pass-confirm']){
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Mật khẩu nhập lại không đúng.",
                ]
            );
        }

        $employee->password = Hash::make($validatedData['pass-new']);

        if ($employee->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thay đổi mật khẩu thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thay đổi mật khẩu thất bại",
                ]
            );
        }
    }

    public function showChangePassword(){
        return view('backend.user_account.change_password');
    }

    public function updatePassword(Request $request){
        $employeeID = Auth::guard('web')->user()->id;

        $employee = Employee::find($employeeID);

        // dd($employee->id, $employeeID);
        if (!$employee) {
            return redirect()->route("employee.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài nhà cung cấp có id = $employeeID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employees')->ignore($employee->id),
                ],
                "id_card_number" => 'required|digits:12',
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],

            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
                'unique' => 'Trường :attribute đã tồn tại trong hệ thống.',
                'digits' => 'Trường :attribute phải có :digits chữ số.'
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'email' => '<strong>địa chỉ email</strong>',
                'id_card_number' => '<strong>căn cước công dân</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
            ]
        );
    }
}
