<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Services\ResourceScanner;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class ResourceController extends Controller
{
    public function __construct(protected ResourceScanner $scanner){}

    public function index() {
        $resources = Resource::orderBy('group')
            ->orderBy('controller_action')
            ->get()
            ->groupBy('group');

        return view('admin.resources.index', compact('resources'));
    }

    public function sync()
    {
        $result = $this->scanner->sync();

        Swal::toastSuccess([
            'title' => __('Resources synchronized successfully.'),
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000,
        ]);

        $text = '<strong>' . __('Sync Results') . ':</strong> ' . __(':created new, :updated updated, :deleted deleted.', [
            'created' => $result['created'],
            'updated' => $result['updated'],
            'deleted' => $result['deleted'],
        ]);

        return redirect()->back()->with('success', $text);
    }

    public function toggle(Resource $resource)
    {
        $resource->update(['is_active' => !$resource->is_active]);
        Resource::clearCache();

        return response()->json([
            'is_active' => $resource->is_active,
            'message'   => $resource->is_active ? __('Resource enabled.') : __('Resource disabled.'),
        ]);
    }

    public function updateGroup(Request $request, Resource $resource)
    {
        $request->validate(['group' => 'required|string|max:100|regex:/^[\w\s\-]+$/']);

        $resource->update(['group' => trim($request->group)]);
        Resource::clearCache();

        return response()->json(['group' => $resource->group]);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Resource::orderBy('group')
                ->orderBy('controller_action')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($resource) {
                    $el = '<div class="form-check form-switch form-switch-success">
                                <input class="form-check-input toggle-active" type="checkbox" role="switch" data-id="' . $resource->id . '" ' . ($resource->is_active ? 'checked' : '') . '>
                            </div>';
                    return $el;
                })
                ->rawColumns(['is_active'])
                ->make(true);
        }
    }
}
