<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
public function index()
{
return view('profile.index');
}
public function updatePassword(Request $request)
{
$request->validate([
'current_password'=>'required',
'new_password'=>'required|min:8|confirmed',
]);
if(!Hash::check($request->current_password,auth()->user()->password)){
return back()->withErrors(['current_password'=>'Password saat ini tidak sesuai dengan data kami.']);
}
auth()->user()->update([
'password'=>Hash::make($request->new_password)
]);
return back()->with('success','Password berhasil diperbarui!');
}
}