@extends('admin.layouts.admin')

@section('title', 'Trashed Blog Posts')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Hidden Gem / Blog Posts /</span> Trash
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Trashed Blog Posts</h5>
                <a href="{{ route('blog-posts.index') }}" class="btn btn-primary">
                    <i class='bx bx-arrow-back me-1'></i> Back to Posts
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($trashedPosts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($post->title, 30) }}</td>
                                <td>{{ $post->user->name ?? 'Unknown' }}</td>
                                <td>
                                    @if ($post->status == 'published')
                                        <span class="badge bg-label-success">Published</span>
                                    @elseif ($post->status == 'draft')
                                        <span class="badge bg-label-warning">Draft</span>
                                    @else
                                        <span class="badge bg-label-info">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $post->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('admin.blog-posts.restore', $post->id) }}" method="POST"
                                            class="me-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class='bx bx-refresh me-1'></i> Restore
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    No trashed blog posts found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Add pagination links below the table -->
            <div class="d-flex justify-content-center mt-4">
                {{ $trashedPosts->links() }}
            </div>
        </div>
    </div>
@endsection
