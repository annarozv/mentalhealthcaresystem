<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Therapist;
use App\Models\User;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ModeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index()
    {
        // if user is an Admin, he will be allowed to see moderator list
        if (Auth::check() && Auth::user()->isAdmin()) {
            $moderatorRoleId = Role::where('role', Role::MODERATOR)->first()->id;
            $moderators = User::where('role_id', $moderatorRoleId)->where('is_active', true)->get();

            return view('moderators', ['moderators' => $moderators]);
        }

        // otherwise, user will be redirected back
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create()
    {
        // if user is an Admin, he will be allowed to add moderator
        if (Auth::check() && Auth::user()->isAdmin()) {
            return view('moderator_new');
        }

        // otherwise, user will be redirected back
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validationRules = array(
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        );
        $this->validate($request, $validationRules);

        $moderatorRoleId = Role::where('role', Role::MODERATOR)->first()->id;

        $moderator = new User();
        $moderator->role_id = $moderatorRoleId;
        $moderator->name = $request->name;
        $moderator->surname = $request->surname;
        $moderator->email = $request->email;
        $moderator->password = Hash::make($request->password);
        $moderator->save();

        return redirect('moderators');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        // find the moderator and deactivate him
        $moderator = User::find($id);

        if (!empty($moderator)) {
            $moderator->is_active = false;
            $moderator->save();
        }

        return redirect('moderators');
    }

    /**
     * See all therapists and patients that are registered in the system
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function getSystemUsers()
    {
        // if current user is a moderator, show the view
        if (Auth::check() && Auth::user()->isModerator()) {
            $therapistRoleId = Role::where('role', Role::THERAPIST)->first()->id;
            $patientRoleId = Role::where('role', Role::PATIENT)->first()->id;

            $therapists = User::where('role_id', $therapistRoleId)->get();
            $patients = User::where('role_id', $patientRoleId)->get();

            return view('system_users', ['therapists' => $therapists, 'patients' => $patients]);
        }

        // otherwise, redirect back
        return redirect()->back();
    }

    /**
     * Deactivate a user
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deactivateUser($id)
    {
        $user = User::find($id);

        if (!empty($user)) {
            // deactivate user
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
        }

        return redirect('system/users');
    }

    /**
     * Reactivate a deactivated user
     *
     * @param $id
     * @return RedirectResponse
     */
    public function reactivateUser($id)
    {
        $user = User::find($id);

        if (!empty($user)) {
            // reactivate user
            $user->is_active = true;
            $user->save();

            // reactivate user therapist info if present
            if ($user->isTherapist()) {
                $therapist = Therapist::where('user_id', $user->id)->first();

                if (!empty($therapist)) {
                    $therapist->is_active = true;
                    $therapist->save();
                }
            }

            // reactivate user patient info if present
            if ($user->isPatient()) {
                $patient = Patient::where('user_id', $user->id)->first();

                if (!empty($patient)) {
                    $patient->is_active = true;
                    $patient->save();
                }
            }
        }

        return redirect('system/users');
    }

    /**
     * Filter users depending on the keyword
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function filterUsers(Request $request)
    {
        $keys = [];

        $therapistRoleId = Role::where('role', Role::THERAPIST)->first()->id;
        $patientRoleId = Role::where('role', Role::PATIENT)->first()->id;

        $therapists = Collection::make(new User);
        $patients = Collection::make(new User);

        if ($request->keyword) {
            // split keywords into separate words
            $keyArray = preg_split('/[^\w]*([\s]+[^\w]*|$)/', $request->keyword, 0, PREG_SPLIT_NO_EMPTY);
            $keys = array_unique($keyArray);

            if (!empty($keys) && count($keys)) {
                foreach ($keys as $keyword) {
                    $key = sprintf(
                        '%%%s%%',
                        $keyword
                    );

                    $therapists = User::where('role_id', $therapistRoleId)
                        ->where(function ($query) use ($key) {
                            $query->where('name', 'like', $key)
                                ->orWhere('surname', 'like', $key)
                                ->orWhere('email', 'like', $key);
                        })->get();

                    $patients = User::where('role_id', $patientRoleId)
                        ->where(function ($query) use ($key) {
                            $query->where('name', 'like', $key)
                                ->orWhere('surname', 'like', $key)
                                ->orWhere('email', 'like', $key);
                        })->get();
                }
            }
        } else {
            return redirect('system/users');
        }

        return view('system_users', ['therapists' => $therapists, 'patients' => $patients]);
    }
}
