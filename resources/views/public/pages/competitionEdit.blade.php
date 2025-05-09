@extends('public.layouts.main')
@section('title', 'Edit Hidden Gem')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
    <style>
        .upload-form {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .upload-form:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: rgba(121, 53, 9, 0.1);
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--dark);
        }

        .form-subtitle {
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #793509;
            box-shadow: 0 0 0 0.2rem rgba(121, 53, 9, 0.15);
        }

        .form-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .image-preview {
            width: 100%;
            height: 250px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .image-preview:hover {
            border-color: #793509;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .submit-btn {
            background-color: #793509;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background-color: #5e2907;
            color: white;
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
            transform: translateY(-2px);
        }

        textarea.form-control {
            min-height: 250px;
            line-height: 1.6;
        }

        .current-image-container {
            margin-bottom: 20px;
        }

        .current-image-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
        }

        .alert-warning {
            border-left: 4px solid #ffc107;
            background-color: #fff8e1;
            padding: 15px;
            border-radius: 6px;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.15);
        }

        .rules-list li {
            transition: all 0.3s ease;
        }

        .rules-list li:hover {
            transform: translateX(5px);
        }

        .rules-list li i {
            color: #793509;
            transition: all 0.3s ease;
        }

        .rules-list li:hover i {
            transform: scale(1.1);
        }

        .btn-outline-primary {
            border-color: #793509 !important;
            color: #793509 !important;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #793509 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Main content area -->
                <div class="col-lg-8">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="upload-form">
                        <h1 class="form-title">Edit Your Hidden Gem</h1>
                        <p class="form-subtitle">Update the details of your hidden gem submission. Note that after editing,
                            your entry will be sent for review again.</p>

                        <form action="{{ route('blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="title" class="form-label">Title of your Hidden Gem</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $post->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $post->location) }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label">Description</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                                    required>{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="current-image-container">
                                    <label class="current-image-label">Current Image:</label>
                                    <div class="image-preview">
                                        @if ($post->image)
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}">
                                        @else
                                            <img src="/api/placeholder/800/600" alt="{{ $post->title }}">
                                        @endif
                                    </div>
                                </div>

                                <label for="image" class="form-label">Upload New Image (optional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to keep the current image.</div>

                                <div class="image-preview mt-3" id="imagePreview" style="display: none;">
                                    <img src="" alt="New Image Preview" id="previewImg">
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Your entry will need to be re-approved
                                after editing.
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('blog.show', $post->id) }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="submit-btn">Update Entry</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <h3 class="sidebar-title">Editing Guidelines</h3>
                        <ul class="rules-list">
                            <li>
                                <i class="fas fa-info-circle"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Approval Process</div>
                                    <div class="rule-text">After editing, your entry will be reviewed again before becoming
                                        visible to other users.</div>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-clipboard-list"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Maintain Quality</div>
                                    <div class="rule-text">Ensure your edited submission still meets all the competition
                                        requirements.</div>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-image"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Image Requirements</div>
                                    <div class="rule-text">Original photos only. Maximum file size: 2MB. Supported formats:
                                        JPEG, PNG, GIF.</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="sidebar mt-4">
                        <h3 class="sidebar-title">Need Help?</h3>
                        <p>If you're having trouble editing your entry or have questions about the competition, feel free to
                            <a href="{{ route('contact') }}">contact us</a>.
                        </p>

                        <div class="mt-4">
                            <a href="{{ route('blog.user-posts') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-2"></i> Back to My Entries
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Image preview functionality
                const imageInput = document.getElementById('image');
                const imagePreview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');

                imageInput.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.addEventListener('load', function() {
                            previewImg.src = this.result;
                            imagePreview.style.display = 'block';
                        });

                        reader.readAsDataURL(file);
                    } else {
                        previewImg.src = '';
                        imagePreview.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
