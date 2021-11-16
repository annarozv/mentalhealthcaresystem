<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Patient;
use App\Models\RequestType;
use App\Models\Status;
use App\Models\Therapist;
use App\Models\User;
use App\Models\Request as RequestModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PatientController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // user can add patient information only if he is a patient
        if (Auth::check() && Auth::user()->isPatient()) {
            // check if user already has patient info
            $patientInfo = Patient::where('user_id', Auth::id())->where('is_active', true)->first();

            // return create view only if no active patient information associated with user was found
            if (empty($patientInfo)) {
                $genders = Gender::all();

                return view('patient_new', ['genders' => $genders]);
            }
        }

        // otherwise, redirect user to the homepage
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'profile_picture' => 'image|max:10240|mimes:jpeg,png,jpg',
            'date_of_birth' => 'nullable|before:today',
            'additional_information' => 'nullable|max:5000'
        ]);

        // check if database already contains patient information associated with current user
        $patientInfo = Patient::where('user_id', Auth::id())->first();

        // if no associated information is present, create a new record
        if (empty($patientInfo)) {
            $patientInfo = new Patient();
            $patientInfo->user_id = Auth::id();
        } else {
            // else reactivate the old record
            $patientInfo->is_active = true;
        }

        // if the image was uploaded in the form
        if ($request->profile_picture) {
            // generate unique name for profile picture for avoiding duplicate names
            $fileName = sprintf(
                '%s.%s',
                uniqid(),
                $request->profile_picture->extension()
            );

            // save image in public folder and store image filename in DB to be able to obtain image
            $request->profile_picture->move(public_path('images'), $fileName);
            $patientInfo->profile_picture = $fileName;
        }

        $patientInfo->date_of_birth = $request->date_of_birth;
        $patientInfo->gender_id = $request->gender;
        $patientInfo->additional_information = $request->additional_information;
        $patientInfo->save();

        // redirect to patient info page
        $redirectPath = sprintf(
            'patient/%s/info',
            $patientInfo->id
        );

        return redirect($redirectPath);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        // patient info is available for patient, his therapists and moderators
        $allowedIdArray = [Patient::find($id)->user_id];

        // get right type and status id for requests
        $requestTypeId = RequestType::where('type', 'Connection')->get('id')->first();
        $requestStatusId = Status::where('status', 'Approved')->get('id')->first();

        // find all therapists, that are connected with patient at the moment
        $therapistIds = RequestModel::where('type_id', $requestTypeId)
            ->where('status_id', $requestStatusId)
            ->get('therapist_id');

        // add therapist user id to allowed user id list
        foreach ($therapistIds as $therapistId) {
            $therapist = Therapist::where('id', $therapistId)->where('is_active', true)->first();

            if (!empty($therapist)) array_push($allowedIdArray, $therapist->user_id);
        }

        if (Auth::check() && (in_array(Auth::id(), $allowedIdArray) || Auth::user()->isModerator())) {
            $patient = Patient::where('id', $id)->where('is_active', true)->first();

            return view('patient_public_info', ['patient' => $patient]);
        }

        // if access is not allowed, redirect to homepage
        return redirect('/');
    }

    /**
     * Show list of therapists associated with a patient
     *
     * @return Application|Factory|View
     */
    public function getTherapists()
    {
        // check if user is patient
        if (Auth::check() && Auth::user()->isPatient()) {
            // check if user has an active patient info
            $patient = Patient::where('user_id', Auth::id())->where('is_active', true)->first();

            if (!empty($patient)) {
                $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
                $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;
                $therapistIds = RequestModel::where('type_id', $connectionTypeId)->where('status_id', $activeStatusId)->get(['therapist_id']);

                // get all the therapists, that are connected with patient
                $therapists = Therapist::whereIn('id', $therapistIds)->where('is_active', true)->get();

                return view('patient_therapists', ['therapists' => $therapists]);
            }
        }

        // if access is not allowed or patient info not present, redirect to homepage
        return redirect('/');
    }

    /**
     * Show all requests connected with patient
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function getRequests()
    {
        // check if user is patient
        if (Auth::check() && Auth::user()->isPatient()) {
            // check if user has an active patient info
            $patient = Patient::where('user_id', Auth::id())->where('is_active', true)->first();

            if (!empty($patient)) {
                $requests = RequestModel::where('patient_id', $patient->id)->get();

                return view('patient_requests', ['requests' => $requests]);
            }
        }

        // if access is not allowed or patient info not present, redirect to homepage
        return redirect('/');
    }

    /**
     * Connect patient with a therapist
     *
     * @param $therapistId
     * @return Application|RedirectResponse|Redirector
     */
    public function connectWithTherapist($therapistId)
    {
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
        $patientCreatedStatusId = Status::where('status', Status::INITIATED)->first()->id;

        // create new connection between therapist and a patient (right now not approved by therapist)
        if (Auth::user()->patient) {
            $patientId = Auth::user()->patient->id;
            $connection = RequestModel::where('type_id', $connectionTypeId)
                ->where('patient_id', $patientId)
                ->where('therapist_id', $therapistId)
                ->first();

            // if no previous connection was recorded between the patient and the therapist, create a new record
            if (empty($connection)) {
                $connection = new RequestModel();
                $connection->patient_id = $patientId;
                $connection->therapist_id = $therapistId;
                $connection->type_id = $connectionTypeId;
            }

            $connection->status_id = $patientCreatedStatusId;
            $connection->save();
        }

        return redirect('my/requests');
    }

    /**
     * Disconnect from the therapist
     *
     * @param $therapistId
     * @return RedirectResponse
     */
    public function disconnectTherapist($therapistId)
    {
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
        $removedStatusId = Status::where('status', Status::REMOVED)->first()->id;

        if (Auth::check() && Auth::user()->patient) {
            $connection = RequestModel::where('patient_id', Auth::user()->patient->id)
                ->where('therapist_id', $therapistId)
                ->where('type_id', $connectionTypeId)
                ->first();

            if ($connection) {
                $connection->status_id = $removedStatusId;
                $connection->save();
            }
        }

        return redirect()->back();
    }

    /**
     * Request feedback from therapist
     *
     * @param $therapistId
     * @return RedirectResponse
     */
    public function requestFeedback($therapistId) {
        $feedbackTypeId = RequestType::where('type', RequestType::FEEDBACK)->first()->id;
        $initiatedStatusId = Status::where('status', Status::INITIATED)->first()->id;

        if (Auth::check() && Auth::user()->patient) {
            $patientId = Auth::user()->patient->id;

            // check if patient has already created a request, that is not yet approved by doctor
            $feedbackRequest = RequestModel::where('patient_id', $patientId)
                ->where('therapist_id', $therapistId)
                ->where('type_id', $feedbackTypeId)
                ->where('status_id', $initiatedStatusId)
                ->first();

            if (empty($feedbackRequest)) {
                // if no initiated feedback request was between patient and therapist
                $newFeedbackRequest = new RequestModel();

                $newFeedbackRequest->patient_id = $patientId;
                $newFeedbackRequest->therapist_id = $therapistId;
                $newFeedbackRequest->type_id = $feedbackTypeId;
                $newFeedbackRequest->status_id = $initiatedStatusId;

                $newFeedbackRequest->save();
            }
        }

        return redirect()->back();
    }

    /**
     * Remove initiated request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function removeFeedbackRequest($id)
    {
        // set 'removed' status for the request
        $removedStatusId = Status::where('status', Status::REMOVED)->first()->id;
        $request = RequestModel::find($id);
        $request->status_id = $removedStatusId;
        $request->save();

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|Redirector|RedirectResponse|View
     */
    public function edit()
    {
        // if user is a patient that already has patient information he will be able to edit it
        if (Auth::check() && Auth::user()->isPatient()) {
            $patientInfo = Patient::where('user_id', Auth::id())->where('is_active', true)->first();
            $genders = Gender::all();

            if ($patientInfo) return view('patient_edit', ['patient' => $patientInfo, 'genders' => $genders]);
        }

        // otherwise, user will be redirected to his profile
        return redirect('profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'profile_picture' => 'image|max:10240|mimes:jpeg,png,jpg',
            'date_of_birth' => 'nullable|before:today',
            'additional_information' => 'nullable|max:5000'
        ]);

        $patient = Patient::find($id);
        $user = User::find($patient->user_id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->save();

        // if user chose to remove his profile picture
        if ((bool) $request->remove_picture === true) {
            $imagePath = sprintf(
                '%s/%s',
                public_path('images'),
                $patient->profile_picture
            );

            // remove previous profile picture from storage
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // set user profile picture to default in database
            $patient->profile_picture = Config::get('app.default_profile_picture_name');
        }

        // if the image was uploaded in the form
        if ($request->profile_picture) {
            // generate unique name for profile picture for avoiding duplicate names
            $fileName = sprintf(
                '%s.%s',
                uniqid(),
                $request->profile_picture->extension()
            );

            // save image in public folder and store image filename in DB to be able to obtain image
            $request->profile_picture->move(public_path('images'), $fileName);
            $patient->profile_picture = $fileName;
        }

        $patient->date_of_birth = $request->date_of_birth;
        $patient->gender_id = $request->gender;
        $patient->additional_information = $request->additional_information;
        $patient->save();

        // redirect to patient info page
        $redirectPath = sprintf(
            'patient/%s/info',
            $id
        );

        return redirect($redirectPath);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);
        $patient->is_active = false;

        $defaultPicture = Config::get('app.default_profile_picture_name');

        // if patient profile picture was not default
        if ($patient->profile_picture !== $defaultPicture) {
            // remove profile picture before deactivating patient data
            // not removing profile picture may lead to storage pollution
            $imagePath = sprintf(
                '%s/%s',
                public_path('images'),
                $patient->profile_picture
            );

            // remove previous profile picture from storage
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // set user profile picture to default in database
            $patient->profile_picture = $defaultPicture;
        }

        $patient->save();

        return redirect('profile');
    }
}
