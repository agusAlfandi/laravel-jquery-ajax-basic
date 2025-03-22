<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request; // Corrected import
use Illuminate\Contracts\View\View;
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
    public function store(StoreBlogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $blogs = Blog::all();
        return response()->json($blogs);
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
    public function update(UpdateBlogRequest $request, Blog $blog, $id)
    {
        $blog = Blog::find($id);
        $blog->title = $request->input('title');
        $blog->body = $request->input('body');
        $blog->save();

        return response()->json(['success' => 'Blog updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
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
                <a href="#" class="btn btn-xs btn-primary edit me-2" id="' . $blog->id . '"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="#" class="btn btn-xs btn-danger delete" id="' . $blog->id . '"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
