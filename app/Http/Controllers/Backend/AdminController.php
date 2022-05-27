<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use MercurySeries\Flashy\Flashy;

class AdminController extends Controller
{
    use AuthenticatesUsers;

    public function changePassword(Request $request)
    {
        $data = [];
        $data['request'] = $request->All();

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, \Illuminate\Support\Facades\Auth::user()->password)) {
                        $fail('Current Password didn\'t match');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'confirmed',
                'different:current_password',
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $user = auth()->user();
            $user->update([
                'password' => Hash::make($data['request']['password'])
            ]);
            Auth::logout();
            Flashy::success('Password Has Changed Please Login with new Password');
            return redirect()->route('admin.login');
        }
    }

    public function basicInformation(Request $request)
    {
        $admin = Admin::where('id', $this->guard()->user()->id)->first();
        if (Auth::Check()) {
            $request_data = $request->only('name', 'image');
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'image' => 'mimes:jpg,bmp,png',
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                if ($request->hasFile('image') == null) {
                    $content = $admin->image;
                } else {
                    $imageFile = sprintf('admin_%s.jpg', random_int(1, 1000));
                    $path = $request->file('image')->storeAs('/images', $imageFile, 'public');
                    $content = '/storage/app/public/' . $path;
                }
                $admin->name = $request_data['name'];
                $admin->image = $content;
                if ($admin->update()) {
                    return redirect()->back()->with('success', 'Your Account Information Changed');
                }
            }
        }
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function dashboard()
    {
        $data['users'] = User::where('role_id', 1)->count();
        $data['vendors'] = User::where('role_id', 2)->count();
        $data['bookings'] = Booking::count();
        $data['experiences'] = Experience::count();
        return view('Backend.dashboard.dashboard', compact('data'));
    }

    public function account()
    {
        return view('Backend.admin.index');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('admin.login');
    }

    protected function authenticated(Request $request, $user)
    {
        Flashy::success("Login Successful.");
        return redirect()->route('admin.home');
    }

}
