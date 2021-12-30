<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\MentalIllness;
use App\Models\IllnessSymptom;
use App\Models\Symptom;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class IllnessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        // order illnesses alphabetically by name or name in latvian depending on the selected language
        $illnesses = app()->getLocale() === 'en'
            ? MentalIllness::where('is_active', true)->orderBy('illness_name')->get()
            : MentalIllness::where('is_active', true)->orderBy('illness_name_lv')->get();

        return view('illnesses', ['illnesses' => $illnesses]);
    }

    /**
     * Filter illnesses depending on the keyword
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function filter(Request $request)
    {
        // if input is not empty
        if ($request->keyword) {
            // split keywords into separate words
            $keyArray = preg_split('/[^\w]*([\s]+[^\w]*|$)/', $request->keyword, 0, PREG_SPLIT_NO_EMPTY);
            $keys = array_unique($keyArray);
            $illnesses = [];

            foreach ($keys as $key) {
                $key = sprintf(
                    '%%%s%%',
                    $key
                );

                // depending on locale we filter illnesses list by keyword
                if (app()->getLocale() === 'en') {
                    $illnesses = MentalIllness::where('is_active', true)
                        ->where(function ($query) use ($key) {
                            $query->where('illness_name', 'like', $key)
                                ->orWhere('description', 'like', $key);
                        })
                        ->get();
                }

                if (app()->getLocale() === 'lv') {
                    $illnesses = MentalIllness::where('is_active', true)
                        ->where(function ($query) use ($key) {
                            $query->where('illness_name_lv', 'like', $key)
                                ->orWhere('description_lv', 'like', $key);
                        })
                        ->get();
                }
            }

            return view('illnesses', ['illnesses' => $illnesses]);
        }

        return redirect('illnesses');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // return create view only if user is admin
        if(!Auth::guest() && Auth::user()->isAdmin()) return view('illness_create');

        // otherwise, return illnesses view
        return redirect('illnesses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validationRules = array(
            'illness_name' => 'required|min:5|max:255|unique:mental_illnesses,illness_name',
            'latvian_illness_name' => 'required|min:5|max:255|unique:mental_illnesses,illness_name_lv',
            'description' => 'required|min:10|max:5000',
            'latvian_description' => 'required|min:10|max:5000'
        );
        $this->validate($request, $validationRules);

        $illness = new MentalIllness();
        $illness->illness_name = $request->illness_name;
        $illness->illness_name_lv = $request->latvian_illness_name;
        $illness->description = $request->description;
        $illness->description_lv = $request->latvian_description;
        $illness->save();

        // redirect to illness details page
        $redirectRoute = sprintf(
            'illness/%s/details',
            $illness->id
        );

        return redirect($redirectRoute);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        // get illness details and all the symptoms related to illness
        $illness = MentalIllness::where('is_active', true)->where('id', $id)->first();
        $symptomIdList = IllnessSymptom::where('illness_id', $id)->get(['symptom_id']);
        $symptoms = Symptom::where('is_active', true)->whereIn('id', $symptomIdList)->get();

        return view('illness_details', ['illness' => $illness, 'symptoms' => $symptoms]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $illness = MentalIllness::find($id);

        // return edit view only if user is admin
        if(!Auth::guest() && Auth::user()->isAdmin()) return view('illness_edit', ['illness' => $illness]);

        // otherwise, return illnesses view
        return redirect('illnesses');
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
            'illness_name' => 'required|min:5|max:255|unique:mental_illnesses,illness_name,' . $id,
            'latvian_illness_name' => 'required|min:5|max:255|unique:mental_illnesses,illness_name_lv,' . $id,
            'description' => 'required|min:10|max:5000',
            'latvian_description' => 'required|min:10|max:5000'
        );
        $this->validate($request, $validationRules);

        $illness = MentalIllness::find($id);

        if (!empty($illness)) {
            $illness->illness_name = $request->illness_name;
            $illness->illness_name_lv = $request->latvian_illness_name;
            $illness->description = $request->description;
            $illness->description_lv = $request->latvian_description;
            $illness->save();
        }

        // redirect to illness details page
        $redirectPath = sprintf(
            'illness/%s/details',
            $id
        );

        return redirect($redirectPath);
    }

    /**
     * Display the form where admin can choose symptoms to add to disorder
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function displayAvailableSymptoms($id)
    {
        $illness = MentalIllness::find($id);

        // get the list of symptoms that are not added to illness yet
        $symptomIdList = IllnessSymptom::where('illness_id', $id)->get(['symptom_id']);
        $symptoms = Symptom::where('is_active', true)->whereNotIn('id', $symptomIdList)->get();

        // return view only if user is admin
        if (
            !Auth::guest()
            && Auth::user()->isAdmin()
            && $illness->is_active
            && !empty($symptoms)
            && count($symptoms)
        )
            return view(
            'add_symptoms_to_illness',
            [
                'symptoms' => $symptoms,
                'illness' => $illness
            ]
        );

        // otherwise, redirect to illness details page
        $redirectPath = sprintf(
            'illness/%s/details',
            $id
        );

        return redirect($redirectPath);
    }

    /**
     * Add selected symptoms to illness
     *
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function addSymptoms(Request $request, $id)
    {
        $symptomIds = $request->symptoms;

        // check if any symptoms are chosen
        if (!empty($symptomIds) && count($symptomIds)) {
            foreach ($symptomIds as $symptomId) {
                $illnessSymptom = new IllnessSymptom();
                $illnessSymptom->illness_id = $id;
                $illnessSymptom->symptom_id = $symptomId;
                $illnessSymptom->save();
            }
        }

        // redirect to illness details page
        $redirectPath = sprintf(
            'illness/%s/details',
            $id
        );

        return redirect($redirectPath);
    }

    /**
     * Remove the symptom from illness
     *
     * @param $id
     * @param $symptomId
     * @return Application|RedirectResponse|Redirector
     */
    public function removeSymptom($id, $symptomId)
    {
        //remove relation between symptom and the illness
        IllnessSymptom::where('illness_id', $id)->where('symptom_id', $symptomId)->first()->delete();

        // redirect to illness details page
        $redirectPath = sprintf(
            'illness/%s/details',
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
        // inactivate illness (not physically delete from DB)
        $illness = MentalIllness::find($id);

        if(!empty($illness)) {
            $illness->is_active = false;
            $illness->save();
        }

        return redirect('illnesses');
    }
}
