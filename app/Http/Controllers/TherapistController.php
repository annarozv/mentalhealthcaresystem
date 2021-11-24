<?php

namespace App\Http\Controllers;

use App\Models\DiaryRecord;
use App\Models\DiaryRecordSymptom;
use App\Models\Gender;
use App\Models\IllnessSymptom;
use App\Models\MentalIllness;
use App\Models\Patient;
use App\Models\RequestType;
use App\Models\Review;
use App\Models\Status;
use App\Models\Symptom;
use App\Models\Therapist;
use App\Models\User;
use App\Models\Request as RequestModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class TherapistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $therapists = Therapist::where('is_active', true)->get();

        return view('therapists', ['therapists' => $therapists]);
    }

    /**
     * Filter the therapist list depending on the keyword
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function filter(Request $request)
    {
        $key = sprintf(
            '%%%s%%',
            $request->keyword
        );

        $allTherapists = Therapist::where('is_active', true)->get();

        // search for a keyword in therapist data
        $therapists = Therapist::where('is_active', true)
            ->where(function ($query) use ($key) {
                $query->where('specialization', 'like', $key)
                    ->orWhere('education_information', 'like', $key)
                    ->orWhere('additional_information', 'like', $key);
                })
                ->get();

        foreach ($allTherapists as $therapist) {
            // if therapist name or surname contains the keyword
            $contains = stripos($therapist->user->name, $request->keyword) !== false
                || stripos($therapist->user->surname, $request->keyword) !== false;

            // add therapist to the array, if not present yet
            if ($contains && !$therapists->contains('id', $therapist->id)) {
                $therapists->push($therapist);
            }
        }

        return view('therapists', ['therapists' => $therapists]);
    }

    public function findTherapistsIndividually()
    {
        if (Auth::check() && Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->is_active) {
            $allTherapists = Therapist::where('is_active', true)->get();
            $patient = Auth::user()->patient;

            // long string that will contain all the patient information that can be used for individualized search
            // firstly add patient textual information to the string and one white space as a delimiter
            $dataString = $patient->additional_information . ' ';

            // then get all patient diary record
            $diaryRecords = DiaryRecord::where('patient_id', $patient->id)->where('is_active', true)->get();
            // create an empty Symptom collection
            $symptoms = Collection::make(new Symptom);
            $illnesses = Collection::make(new MentalIllness);

            foreach ($diaryRecords as $diaryRecord) {
                // add all diary record texts to the main search string
                $dataString .=  $diaryRecord->record_text . ' ';

                $connectedSymptomIds = DiaryRecordSymptom::where('record_id', $diaryRecord->id)
                    ->pluck('symptom_id')
                    ->toArray();
                $connectedSymptoms = Symptom::where('is_active', true)->whereIn('id', $connectedSymptomIds)->get();

                foreach ($connectedSymptoms as $connectedSymptom) {
                    if (!$symptoms->contains('id', $connectedSymptom->id)) {
                        $symptoms->push($connectedSymptom);
                    }
                }
            }

            foreach ($symptoms as $symptom) {
                // add symptom data to data string
                $dataString .=  sprintf(
                    '%s %s ',
                    $symptom->symptom_name,
                    $symptom->symptom_name_lv
                );

                // get all the illnesses that are connected with symptoms
                $connectedIllnessIds = IllnessSymptom::where('symptom_id', $symptom->id)
                    ->pluck('illness_id')
                    ->toArray();
                $connectedIllnesses = MentalIllness::where('is_active', true)
                    ->whereIn('id', $connectedIllnessIds)
                    ->get();

                foreach ($connectedIllnesses as $connectedIllness) {
                    if (!$illnesses->contains($connectedIllness)) {
                        $illnesses->push($connectedIllness);
                    }
                }
            }

            // add illness data to the data string
            foreach ($illnesses as $illness) {
                $dataString .= sprintf(
                    '%s %s ',
                    $illness->illness_name,
                    $illness->illness_name_lv
                );
            }

            // split the big data string into the array of words (keys) that will be used to find therapist
            $keyArray = preg_split('/[^\w]*([\s]+[^\w]*|$)/', $dataString, 0,PREG_SPLIT_NO_EMPTY);
            // leave only unique keys in the array to avoid re-doing the same work for the same keys
            $keys = array_unique($keyArray);

            // create an empty collection for storing found therapists
            $therapists = Collection::make(new Therapist);

            // for each key check, if therapist information contains it
            foreach ($keys as $key) {
                // check only if key is longer than 5 symbols
                $minKeyLength = 5;

                if (strlen($key) >= $minKeyLength) {
                    foreach ($allTherapists as $therapist) {
                        $contains = stripos($therapist->specialization, $key) !== false
                            || stripos($therapist->education_information, $key) !== false
                            || stripos($therapist->additional_information, $key) !== false;

                        // if therapist information contains key, add therapist to the collection, if not present yet
                        if ($contains && !$therapists->contains('id', $therapist->id)) {
                            $therapists->push($therapist);
                        }
                    }
                }
            }

            return view('found_therapists', ['therapists' => $therapists]);
        }

        // if user is not a patient or user does not have an active patient information, he will be redirected back
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function create()
    {
        // if user is a therapist
        if (Auth::check() && Auth::user()->isTherapist()) {
            // check if user already has an active therapist information associated with his profile
            $therapistInfo = Therapist::where('user_id', Auth::id())->where('is_active', true)->first();

            if (empty($therapistInfo)) {
                $genders = Gender::all();

                return view('therapist_new', ['genders' => $genders]);
            }
        }

        // otherwise, redirected to homepage
        return redirect('/');
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
            'profile_picture' => 'image|max:10240|mimes:jpeg,png,jpg',
            'date_of_birth' => 'nullable|before:today',
            'specialization' => 'required|min:20|max:5000',
            'education_info' => 'required|min:20|max:5000',
            'education_document' => 'required|max:10000|mimes:pdf',
            'additional_information' => 'nullable|max:5000'
        );
        $this->validate($request, $validationRules);

        // check if database already contains therapist information, associated with current user
        $therapistInfo = Therapist::where('user_id', Auth::id())->first();

        // if no information is present, create a new record
        if (empty($therapistInfo)) {
            $therapistInfo = new Therapist();
            $therapistInfo->user_id = Auth::id();
        } else {
            // else reactivate the old record
            $therapistInfo->is_active = true;
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
            $therapistInfo->profile_picture = $fileName;
        }

        // delete the old education document if present
        if ($therapistInfo->education_document) {
            $filePath = sprintf(
                '%s/%s',
                public_path('documents'),
                $therapistInfo->education_document
            );

            // remove previous document from storage
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        // save the new education document
        $documentFileName = sprintf(
            '%s.%s',
            uniqid(),
            $request->education_document->extension()
        );
        $request->education_document->move(public_path('documents'), $documentFileName);
        $therapistInfo->education_document = $documentFileName;

        $therapistInfo->date_of_birth = $request->date_of_birth;
        $therapistInfo->gender_id = $request->gender;
        $therapistInfo->specialization = $request->specialization;
        $therapistInfo->education_information = $request->education_info;
        $therapistInfo->additional_information = $request->additional_information;
        $therapistInfo->save();

        // redirect to therapist info page
        $redirectPath = sprintf(
            'therapist/%s/info',
            $therapistInfo->id
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
        $therapist = Therapist::where('id', $id)->where('is_active', true)->first();
        $reviews = Review::where('therapist_id', $id)->where('is_active', true)->get();

        // counting the therapist rating
        $ratingSum = 0;
        $rating = 0;

        if (count($reviews)) {
            foreach ($reviews as $review) {
                $ratingSum += $review->mark;
            }

            $rating = round($ratingSum / count($reviews), 2);
        }

        // variables that will determine functionality available in the therapist view
        $isConnected = false;
        $isPatient = false;

        if (Auth::check() && Auth::user()->isPatient()) {
            $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
            $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;
            $initiatedStatusId = Status::where('status', Status::INITIATED)->first()->id;

            // if user has an active patient information
            if (Auth::user()->patient && Auth::user()->patient->is_active) {
                // determine if there is an active connection between patient and therapist
                $connection = RequestModel::where('patient_id', Auth::user()->patient->id)
                    ->where('therapist_id', $id)
                    ->where('type_id', $connectionTypeId)
                    ->whereIn('status_id', [$activeStatusId, $initiatedStatusId])
                    ->first();

                // determine if user was connected with therapist at all
                $connectionAllStatuses = RequestModel::where('patient_id', Auth::user()->patient->id)
                    ->where('therapist_id', $id)
                    ->where('type_id', $connectionTypeId)
                    ->first();

                $isConnected = !empty($connection);
                $isPatient = !empty($connectionAllStatuses);
            }
        }

        return view('therapist_public_info', [
            'therapist' => $therapist,
            'reviews' => $reviews,
            'rating' => $rating,
            'isConnected' => $isConnected,
            'isPatient' => $isPatient
        ]);
    }

    /**
     * Return list of patients, connected with therapist
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function getPatients()
    {
        // check if user is a therapist and has an active therapist info
        if (
            Auth::check()
            && Auth::user()->isTherapist()
            && Auth::user()->therapist
            && Auth::user()->therapist->is_active == true
        ) {
            $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
            $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;
            $patientIds = RequestModel::where('therapist_id', Auth::user()->therapist->id)
                ->where('type_id', $connectionTypeId)
                ->where('status_id', $activeStatusId)
                ->get('patient_id');

            $patients = Patient::whereIn('id', $patientIds)->where('is_active', true)->get();

            return view('therapist_patients', ['patients' => $patients]);

        }

        // otherwise, redirect to homepage
        return redirect('/');
    }

    /**
     * Get all requests, connected with therapist
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function getRequests()
    {
        // first check if user is a therapist with an active therapist info
        if (
            Auth::check()
            && Auth::user()->isTherapist()
            && Auth::user()->therapist
            && Auth::user()->therapist->is_active == true
        ) {
            $requests = RequestModel::where('therapist_id', Auth::user()->therapist->id)->get();

            return view('therapist_requests', ['requests' => $requests]);
        }

        // otherwise, redirect to home page
        return redirect('/');
    }

    /**
     * Disconnect or refuse connection with the patient
     *
     * @param $patientId
     * @return RedirectResponse
     */
    public function disconnectPatient($patientId)
    {
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
        $refusedStatusId = Status::where('status', Status::REFUSED)->first()->id;

        if (Auth::check() && Auth::user()->therapist) {
            $connection = RequestModel::where('therapist_id', Auth::user()->therapist->id)
                ->where('patient_id', $patientId)
                ->where('type_id', $connectionTypeId)
                ->first();

            if ($connection) {
                $connection->status_id = $refusedStatusId;
                $connection->save();
            }
        }

        return redirect()->back();
    }

    /**
     * Approve patient request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function approveRequest($id)
    {
        $request = RequestModel::find($id);
        $approvedStatusId = Status::where('status', Status::APPROVED)->first()->id;

        if ($request) {
            $request->status_id = $approvedStatusId;
            $request->save();
        }

        return redirect()->back();
    }

    /**
     * Remove patient request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function refuseRequest($id)
    {
        $request = RequestModel::find($id);
        $refusedStatusId = Status::where('status', Status::REFUSED)->first()->id;

        if ($request) {
            $request->status_id = $refusedStatusId;
            $request->save();
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit()
    {
        // if user is a therapist that already has therapist information he will be able to edit it
        if (Auth::check() && Auth::user()->isTherapist()) {
            $therapistInfo = Therapist::where('user_id', Auth::id())->where('is_active', true)->first();
            $genders = Gender::all();

            if ($therapistInfo) return view('therapist_edit', ['therapist' => $therapistInfo, 'genders' => $genders]);
        }

        // otherwise, user will be redirected to his profile
        return redirect('profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Redirector|RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $validationRules = array(
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'profile_picture' => 'image|max:10240|mimes:jpeg,png,jpg',
            'date_of_birth' => 'nullable|before:today',
            'specialization' => 'required|min:20|max:5000',
            'education_info' => 'required|min:20|max:5000',
            'education_document' => 'nullable|max:10000|mimes:pdf',
            'additional_information' => 'nullable|max:5000'
        );
        $this->validate($request, $validationRules);

        $therapistInfo = Therapist::find($id);
        $user = User::find($therapistInfo->user_id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->save();

        // if user chose to remove his profile picture
        if ((bool) $request->remove_picture === true) {
            $imagePath = sprintf(
                '%s/%s',
                public_path('images'),
                $therapistInfo->profile_picture
            );

            // remove previous profile picture from storage
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // set user profile picture to default in database
            $therapistInfo->profile_picture = Config::get('app.default_profile_picture_name');
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
            $therapistInfo->profile_picture = $fileName;
        }

        // if new education document is uploaded
        if ($request->education_document) {
            // delete the old education document
            $filePath = sprintf(
                '%s/%s',
                public_path('documents'),
                $therapistInfo->education_document
            );

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // save the new education document
            $documentFileName = sprintf(
                '%s.%s',
                uniqid(),
                $request->education_document->extension()
            );
            $request->education_document->move(public_path('documents'), $documentFileName);
            $therapistInfo->education_document = $documentFileName;
        }

        $therapistInfo->date_of_birth = $request->date_of_birth;
        $therapistInfo->gender_id = $request->gender;
        $therapistInfo->specialization = $request->specialization;
        $therapistInfo->education_information = $request->education_info;
        $therapistInfo->additional_information = $request->additional_information;
        $therapistInfo->save();

        // redirect to therapist info page
        $redirectPath = sprintf(
            'therapist/%s/info',
            $therapistInfo->id
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
        $therapist = Therapist::find($id);
        $therapist->is_active = false;

        $defaultPicture = Config::get('app.default_profile_picture_name');

        // if therapist profile picture was not default
        if ($therapist->profile_picture !== $defaultPicture) {
            // remove profile picture before deactivating patient data
            // not removing profile picture may lead to storage pollution
            $imagePath = sprintf(
                '%s/%s',
                public_path('images'),
                $therapist->profile_picture
            );

            // remove previous profile picture from storage
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // set user profile picture to default in database
            $therapist->profile_picture = $defaultPicture;
        }

        $therapist->save();

        return redirect('profile');
    }
}
