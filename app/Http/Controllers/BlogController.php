<?php

namespace App\Http\Controllers;

use App\Http\Requests\blogRequest;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request; // Corrected import
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
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
        Blog::create($validated);
        return response()->json(['message' => 'Blog created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        return response()->json(['data' => Blog::findOrFail($id)]);
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
        $blog = Blog::find($id)->update($validated);

        return response()->json(['message' => 'Blog updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        Blog::destroy($id);
        return response()->json(['message' => 'Blog deleted successfully']);
    }

    public function serverSideTable(Request $request)
    {
        $blogs = Blog::select(['id', 'title', 'content', 'created_at']);
        return DataTables::of($blogs)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function ($query) use ($searchValue) {
                        $query->where('title', 'like', "%{$searchValue}%")
                            ->orWhere('content', 'like', "%{$searchValue}%")
                            ->orWhere('created_at', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->editColumn('created_at', function ($blog) {
                return $blog->created_at->diffForHumans();
            })
            ->addColumn('action', function ($blog) {
                return '
                <div class="d-flex justify-content-center align-items-center">
                <a href="#" class="btn btn-xs btn-primary edit me-2" onClick="editModal(this)" id="' . $blog->id . '"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="#" class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" id="' . $blog->id . '"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
