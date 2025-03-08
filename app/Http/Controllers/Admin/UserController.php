<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\DashboardService;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private DashboardService $service,private string $view='admin.users'){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request)
    {
        

        $data = $this->service->index();
        return view("{$this->view}.index", compact('data'));
    }
   
    public function getData(Request $request)
    {
        // if(request()->wantsJson()){
            // return null;
        return response()->json(
            DataTableService::handle(new User(), $request->all())
        );
    // }


        // Get DataTables parameters
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search')['value'];
        $orderColumn = $request->input('order')[0]['column'];
        $orderDir = $request->input('order')[0]['dir'];

        // Query the database
        $query = User::query();

        // Apply search
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }

        // Apply sorting
        $columns = ['name', 'type', 'city', 'score', 'date', 'progress', 'quantity'];
        $query->orderBy($columns[$orderColumn], $orderDir);

        // Get total records count (without pagination)
        $totalRecords = User::count();

        // Get filtered records count (with search)
        $filteredRecords = $query->count();

        // Apply pagination
        $data = $query->skip($start)->take($length)->get();

        // Prepare response
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];

        return response()->json($response);
    }

}
