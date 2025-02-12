<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    //
    public function index()
    {
        return view('backend.login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
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
        $user = Employee::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => 'Người dùng không tồn tại.',
            ]);
        }

        if ($user->status !== 'active') {
            return back()->withInput()->withErrors([
                'email' => 'Tài khoản của bạn hiện không hoạt động.',
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') ? true : false;
        // dd($credentials);

        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // dd(auth());
            return redirect()->route('admin.index');
        }

        return back()->withInput()->with([
            "code" => "error",
            "status" => "Thông tin đăng nhập không hợp lệ. Vui lòng thử lại.",
        ]);
    }

    function logout(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
