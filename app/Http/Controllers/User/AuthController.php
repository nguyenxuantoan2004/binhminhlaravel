<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Rules\UniqueInTwoTables;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;
use Password;
use Str;

class AuthController extends Controller
{
    //
    function showRegisterForm()
    {
        return view("frontend.register");
    }

    function register(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|min:2|max:255',
                'email' => ['required', 'email', new UniqueInTwoTables()],
                "id_card_number" => 'required|digits:12',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'password' => 'required|min:8|max:255',
                'confirm-password' => 'required|min:8|max:255|same:password',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'same' => 'Trường :attribute phải trùng khớp với mật khẩu.',
                'digits' => 'Trường :attribute phải có :digits chữ số.'
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'email' => "<strong>email</strong>",
                'id_card_number' => '<strong>căn cước công dân</strong>',
                'password' => "<strong>mật khẩu</strong>",
                'phone_number' => '<strong>số điện thoại</strong>',
                'confirm-password' => "<strong>nhập lại mật khẩu</strong>",
            ]
        );

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'id_card_number' => $request->id_card_number,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'status' => 'active',
        ]);

        if ($customer) {
            return redirect()->route('home.login.show')->with([
                "code" => "success",
                "status" => "Tạo tài khoản thành công.<br> Bạn có thể đăng nhập ngay bây giờ.",
            ]);
        }
        // Kiểm tra trạng thái người dùng

        return back()->withInput()->with([
            "code" => "error",
            "status" => "Thông tin đăng nhập không hợp lệ. Vui lòng thử lại.",
        ]);

    }

    function showLoginForm()
    {
        return view("frontend.login");
    }

    function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:8|max:255'
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
            ],
            [
                'email' => '<strong>địa chỉ email</strong>',
                'password' => "<strong>mật khẩu</strong>",
            ]
        );

        // Kiểm tra trạng thái người dùng
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return back()->withInput()->withErrors([
                'email' => 'Người dùng không tồn tại.',
            ]);
        }

        if ($customer->status !== 'active') {
            return back()->withInput()->withErrors([
                'email' => 'Tài khoản của bạn hiện không hoạt động.',
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') ? true : false;
        // dd($credentials);

        if (auth()->guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // dd(auth());
            return redirect()->route('home');
        }

        return back()->withInput()->with([
            "code" => "error",
            "status" => "Thông tin đăng nhập không hợp lệ.<br> Vui lòng thử lại.",
        ]);
    }

    function logout(Request $request): RedirectResponse
    {
        auth()->guard("customer")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    function showForgotPasswordForm()
    {
        return view("frontend.forgot-password");
    }

    function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        // return $status === Password::RESET_LINK_SENT
        //     ? back()->with(['status' => __($status)])
        //     : back()->withErrors(['email' => __($status)]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with([
                "code" => "success",
                "status" => "Hệ thống đã gửi email để lấy lại mật khẩu.",
            ]);
        }

        return back()->withInput()->with([
            "code" => "error",
            "status" => "Email không tồn tại trong hệ thống.",
        ]);
    }

    function showResetPasswordForm(string $token)
    {
        return view('frontend.reset-password', ['token' => $token]);
    }

    function resetPassword(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|max:255',
                'password_confirmation' => 'required|min:8|max:255|same:password',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'same' => 'Trường :attribute phải trùng khớp với mật khẩu.',
            ],
            [
                'token' => "<strong>token</strong>",
                'email' => "<strong>email</strong>",
                'password' => "<strong>mật khẩu</strong>",
                'password_confirmation' => "<strong>nhập lại mật khẩu</strong>",
            ]
        );

        // dd($request->all());
        // dd(bcrypt($request->token));

        // dd($request->only('email', 'password', 'password_confirmation', 'token'));


        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Customer $customer, string $password) {
                $customer->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $customer->save();

                event(new PasswordReset($customer));
            }
        );

        if ($status === Password::INVALID_TOKEN) {
            return back()->with([
                'code' => 'error',
                'status' => 'Token không hợp lệ hoặc đã hết hạn.',
            ]);
        }

        // return $status === Password::PASSWORD_RESET
        //     ? redirect()->route('login')->with('status', __($status))
        //     : back()->withErrors(['email' => [__($status)]]);
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('home.login.show')->with([
                "code" => "success",
                "status" => "Thay đổi mật khẩu thành công.",
            ]);
        }

        return back()->withInput()->with([
            "code" => "error",
            "status" => "Thay đổi mật khẩu không thành công.",
        ]);
    }

}
