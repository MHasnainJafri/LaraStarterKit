<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use \Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserRequest;


class UserController extends Controller
{
    

    public function __construct(private UserService $service,private string $view='User'){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request): JsonResponse|View
    {
        $filters = $request->query('filters', []);
        $sortBy = $request->query('sort_by', 'id');
        $sortOrder = $request->query('sort_order', 'asc');
        $perPage = $request->query('per_page', 10);

        $data = $this->service->list($filters, $sortBy, $sortOrder, $perPage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return view("{$this->view}.index", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(UserRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            // Validation rules go here
        ]);

        $record = $this->service->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $record,
                'message' => 'User created successfully.',
            ], 201);
        }

        return redirect()->route("{$this->view}.index")->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse|View
     */
    public function show(User $user): JsonResponse|View
    {
        $record = $this->service->show($user);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $record,
            ]);
        }

        return view("{$this->view}.show", compact('record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            // Validation rules go here
        ]);

        $record = $this->service->update($user, $validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $record,
                'message' => 'User updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(User $user): JsonResponse|RedirectResponse
    {
        $this->service->delete($user);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('messages.delete', ['model' => 'User']),
            ]);
        }

        return redirect()->back()->with('success', trans('User.created', ['model' => 'User']));
    }
}
