<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogVote;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'recent');

        $query = BlogPost::with(['user', 'comments', 'votes'])
            ->where('status', 'published');

        // Handle sorting
        if ($sort === 'recent') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'popular') {
            $query->orderBy('vote_count', 'desc');
        }

        $posts = $query->paginate(6);

        // Get top contributors
        $topContributors = User::withCount(['blogPosts', 'blogVotes'])
            ->orderBy('blog_posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('public.pages.hiddenGem', compact('posts', 'topContributors', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit an entry');
        }

        return view('public.pages.competitionUpload');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create blog post
        $post = new BlogPost();
        $post->user_id = Auth::id();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->location = $validated['location'];
        $post->vote_count = 0;
        $post->status = 'pending'; // Use status field with pending value
        $post->is_winner = false;

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blog_images', $filename, 'public');
            $post->image = '/storage/blog_images/' . $filename;
        }

        $post->save();

        return redirect()->route('blog.show', $post->id)
            ->with('success', 'Your entry has been submitted and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = BlogPost::with(['user', 'comments.user', 'votes'])
            ->findOrFail($id);

        // Check if the post is published or if the current user is the author
        if ($post->status !== 'published' && (!Auth::check() || Auth::id() != $post->user_id)) {
            abort(403, 'This entry is pending approval.');
        }

        // Get related posts
        $relatedPosts = BlogPost::where('id', '!=', $post->id)
            ->where('status', 'published')
            ->orderBy('vote_count', 'desc')
            ->limit(3)
            ->get();

        return view('public.pages.blogDetail', compact('post', 'relatedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if the user is the author
        if (Auth::id() != $post->user_id) {
            abort(403, 'You do not have permission to edit this entry.');
        }

        return view('public.pages.competitionEdit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if the user is the author
        if (Auth::id() != $post->user_id) {
            abort(403, 'You do not have permission to edit this entry.');
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && Storage::disk('public')->exists(str_replace('/storage', '', $post->image))) {
                Storage::disk('public')->delete(str_replace('/storage', '', $post->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blog_images', $filename, 'public');
            $post->image = '/storage/blog_images/' . $filename;
        }

        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->location = $validated['location'];
        $post->status = 'pending'; // Reset to pending when edited
        $post->save();

        return redirect()->route('blog.show', $post->id)
            ->with('success', 'Your entry has been updated and is now pending approval.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = BlogPost::findOrFail($id);

        // Check if the user is the author
        if (Auth::id() != $post->user_id) {
            abort(403, 'You do not have permission to delete this entry.');
        }

        // Delete image if exists
        if ($post->image && Storage::exists(str_replace('/storage', 'public', $post->image))) {
            Storage::delete(str_replace('/storage', 'public', $post->image));
        }

        $post->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Your entry has been deleted.');
    }

    /**
     * Toggle vote for a blog post.
     */
    public function toggleVote(Request $request, string $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to vote.'
            ], 401);
        }

        try {
            $post = BlogPost::findOrFail($id);
            $userId = Auth::id();

            // Check if the user has already voted for this post
            $existingVote = BlogVote::where('blog_post_id', $id)
                ->where('user_id', $userId)
                ->first();

            if ($existingVote) {
                // User already voted, remove the vote
                $existingVote->delete();
                $post->decrementVoteCount();

                return response()->json([
                    'success' => true,
                    'voted' => false,
                    'voteCount' => $post->vote_count
                ]);
            } else {
                // User hasn't voted, add the vote
                $vote = new BlogVote();
                $vote->blog_post_id = $id;
                $vote->user_id = $userId;
                $vote->save();

                $post->incrementVoteCount();

                return response()->json([
                    'success' => true,
                    'voted' => true,
                    'voteCount' => $post->vote_count
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your vote: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if the user has voted for a post.
     */
    public function checkVote(string $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'voted' => false
            ]);
        }

        $voted = BlogVote::where('blog_post_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

        return response()->json([
            'voted' => $voted
        ]);
    }

    /**
     * Display the user's submitted blog posts.
     */
    public function userPosts()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your entries');
        }

        $posts = BlogPost::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.pages.userPosts', compact('posts'));
    }

    /**
     * Store a new comment for the blog post.
     */
    public function storeComment(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = BlogPost::findOrFail($id);

        $comment = new BlogComment();
        $comment->blog_post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->input('content');
        $comment->save();

        return redirect()->route('blog.show', $post->id)
            ->with('success', 'Your comment has been added successfully.');
    }
}
