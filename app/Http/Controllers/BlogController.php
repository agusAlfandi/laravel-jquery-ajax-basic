<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\blogRequest;
use Illuminate\Contracts\View\View;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Http\Request;
use App\Service\BlogService;

class BlogController extends Controller
{

    public function __construct(protected BlogService $blogService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(blogRequest $request): JsonResponse
    {
        $validated = $request->validated();
        return $this->blogService->create($validated);

    }

    /**
     * Display the specified resource.
     */
    public function show(String $id): JsonResponse
    {
     return $this->blogService->detail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(blogRequest $request, string $id)
    {
        $validated = $request->validated();
        return $this->blogService->update($validated, $id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        return $this->blogService->delete($id);
    }

    public function serverSideTable(Request $request): JsonResponse
    {
        return $this->blogService->getDataTable($request);
    }
}
