<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Patient;
use App\Models\Review;
use App\Models\Therapist;
use App\Models\User;
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
            // if therapist name and surname contains the keyword
            $contains = str_contains($therapist->user->name, $request->keyword)
                || str_contains($therapist->user->surname, $request->keyword);

            // add therapist to the array, if not present yet
            if ($contains && !$therapists->contains('id', $therapist->id)) {
                $therapists->push($therapist);
            }
        }

        return view('therapists', ['therapists' => $therapists]);
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

        return view('therapist_public_info', [
            'therapist' => $therapist,
            'reviews' => $reviews,
            'rating' => $rating
        ]);
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
