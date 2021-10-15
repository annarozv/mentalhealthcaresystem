<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Deactivate the specified user
     *
     * @param  int  $id
     * @return Application|RedirectResponse|Redirector
     */
    public function deactivate($id)
    {
        $user = User::find($id);
        $user->is_active = false;
        $user->save();

        // if user is Admin or a Moderator
        // TODO: redirect back to users view
        if (Auth::user()->isAdmin() || Auth::user()->isModerator()) return redirect('/');

        // if authenticated user was a simple user who deactivated their own account, then he is logged out
        Auth::logout();

        return redirect('/');
    }
}
