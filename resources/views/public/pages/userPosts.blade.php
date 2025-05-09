@extends('public.layouts.main')
@section('title', 'My Hidden Gem Entries')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
    <style>
        .my-entries-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--dark);
            position: relative;
            padding-bottom: 10px;
        }

        .my-entries-title:after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #793509;
            border-radius: 3px;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .status-pending:before {
            content: "⏳";
            margin-right: 5px;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-approved:before {
            content: "✓";
            margin-right: 5px;
            font-weight: bold;
        }

        .status-winner {
            background-color: #ffedf0;
            color: #793509;
            border: 1px solid #f8d7da;
        }

        .status-winner:before {
            content: "🏆";
            margin-right: 5px;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-rejected:before {
            content: "✕";
            margin-right: 5px;
        }

        .entry-card {
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .entry-card:hover {
            transform: translateY(-5px);
        }

        .entry-card .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .entry-card:hover .card {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: rgba(121, 53, 9, 0.1);
        }

        /* Simple fix for the image container */
        .entry-card .row {
            margin: 0;
        }

        .entry-image-container {
            height: 180px;
            /* Fixed height */
            overflow: hidden;
        }

        .entry-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .entry-image-container {
                height: 200px;
            }
        }

        .entry-card .card-body {
            padding: 20px;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            /* Prevent content overflow */
        }

        .card-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            /* Prevent content overflow */
        }

        .card-title {
            margin-top: 0;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .location-text,
        .submission-date,
        .vote-count {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: nowrap;
        }

        .btn-view {
            background-color: transparent;
            color: #793509;
            border: 1px solid #793509;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-view i {
            margin-right: 5px;
        }

        .btn-view:hover {
            background-color: #793509;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        .btn-edit {
            background-color: transparent;
            color: #6c757d;
            border: 1px solid #6c757d;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-edit i {
            margin-right: 5px;
        }

        .btn-edit:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
        }

        .btn-delete {
            background-color: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-delete i {
            margin-right: 5px;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        /* Pagination styling */
        .pagination {
            margin-top: 30px;
        }

        .page-item.active .page-link {
            background-color: #793509;
            border-color: #793509;
        }

        .page-link {
            color: #793509;
        }

        .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(121, 53, 9, 0.25);
        }

        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 60px 0;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .empty-state:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #793509;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state h4 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state .btn-primary {
            background-color: #793509;
            border-color: #793509;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .empty-state .btn-primary:hover {
            background-color: #5e2907;
            border-color: #5e2907;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        .btn-back {
            background-color: transparent;
            color: #6c757d;
            border: 1px solid #6c757d;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-back i {
            margin-right: 8px;
        }

        .btn-back:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
        }

        /* Success modal styling */
        .success-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .success-modal .modal-header {
            background-color: #793509;
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }

        .success-modal .modal-title {
            font-weight: 600;
            color: white;
        }

        .success-modal .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .success-modal .modal-footer {
            border-top: none;
            justify-content: center;
            padding: 1.5rem;
        }

        .success-modal .btn-close {
            color: white;
            filter: invert(1) brightness(200%);
        }

        .success-icon {
            font-size: 3rem;
            color: #793509;
            margin-bottom: 1rem;
        }

        .success-message {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
        }

        /* Confirm delete modal styling */
        .confirm-modal .modal-header {
            background-color: #f8d7da;
            color: #721c24;
            border-bottom: none;
        }

        .confirm-modal .modal-title {
            color: #721c24;
            font-weight: 600;
        }

        .confirm-modal .modal-body {
            padding: 1.5rem;
            text-align: center;
        }

        .warning-icon {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .warning-message {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-confirm-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            transition: all 0.3s;
        }

        .btn-confirm-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        .alert-success {
            border-left: 4px solid #28a745;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .entry-card .row {
                flex-direction: column;
                height: auto;
            }

            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                height: 200px;
            }

            .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
                height: auto;
            }

            .entry-card .card-body {
                height: auto;
                min-height: 220px;
            }

            .card-actions {
                flex-wrap: wrap;
                justify-content: space-between;
                margin-top: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="page-title my-entries-title">My Hidden Gem Entries</h1>

                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="entry-card">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="entry-image-container">
                                                @if ($post->image)
                                                    <img src="{{ $post->image }}" alt="{{ $post->title }}"
                                                        class="entry-image">
                                                @else
                                                    <img src="/api/placeholder/800/600" alt="{{ $post->title }}"
                                                        class="entry-image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <div class="card-info">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h5 class="card-title">{{ $post->title }}</h5>
                                                        <span
                                                            class="status-badge {{ $post->is_winner ? 'status-winner' : ($post->status == 'published' ? 'status-approved' : ($post->status == 'rejected' ? 'status-rejected' : 'status-pending')) }}">
                                                            {{ $post->is_winner ? 'Winner' : ($post->status == 'published' ? 'Published' : ($post->status == 'rejected' ? 'Rejected' : 'Pending Approval')) }}
                                                        </span>
                                                    </div>
                                                    <p class="location-text">
                                                        <i class="fas fa-map-marker-alt"></i> {{ $post->location }}
                                                    </p>
                                                    <p class="submission-date">
                                                        <i class="far fa-calendar-alt me-1"></i> Submitted on
                                                        {{ $post->created_at->format('M d, Y') }}
                                                    </p>
                                                    <p class="vote-count">
                                                        <i class="fas fa-arrow-up"></i> {{ $post->vote_count }} votes
                                                    </p>
                                                </div>

                                                <div class="card-actions">
                                                    <a href="{{ route('blog.show', $post->id) }}" class="btn btn-view">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-delete" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $post->id }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete confirmation modal -->
                            <div class="modal fade confirm-modal" id="deleteModal{{ $post->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $post->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $post->id }}">Confirm
                                                Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <i class="fas fa-exclamation-triangle warning-icon"></i>
                                            <p class="warning-message">Are you sure you want to delete
                                                "{{ $post->title }}"?</p>
                                            <p class="text-muted">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-cancel"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('blog.destroy', $post->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-confirm-delete">Delete Entry</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-map-marked-alt"></i>
                            <h4>No Hidden Gems Yet</h4>
                            <p>You haven't submitted any hidden gems to the competition yet.</p>
                            <a href="{{ route('blog.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i> Share Your First Hidden Gem
                            </a>
                        </div>
                    @endif

                    <div class="mt-4 mb-5">
                        <a href="{{ route('blog.index') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back to All Hidden Gems
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal to be shown when redirected with success message -->
    @if (session('success'))
        <div class="modal fade success-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <i class="fas fa-check-circle success-icon"></i>
                        <p class="success-message">{{ session('success') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script to auto-show the success modal -->
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                });
            </script>
        @endpush
    @endif
@endsection
