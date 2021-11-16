<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\RequestType;
use App\Models\Review;
use App\Models\Therapist;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create($therapistId)
    {
        $therapist = Therapist::find($therapistId);
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;

        if (Auth::user()->patient && Auth::user()->patient->is_active) {
            // determine if user is connected with therapist as a patient (or a former patient)
            $connection = RequestModel::where('patient_id', Auth::user()->patient->id)
                ->where('therapist_id', $therapistId)
                ->where('type_id', $connectionTypeId)
                ->first();

            // return review create form only if user is allowed to leave a review
            if (!empty($connection)) {
                return view('review_new', ['therapist' => $therapist]);
            }
        }

        // otherwise, redirect user back
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $therapistId
     * @param $patientId
     * @return Application|Redirector|RedirectResponse
     */
    public function store(Request $request, $therapistId, $patientId)
    {
        $request->validate([
            'review_mark' => 'required|numeric|min:0|max:10',
            'review_text' => 'required|string|min:10|max:5000'
        ]);

        $review = new Review();
        $review->therapist_id = $therapistId;
        $review->patient_id = $patientId;
        $review->date = date('Y-m-d');
        $review->mark = $request->review_mark;
        $review->text = $request->review_text;
        $review->save();

        // redirect to therapist info page
        $redirectPath = sprintf(
            'therapist/%s/info',
            $therapistId
        );

        return redirect($redirectPath);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id)
    {
        $review = Review::find($id);

        // if user is a review author, he will be able to edit the review
        if (
            !empty($review)
            && Auth::check()
            && Auth::user()->isPatient()
            && Auth::user()->patient
            && Auth::user()->patient->id === $review->patient_id
        ) {
            return view('review_edit', ['review' => $review]);
        }

        // otherwise, return user back
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'review_mark' => 'required|numeric|min:0|max:10',
            'review_text' => 'required|string|min:10|max:5000'
        ]);

        $review = Review::find($id);
        $review->mark = $request->review_mark;
        $review->text = $request->review_text;
        $review->save();

        // redirect to therapist info page
        $redirectPath = sprintf(
            'therapist/%s/info',
            $review->therapist_id
        );

        return redirect($redirectPath);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // inactivate the review
        $review = Review::find($id);
        $review->is_active = false;
        $review->save();

        return redirect()->back();
    }
}
