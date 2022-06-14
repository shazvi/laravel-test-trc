<?php

namespace App\Http\Controllers;

use App\Models\Html;
use App\Models\Link;
use App\Models\Pdf;
use App\Models\Resource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Resource::getAll()->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $resource = Resource::getById($id);
        if(empty($resource)) {
            return response()->json(['error' => "Resource not found"], 404);
        }

        return $resource;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $resource = new Resource([
            'title' => $request->input('title'),
            'type' => $request->input('type'),
        ]);

        // Use DB transactions to avoid unpredictable states in case one of the DB writes fails.
        DB::transaction(function () use ($request, $resource) {
            $resource->save();

            switch ($request->input('type')) {
                case Resource::TYPE_ID['HTML']:
                    $resource->html()->save(new Html([
                        'description' => $request->input('description'),
                        'html' => $request->input('html'),
                    ]));
                    break;

                case Resource::TYPE_ID['Link']:
                    $resource->link()->save(new Link([
                        'link' => $request->input('link'),
                        // form data sends below boolean as string, therefore we need to use filter_var
                        'open_new_tab' => filter_var($request->input('open_new_tab'), FILTER_VALIDATE_BOOLEAN),
                    ]));
                    break;

                case Resource::TYPE_ID['PDF']:
                    $file_name = $request->file('file')->store('public');

                    $resource->pdf()->save(new Pdf([
                        'filename' => str_replace('public', 'storage', $file_name)
                    ]));
                    break;

                default:
                    throw new Exception("Invalid 'type' in `create` request.");
            }
        });

        return response()->json(['success' => "Resource saved"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function update(Request $request, Resource $resource)
    {
        $this->validateRequest($request);

        $resource['title'] = $request->input('title');

        $responseJson = ['success' => "Resource saved"];

        // Use DB transactions to avoid unpredictable states in case one of the DB writes fails.
        DB::transaction(function () use ($request, $resource, &$responseJson) {
            $resource->save();

            switch ($request->input('type')) {
                case Resource::TYPE_ID['HTML']:
                    $resource->html()->update([
                        'description' => $request->input('description'),
                        'html' => $request->input('html'),
                    ]);
                    break;

                case Resource::TYPE_ID['Link']:
                    $resource->link()->update([
                        'link' => $request->input('link'),
                        // form data sends below boolean as string, therefore we need to use filter_var
                        'open_new_tab' => filter_var($request->input('open_new_tab'), FILTER_VALIDATE_BOOLEAN),
                    ]);
                    break;

                case Resource::TYPE_ID['PDF']:
                    if($request->filled('filename')) {
                        Storage::delete(str_replace('storage', 'public', $request->input('filename')));
                    }
                    $file_name = $request->file('file')->store('public');
                    $file_name = str_replace('public', 'storage', $file_name);

                    $resource->pdf()->update([
                        'filename' => $file_name
                    ]);

                    // pass filename in response to update view with new url
                    $responseJson = ['success' => "Resource saved", 'filename' => $file_name];
                    break;

                default:
                    throw new Exception("Invalid 'type' in `update` request.");
            }
        });

        return response()->json($responseJson);
    }

    /**
     * Remove the specified resource from storage, file system.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function destroy(Resource $resource)
    {
        if($resource['type'] === Resource::TYPE_ID['PDF']) {
            $pdf = Pdf::find($resource['id']);
            Storage::delete(str_replace('storage', 'public', $pdf['filename']));
        }

        $resource->delete();

        return response()->json(['success' => "Resource deleted"]);
    }

    /**
     * @param Request $request
     * @return void
     */
    private function validateRequest(Request $request): void
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required',
            'description' => 'exclude_unless:type,' . Resource::TYPE_ID['HTML'] . '|required|string',
            'html' => 'exclude_unless:type,' . Resource::TYPE_ID['HTML'] . '|required|string',
            'link' => 'exclude_unless:type,' . Resource::TYPE_ID['Link'] . '|required|url|string',
            'file' => 'exclude_unless:type,' . Resource::TYPE_ID['PDF'] . '|required|mimes:pdf',
        ]);
    }
}
