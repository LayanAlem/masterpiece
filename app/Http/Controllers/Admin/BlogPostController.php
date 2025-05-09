<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['user', 'comments', 'votes']);

        // Apply filters
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Sorting
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';

        $query->orderBy($sortField, $sortDirection);

        $posts = $query->paginate(10);

        // Get users for filter dropdown
        $users = User::has('blogPosts')->get();

        return view('admin.blog-posts.index', compact('posts', 'users'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        return view('blog-posts.create');
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:published,pending,rejected',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = new BlogPost();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->status;
        $post->user_id = $request->user_id;
        $post->slug = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/blog_images', $filename);
            $post->image = str_replace('public/', '', $path);
        }

        $post->save();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified blog post.
     */
    public function show($id)
    {
        $post = BlogPost::with(['user', 'comments.user', 'votes'])
            ->findOrFail($id);

        return view('admin.blog-posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.blog-posts.edit', compact('post'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:published,pending,rejected',
        ]);

        $post = BlogPost::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->status;
        $post->slug = Str::slug($request->title);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/blog_images', $filename);
            $post->image = str_replace('public/', '', $path);
        }

        $post->save();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post moved to trash.');
    }

    /**
     * Display a listing of trashed blog posts.
     */
    public function trashed()
    {
        $trashedPosts = BlogPost::onlyTrashed()->with('user')->paginate(10);
        return view('admin.blog-posts.trashed', compact('trashedPosts'));
    }

    /**
     * Restore a trashed blog post.
     */
    public function restore($id)
    {
        $post = BlogPost::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('blog-posts.trashed')
            ->with('success', 'Blog post restored successfully.');
    }

    /**
     * Update the status of a blog post.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:published,pending,rejected',
        ]);

        $post = BlogPost::findOrFail($id);
        $post->status = $request->status;
        $post->save();

        return redirect()->back()->with('success', 'Blog post status updated to ' . ucfirst($request->status));
    }
}
