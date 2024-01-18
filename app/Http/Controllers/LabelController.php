<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Label::class, 'label');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|
        \Illuminate\Foundation\Application|
        \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $labels = Label::all();
        return view('Label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\View|
        \Illuminate\Foundation\Application|
        \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $label = new Label();
        return view('Label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        Label::create($data);

        flash(__('messages.label.created'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label): \Illuminate\Contracts\View\View|
        \Illuminate\Foundation\Application|
        \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $label->update($data);

        flash(__('messages.label.modified'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label): \Illuminate\Http\RedirectResponse
    {
        if ($label->tasks()->exists()) {
            flash(__('messages.label.deleted.error'))->error();
        } else {
            $label->delete();

            flash(__('messages.label.deleted'))->success();
        };

        return redirect()->route('labels.index');
    }
}
