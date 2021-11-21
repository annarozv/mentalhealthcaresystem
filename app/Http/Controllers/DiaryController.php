<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\DiaryRecord;
use App\Models\DiaryRecordSymptom;
use App\Models\IllnessSymptom;
use App\Models\Request as RequestModel;
use App\Models\RequestType;
use App\Models\State;
use App\Models\Status;
use App\Models\Symptom;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $patientId
     * @return Application|Factory|View|RedirectResponse
     */
    public function index($patientId)
    {
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
        $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;

        // get therapist ids to determine the therapists that are allowed to see the diary
        $therapistIds = RequestModel::where('patient_id', $patientId)
            ->where('type_id', $connectionTypeId)
            ->where('status_id', $activeStatusId)
            ->pluck('therapist_id')
            ->toArray();

        // user can access the diary if he is a diary author of he is therapist of the patient who is a diary author
        if (
            Auth::check()
            && (
                (Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->id == $patientId)
                || (
                    Auth::user()->isTherapist()
                    && Auth::user()->therapist
                    && in_array(Auth::user()->therapist->id, $therapistIds)
                )
            )
        ) {
            $records = DiaryRecord::where('patient_id', $patientId)->where('is_active', true)->get();

            return view('diary_records', ['records' => $records, 'patientId' => $patientId]);
        }

        // if not allowed to access the diary, user is redirected back
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $patientId
     * @return Application|Factory|View|RedirectResponse
     */
    public function create($patientId)
    {
        $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
        $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;

        // get therapist ids to determine the therapists that are allowed to see the diary
        $therapistIds = RequestModel::where('patient_id', $patientId)
            ->where('type_id', $connectionTypeId)
            ->where('status_id', $activeStatusId)
            ->pluck('therapist_id')
            ->toArray();

        // user can add diary record if he is a diary author of he is therapist of the patient who is a diary author
        if (
            Auth::check()
            && (
                (Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->id == $patientId)
                || (
                    Auth::user()->isTherapist()
                    && Auth::user()->therapist
                    && in_array(Auth::user()->therapist->id, $therapistIds)
                )
            )
        ) {
            // get all states and symptoms. States are hardcoded and do not have is_active field, so no check is necessary
            $states = State::all();
            $symptoms = Symptom::where('is_active', true)->get();

            return view('record_new', [
                'states' => $states,
                'symptoms' => $symptoms,
                'patientId' => $patientId
            ]);
        }

        // if user is not allowed adding new record, redirect back
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $patientId
     * @return RedirectResponse
     */
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'record_text' => 'required|string|min:10|max:5000'
        ]);

        if (Auth::check()) {
            $record = new DiaryRecord();
            $record->patient_id = $patientId;
            $record->date = date('Y-m-d');
            $record->state_id = $request->state;
            $record->author_id = Auth::id();
            $record->record_text = $request->record_text;
            $record->save();

            // save information about the symptoms connected with a record
            $symptomIds = $request->symptoms;

            if (!empty($symptomIds) && count($symptomIds)) {
                foreach ($symptomIds as $symptomId) {
                    $recordSymptom = new DiaryRecordSymptom();
                    $recordSymptom->record_id = $record->id;
                    $recordSymptom->symptom_id = $symptomId;
                    $recordSymptom->save();
                }
            }

            // redirect to record info page after saving
            $redirectPath = sprintf(
                'record/%s/info',
                $record->id
            );

            return redirect($redirectPath);
        }


        // if user is not allowed adding new record, redirect back
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id)
    {
        $diaryRecord = DiaryRecord::where('id', $id)->where('is_active', true)->first();

        if (!empty($diaryRecord)) {
            $connectionTypeId = RequestType::where('type', RequestType::CONNECTION)->first()->id;
            $activeStatusId = Status::where('status', Status::APPROVED)->first()->id;

            // get therapist ids to determine the therapists that are allowed to see the diary
            $therapistIds = RequestModel::where('patient_id', $diaryRecord->patient_id)
                ->where('type_id', $connectionTypeId)
                ->where('status_id', $activeStatusId)
                ->pluck('therapist_id')
                ->toArray();

            // user can see diary record if he is a diary author of he is therapist of the patient who is a diary author
            if (
                Auth::check()
                && (
                    (Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->id == $diaryRecord->patient_id)
                    || (
                        Auth::user()->isTherapist()
                        && Auth::user()->therapist
                        && in_array(Auth::user()->therapist->id, $therapistIds)
                    )
                )
            ) {
                $symptomIds = DiaryRecordSymptom::where('record_id', $diaryRecord->id)->get('symptom_id');
                $symptoms = Symptom::where('is_active', true)->whereIn('id', $symptomIds)->get();
                $comments = Comment::where('record_id', $diaryRecord->id)->where('is_active', true)->get();

                return view('record_details', [
                    'record' => $diaryRecord,
                    'symptoms' => $symptoms,
                    'comments' => $comments
                ]);
            }
        }

        // if user is not allowed to access the record, he will be redirected back
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id)
    {
        $record = DiaryRecord::where('id', $id)->where('is_active', true)->first();

        // if user is an author he will be able to edit the record
        if (!empty($record) && Auth::check() && Auth::id() === $record->author_id) {
            return view('record_edit', ['record' => $record]);
        }

        // otherwise, user will be redirected back
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
            'record_text' => 'required|string|min:10|max:5000'
        ]);

        $diaryRecord = DiaryRecord::find($id);

        // if diary record is found, information can be updated and saved
        if (!empty($diaryRecord)) {
            $diaryRecord->record_text = $request->record_text;
            $diaryRecord->save();

            // redirect to record info page
            $redirectPath = sprintf(
                'record/%s/info',
                $id
            );

            return redirect($redirectPath);
        }

        // if record is not found than user is just redirected to homepage
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        $record = DiaryRecord::find($id);

        if ($record) {
            $record->is_active = false;
            $record->save();

            // delete connection between record and symptoms
            DiaryRecordSymptom::where('record_id', $id)->get()->each->delete();
        }

        $redirectPath = sprintf(
            'diary/%s',
            $record->patient_id
        );

        return redirect($redirectPath);
    }

    /**
     * Delete all diary records
     *
     * @param $patientId
     * @return Application|RedirectResponse|Redirector
     */
    public function deleteAllRecords($patientId)
    {
        // check if current user is a diary author
        if (Auth::check() && Auth::user()->patient && Auth::user()->patient->id === (int) $patientId) {
            $records = DiaryRecord::where('patient_id', $patientId)->where('is_active', true)->get();

            // deactivate every record and remove symptoms that are connected with them
            foreach ($records as $record) {
                $record->is_active = false;
                $record->save();

                // delete connection between record and symptoms
                DiaryRecordSymptom::where('record_id', $record->id)->get()->each->delete();
            }
        }

        // redirect to diary page
        $redirectPath = sprintf(
            'diary/%s',
            $patientId
        );

        return redirect($redirectPath);
    }
}
