<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // index: list users (lock down with auth in real apps)
    public function index()
    {
        return User::select('id','first_name','last_name','username','email','phone','where_heard','referral_tag')
            ->latest()->paginate(20);
    }

    public function show(User $user)
    {
        return $user->only(['id','first_name','last_name','username','email','phone','where_heard','referral_tag']);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => ['sometimes','string','max:100'],
            'last_name'  => ['sometimes','string','max:100'],
            'where_heard'=> ['sometimes','nullable','string','max:255'],
            'referral_tag'=>['sometimes','nullable','string','max:255'],
        ]);

        $user->update($data);
        return response()->json(['message'=>'Updated','user'=>$user->fresh()]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
