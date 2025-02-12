<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Motorbike;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

class ProfileController extends Controller
{


    //
    function showProfileForm()
    {
        $customer = Auth::guard("customer")->user();
        // dd($customer);

        return view("frontend.profile", ['customer' => $customer]);
    }

    public function profile(Request $request)
    {
        //
        $customerID = Auth::guard("customer")->id();

        $customer = Customer::find($customerID);


        if (!$customer) {
            return back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài khách hàng có id = $customerID",
                ]
            );
        }
        // dd($customer->id);
        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers')->ignore($customer->id),
                ],
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                "address" => 'required|string',
                "id_card_number" => 'required|digits:12',
                'driving_license_number' => "nullable|integer",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'unique' => 'Trường :attribute đã tồn tại trong hệ thống.',
                'digits' => 'Trường :attribute phải có :digits chữ số.'
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'email' => '<strong>email</strong>',
                'id_card_number' => '<strong>căn cước công dân</strong>',
                'driving_license_number' => '<strong>số bằng lái xe</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
            ]
        );
        // dd($validatedData);

        $customer->name = $validatedData['name'];
        $customer->email = $validatedData['email'];
        $customer->id_card_number = $validatedData['id_card_number'];
        $customer->driving_license_number = $validatedData['driving_license_number'];
        $customer->address = $validatedData['address'];
        $customer->phone_number = $validatedData['phone_number'];

        if ($customer->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật thông tin thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật thông tin thất bại",
                ]
            );
        }
    }

    function showChangePasswordForm()
    {
        return view("frontend.change-pass");
    }

    function changePassword(Request $request)
    {
        $customerID = Auth::guard("customer")->id();

        $customer = Customer::find($customerID);

        if (!$customer) {
            return back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài khách hàng có id = $customerID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "pass-old" => 'required|string|min:8',
                "pass-new" => 'required|string|min:8',
                "pass-confirm" => 'required|string|min:8|same:pass-new',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
            ],
            [
                "pass-old" => '<strong>mật khẩu hiện tại</strong>',
                "pass-new" => '<strong>mật khẩu mới</strong>',
                "pass-confirm" => '<strong>nhập lại mật khẩu</strong>',
            ]
        );


        if (Hash::check($validatedData['pass-old'], Auth::guard('customer')->user()->password)) {
            $customer->password = bcrypt($validatedData['pass-new']);


            if ($customer->save()) {
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
        } else {
            return back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Mật khẩu hiện tại không chính xác",
                ]
            );
        }

    }

    function showOrderForm()
    {
        $listOrder = Invoice::where('customer_id', Auth::guard('customer')->id())->orderBy("id", "desc")->paginate(10);
        return view("frontend.order", compact('listOrder'));
    }

    function orderCancel(string $id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(
                [
                    "code" => "error",
                    "status" => "Không tồn tại đơn đặt xe có id = $id",
                ]
            );
        }



        if ($invoice->status == 'pending') {
            $invoice->status = 'cancelled';
            if ($invoice->save()) {
                $motorbike = Motorbike::find($invoice->motorbike_id);
                $quantity_new = $motorbike->quantity + 1;
                $motorbike->quantity = $quantity_new;
                $motorbike->save();
                return response()->json(
                    [
                        "code" => "success",
                        "status" => "Hủy đơn đặt xe thành công",
                    ]
                );
            } else {
                return response()->json(
                    [
                        "code" => "error",
                        "status" => "Hủy đơn đặt xe thất bại",
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    "code" => "error",
                    "status" => "Không thể hủy đơn đặt xe đã xác nhận",
                ]
            );
        }
    }



    function showOrderDetailForm(string $id)
    {
        $invoice = Invoice::find($id);

        $motorbike = $invoice->motorbike;
        if (!$invoice) {
            return back()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tại đơn đặt xe có id = $id",
                ]
            );
        }

        return view("frontend.detail-order", compact('invoice', "motorbike"));
    }
}
