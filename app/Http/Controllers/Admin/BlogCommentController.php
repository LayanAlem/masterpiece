<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['user', 'blogPost']);

        // Filter by status if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by post if provided
        if ($request->has('post_id')) {
            $query->where('blog_post_id', $request->post_id);
        }

        // Search in comment
        if ($request->has('search')) {
            $query->where('comment', 'like', '%' . $request->search . '%');
        }

        // Default sort by latest
        $comments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.blog-comments.index', compact('comments'));
    }

    /**
     * Display the specified comment.
     */
    public function show($id)
    {
        $comment = BlogComment::with(['user', 'blogPost'])->findOrFail($id);
        return view('admin.blog-comments.show', compact('comment'));
    }

    /**
     * Accept the specified comment.
     */
    public function accept($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 'accepted';
        $comment->save();

        return redirect()->back()->with('success', 'Comment has been accepted.');
    }

    /**
     * Reject the specified comment.
     */
    public function reject($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 'rejected';
        $comment->save();

        return redirect()->back()->with('success', 'Comment has been rejected.');
    }

    /**
     * Update the status of the specified comment.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $comment = BlogComment::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();

        return redirect()->back()->with('success', 'Comment status has been updated.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.blog-comments.index')
            ->with('success', 'Comment has been deleted.');
    }
}
