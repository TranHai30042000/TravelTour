<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class LoginAdminController extends Controller
{
    public function login(Request $request)
    {

        if ($request->isMethod('post')) {
            $messages = [
                'email.exists' => 'Email hoặc mật khẩu sai.Vui lòng nhập lại',   //'exists'kiểm tra xem email có tồn tại ko, nếu ko tồn tại in lỗi
                'email.required' => 'Vui lòng nhập email',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu ít nhất có 6 ký tự',

            ];
            $this->validate($request, [
                'email' => 'required|email|exists:account,email',
                'password' => 'required|alpha_num|min:6|max:32',
            ], $messages);

            $email = $request->email;
            $password = $request->password;
          
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                if (Auth::user()->status == 1) {
                    toastr()->success('Đăng nhập thành công!');
                    return redirect()->route('ht.admin');
                } else {
                    Auth::logout();
                    echo "tài khoản bị cấm";
                }
            } else {
                return redirect()->route('ht.login')
                    ->withErrors(['email' => 'Email hoặc mật khẩu sai.Vui lòng nhập lại']) //Được sử dụng để đặt các thông báo lỗi cho người dùng.
                    ->withInput($request->only('email')); //Được sử dụng để giữ lại giá trị trong ô input khi ngdùng nhập sai "dùng hàm only() để giữ lại ô input mình cần giữ"
            }

        } else {
            return view("admin/login_admin/login_admin");
        }


    }
    public function logout()
    {
        return redirect()->route('ht.login')->with(Auth::logout());
    }
}
