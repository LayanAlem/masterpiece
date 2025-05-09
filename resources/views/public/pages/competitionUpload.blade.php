@extends('public.layouts.main')
@section('title', 'Submit Hidden Gem')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
    <style>
        .upload-form {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
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
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(146, 64, 11, 0.25);
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
            display: none;
            margin-bottom: 15px;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .submit-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 4px 10px rgba(146, 64, 11, 0.2);
        }

        textarea.form-control {
            min-height: 250px;
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
                        <h1 class="form-title">Share Your Hidden Gem</h1>
                        <p class="form-subtitle">Participate in our Hidden Places competition by submitting your own
                            discovery. Share a hidden gem in Jordan that deserves recognition.</p>

                        <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="title" class="form-label">Title of your Hidden Gem</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Choose a compelling title that captures the essence of your hidden
                                    place.</div>
                            </div>

                            <div class="mb-4">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location') }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Provide a specific location or area in Jordan. GPS coordinates are
                                    optional.</div>
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label">Description</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                                    required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Describe what makes this place special. Include relevant details like:
                                    <ul class="mt-2">
                                        <li>Why is this place special or unique?</li>
                                        <li>What activities can visitors enjoy here?</li>
                                        <li>Best times to visit</li>
                                        <li>How to get there</li>
                                        <li>Any tips for visitors</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload a high-quality image (JPEG, PNG) of the place. Max size: 2MB.
                                    Original photos only.</div>

                                <div class="image-preview mt-3" id="imagePreview">
                                    <img src="" alt="Image Preview" id="previewImg">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="submit-btn">Submit Entry</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <h3 class="sidebar-title">Competition Rules</h3>
                        <ul class="rules-list">
                            <li>
                                <i class="far fa-calendar-alt"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Submission Deadline</div>
                                    <div class="rule-text">December 15, 2025</div>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-trophy"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Prizes</div>
                                    <div class="prizes-list">
                                        <div class="prize-item">
                                            <span>1st Place:</span>
                                            <span>$2000</span>
                                        </div>
                                        <div class="prize-item">
                                            <span>2nd Place:</span>
                                            <span>$1000</span>
                                        </div>
                                        <div class="prize-item">
                                            <span>3rd Place:</span>
                                            <span>$500</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-clipboard-list"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Requirements</div>
                                    <ul class="requirement-list">
                                        <li>
                                            <i class="fas fa-check"></i> Location must be in Jordan
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> Original photos only
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> Detailed description required
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> GPS coordinates optional
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-info-circle"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Review Process</div>
                                    <div class="rule-text">All submissions are reviewed by our team for approval before
                                        being published. The review process typically takes 1-2 business days.</div>
                                </div>
                            </li>
                        </ul>
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
