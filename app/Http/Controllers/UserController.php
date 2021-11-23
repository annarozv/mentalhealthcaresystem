<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Therapist;
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
        // deactivate user
        $user = User::find($id);
        $user->is_active = false;
        $user->save();

        // deactivate user therapist info if present
        if ($user->isTherapist()) {
            $therapist = Therapist::where('user_id', $user->id)->first();

            if (!empty($therapist)) {
                $therapist->is_active = false;
                $therapist->save();
            }
        }

        // deactivate user patient info if present
        if ($user->isPatient()) {
            $patient = Patient::where('user_id', $user->id)->first();

            if (!empty($patient)) {
                $patient->is_active = false;
                $patient->save();
            }
        }

        // user is logged out
        Auth::logout();

        return redirect('/');
    }
}
