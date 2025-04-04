<?php

namespace App\Service;

use App\Models\Blog;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogService {

    public function detail($id)
    {
        try {
            $blog = Blog::where('uuid', $id)->firstOrFail();
            return response()->json(['data' => $blog]);
        } catch (Exception $e) {
            // return response()->json(['message' => 'Blog not found'], 404);
        }
    }

    public function update(array $validated, string $id)
    {
        try {
            $validated['uuid'] = Str::uuid();
            $blogs = Blog::where('uuid', $id)->update($validated);
            return response()->json(['message' => 'Blog updated successfully']);
        } catch (Exception $e) {
            // return response()->json(['message' => 'Blog not found'], 404);
        }
    }
    public function delete($id)
    {
        Blog::where('uuid', $id)->delete();
        return response()->json(['message' => 'Blog deleted successfully']);
    }

    public function create(array $validated)
    {
        $validated['uuid'] = Str::uuid();
        Blog::create($validated);
        return response()->json(['message' => 'Blog created successfully']);
    }

    public function getDataTable($request)
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
