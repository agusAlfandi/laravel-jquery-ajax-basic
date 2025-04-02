<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\blogRequest;
use Illuminate\Contracts\View\View;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request; // Corrected import

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
        // $validated['uuid'] = (string) \Illuminate\Support\Str::uuid();
        $validated['uuid'] = Str::uuid();
        Blog::create($validated);
        return response()->json(['message' => 'Blog created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id): JsonResponse
    {
        try {
            $blog = Blog::where('uuid', $id)->firstOrFail();
            return response()->json(['data' => $blog]);
        } catch (Exception $e) {
            // return response()->json(['message' => 'Blog not found'], 404);
        }
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
        // dd($validated);
        try {
            $validated['uuid'] = Str::uuid();
            $blogs = Blog::where('uuid', $id)->update($validated);
            // $blogs->save();
            // $blog = Blog::findOrFail($id)->update($validated);

            return response()->json(['message' => 'Blog updated successfully']);
        } catch (Exception $e) {
            // return response()->json(['message' => 'Blog not found'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        Blog::where('uuid', $id)->delete();
        return response()->json(['message' => 'Blog deleted successfully']);
    }

    public function serverSideTable(Request $request)
    {
        $blogs = Blog::select(['id', 'uuid', 'title', 'content', 'created_at']);
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
                <button class="btn btn-xs btn-primary edit me-2" onClick="editModal(this)" data-id="' . $blog->uuid . '"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                <button class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" data-id="' . $blog->uuid . '"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
