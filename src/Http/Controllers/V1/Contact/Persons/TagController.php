<?php

namespace Webkul\RestApi\Http\Controllers\V1\Contact\Persons;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PersonRepository $personRepository) {}

    /**
     * Store a newly created resource in storage.
     */
    public function attach(int $id): JsonResponse
    {
        Event::dispatch('persons.tag.create.before', $id);

        $person = $this->personRepository->find($id);

        if (! $person->tags->contains(request()->input('tag_id'))) {
            $person->tags()->attach(request()->input('tag_id'));
        }

        Event::dispatch('persons.tag.create.after', $person);

        return response()->json([
            'message' => trans('rest-api::app.contacts.persons.view.tags.create-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function detach(int $personId): JsonResponse
    {
        Event::dispatch('persons.tag.delete.before', $personId);

        $person = $this->personRepository->find($personId);

        $person->tags()->detach(request()->input('tag_id'));

        Event::dispatch('persons.tag.delete.after', $person);

        return response()->json([
            'message' => trans('rest-api::app.contacts.persons.view.tags.destroy-success'),
        ]);
    }
}