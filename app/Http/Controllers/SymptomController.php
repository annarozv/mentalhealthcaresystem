<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\MentalIllness;
use App\Models\IllnessSymptom;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        // order symptoms alphabetically by name or name in latvian depending on the selected language
        $symptoms = app()->getLocale() === 'en'
            ? Symptom::where('is_active', true)->orderBy('symptom_name')->get()
            : Symptom::where('is_active', true)->orderBy('symptom_name_lv')->get();

        return view('symptoms', ['symptoms' => $symptoms]);
    }

    /**
     * Filter the list based on keyword submitted
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
            $symptoms = [];

            foreach ($keys as $key) {
                $key = sprintf(
                    '%%%s%%',
                    $key
                );

                // depending on locale we filter symptoms list by keyword
                if (app()->getLocale() === 'en') {
                    $symptoms = Symptom::where('is_active', true)
                        ->where(function ($query) use ($key) {
                            $query->where('symptom_name', 'like', $key)
                                ->orWhere('description', 'like', $key);
                        })
                        ->get();
                }

                if (app()->getLocale() === 'lv') {
                    $symptoms = Symptom::where('is_active', true)
                        ->where(function ($query) use ($key) {
                            $query->where('symptom_name_lv', 'like', $key)
                                ->orWhere('description_lv', 'like', $key);
                        })
                        ->get();
                }
            }

            return view('symptoms', ['symptoms' => $symptoms]);
        }

        return redirect('symptoms');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Redirector|RedirectResponse|View
     */
    public function create()
    {
        // return create view only if user is admin
        if(!Auth::guest() && Auth::user()->isAdmin()) return view('symptom_create');

        // otherwise, return symptoms view
        return redirect('symptoms');
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
            'symptom_name' => 'required|min:5|max:255|unique:mental_illness_symptoms,symptom_name',
            'latvian_symptom_name' => 'required|min:5|max:255|unique:mental_illness_symptoms,symptom_name_lv',
            'description' => 'required|min:10|max:5000',
            'latvian_description' => 'required|min:10|max:5000'
        );
        $this->validate($request, $validationRules);

        $symptom = new Symptom();
        $symptom->symptom_name = $request->symptom_name;
        $symptom->symptom_name_lv = $request->latvian_symptom_name;
        $symptom->description = $request->description;
        $symptom->description_lv = $request->latvian_description;
        $symptom->save();

        // redirect to symptom details page
        $redirectRoute = sprintf(
            'symptom/%s/details',
            $symptom->id
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
        // get symptom information and related illnesses
        $symptom = Symptom::where('is_active', true)->where('id', $id)->first();
        $illnessIds = IllnessSymptom::where('symptom_id', $id)->get(['illness_id']);
        $illnesses = MentalIllness::where('is_active', true)->whereIn('id', $illnessIds)->get();

        return view('symptom_details', ['symptom' => $symptom, 'illnesses' => $illnesses]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $symptom = Symptom::find($id);

        // return edit view only if user is admin
        if(!Auth::guest() && Auth::user()->isAdmin()) return view('symptom_edit', ['symptom' => $symptom]);

        // otherwise, return symptoms view
        return redirect('symptoms');
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
            'symptom_name' => 'required|min:5|max:255|unique:mental_illness_symptoms,symptom_name,' . $id,
            'latvian_symptom_name' => 'required|min:5|max:255|unique:mental_illness_symptoms,symptom_name_lv,' . $id,
            'description' => 'required|min:10|max:5000',
            'latvian_description' => 'required|min:10|max:5000'
        );
        $this->validate($request, $validationRules);

        $symptom = Symptom::find($id);
        $symptom->symptom_name = $request->symptom_name;
        $symptom->symptom_name_lv = $request->latvian_symptom_name;
        $symptom->description = $request->description;
        $symptom->description_lv = $request->latvian_description;
        $symptom->save();

        // redirect to symptom details page
        $redirectRoute = sprintf(
            'symptom/%s/details',
            $id
        );

        return redirect($redirectRoute);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        // inactivate symptom (not physically delete from DB)
        $symptom = Symptom::find($id);
        $symptom->is_active = false;
        $symptom->save();

        return redirect('symptoms');
    }
}
