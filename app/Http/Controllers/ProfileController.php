<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function index()
    {
        $memberId = Session::get('member_id');

        $member = DB::table('members')
            ->leftJoin('roles', 'members.role_id', '=', 'roles.id')
            ->select('members.*', 'roles.role_name')
            ->where('members.id', $memberId)
            ->first();

        return view('pages.profile', compact('member'));
    }

  public function uploadPhoto(Request $request)
{
    $memberId = Session::get('member_id');

    $member = DB::table('members')->where('id', $memberId)->first();

    if ($request->cropped_image) {

        $image = $request->cropped_image;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = time().'.png';

        Storage::disk('public')->put('profile/'.$imageName, base64_decode($image));

        if ($member->profile_photo) {
            Storage::disk('public')->delete('profile/'.$member->profile_photo);
        }

        DB::table('members')
            ->where('id', $memberId)
            ->update(['profile_photo' => $imageName]);
    }

    return back();
}

    public function removePhoto()
    {
        $memberId = Session::get('member_id');

        $member = DB::table('members')->where('id', $memberId)->first();

        if ($member->profile_photo && Storage::exists('public/profile/'.$member->profile_photo)) {
            Storage::delete('public/profile/'.$member->profile_photo);
        }

        DB::table('members')
            ->where('id', $memberId)
            ->update(['profile_photo' => null]);

        return back();
    }

   public function update(Request $request)
{
    $memberId = Session::get('member_id');

    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
    ]);

    DB::table('members')
        ->where('id', $memberId)
        ->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at' => now()
        ]);

    return back()->with('success', 'Profile updated successfully');
}

public function changePassword(Request $request)
{
    $memberId = Session::get('member_id');

    if (!$memberId) {
        return back()->with('error', 'Session expired. Please login again.');
    }

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:4|different:current_password',
        'confirm_password' => 'required|same:new_password',
    ]);

    $member = DB::table('members')->where('id', $memberId)->first();

    if (!$member) {
        return back()->with('error', 'User not found.');
    }

    // Verify current password
    if (!Hash::check($request->current_password, $member->password)) {
        return back()->with('error', 'Current password is incorrect.');
    }

    // Update new password
    DB::table('members')
        ->where('id', $memberId)
        ->update([
            'password' => Hash::make($request->new_password),
            'updated_at' => now()
        ]);

    return back()->with('success', 'Password changed successfully.');
}

}