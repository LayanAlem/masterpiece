@extends('public.layouts.main')
@section('title', 'Competition Upload')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/competitionUpload.css') }}">
@endpush

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22L3 12L12 2L21 12L12 22Z" fill="#92400b"/>
            </svg>
            Hidden Jordan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="discover.html">Discover</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="competition.html">Competition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About</a>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a class="share-btn" href="upload.html">Share Your Place</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h1 class="page-title">Submit Your Hidden Jordan Entry</h1>

        <div class="row">
            <!-- Upload Form Section -->
            <div class="col-lg-8">
                <div class="upload-section">
                    <h2 class="upload-title">Tell Us About Your Discovery</h2>

                    <form id="upload-form">
                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Title of Your Discovery</label>
                            <input type="text" class="form-control" id="title" placeholder="E.g., 'Ancient Roman Ruins near Ajloun'" required>
                            <div class="form-text">Give your hidden place a catchy and descriptive title</div>
                        </div>

                        <!-- Photo Upload Field -->
                        <div class="mb-4">
                            <label class="form-label">Photos</label>
                            <div class="upload-dropzone" id="upload-dropzone">
                                <input type="file" id="file-input" multiple accept="image/*" style="display: none;">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <p class="upload-text">Drag and drop your photos here</p>
                                <p class="upload-hint">or click to browse from your device</p>
                                <p class="upload-hint">(Maximum 5 photos, JPG or PNG, max 10MB each)</p>
                            </div>
                            <div class="thumbnail-preview" id="thumbnail-preview"></div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="6" placeholder="Describe this hidden place - what makes it special? What can visitors expect?" required></textarea>
                            <div class="form-text">Provide a detailed description of the place, including its history, unique features, and why others should visit</div>
                        </div>

                        <!-- Location Field -->
                        <div class="mb-4">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" placeholder="E.g., '5km east of Petra, near Wadi Musa'" required>
                            <div class="form-text">Provide directions or a description of how to find this place</div>
                        </div>

                        <!-- Category Field -->
                        <div class="mb-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" required>
                                <option value="" selected disabled>Select a category</option>
                                <option value="natural">Natural Wonder</option>
                                <option value="historical">Historical Site</option>
                                <option value="cultural">Cultural Experience</option>
                                <option value="village">Hidden Village</option>
                                <option value="viewpoint">Scenic Viewpoint</option>
                                <option value="culinary">Culinary Secret</option>
                                <option value="artisan">Local Artisan/Craft</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="form-text">Choose the category that best describes your discovery</div>
                        </div>

                        <!-- Map Location Field -->
                        <div class="mb-4">
                            <label class="form-label">Pin on Map (Optional)</label>
                            <div class="location-map">
                                <p>Map Interface Will Be Displayed Here</p>
                            </div>
                            <div class="form-text">Click on the map to mark the exact location of this hidden place</div>
                        </div>

                        <!-- GPS Coordinates Field -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude (Optional)</label>
                                <input type="text" class="form-control" id="latitude" placeholder="E.g., 31.9454">
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude (Optional)</label>
                                <input type="text" class="form-control" id="longitude" placeholder="E.g., 35.9336">
                            </div>
                            <div class="col-12">
                                <div class="form-text">If you know the exact coordinates, you can enter them here</div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex button-group justify-content-end">
                            <a href="competition.html" class="cancel-btn">Cancel</a>
                            <button type="submit" class="submit-btn">Submit Entry</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <h3 class="sidebar-title">Submission Guidelines</h3>
                    <ul class="guidelines-list">
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Original Discoveries</strong>
                                <p class="guideline-text">Submit places that are not commonly found in typical tourist guides or popular travel blogs.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>High-Quality Photos</strong>
                                <p class="guideline-text">Include clear, well-lit photos that showcase the beauty and uniqueness of the place.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Detailed Description</strong>
                                <p class="guideline-text">Provide comprehensive information about the place, including its significance and what makes it special.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Accurate Location</strong>
                                <p class="guideline-text">Give precise directions or GPS coordinates to help others find the place.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <div>
                                <strong>Respect Cultural Sensitivities</strong>
                                <p class="guideline-text">Ensure your submission respects local customs, traditions, and environmental concerns.</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <div>
                                <strong>Safety Considerations</strong>
                                <p class="guideline-text">If the place requires special precautions or equipment to visit safely, please mention this.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <h4 class="sidebar-title">Competition Prizes</h4>
                        <ul class="guidelines-list">
                            <li>
                                <i class="fas fa-trophy" style="color: gold;"></i>
                                <div>
                                    <strong>First Place</strong>
                                    <p class="guideline-text">3-night stay at a luxury hotel in Petra plus guided tour package</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-medal" style="color: silver;"></i>
                                <div>
                                    <strong>Second Place</strong>
                                    <p class="guideline-text">2-night stay at a luxury hotel in Dead Sea</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-award" style="color: #cd7f32;"></i>
                                <div>
                                    <strong>Third Place</strong>
                                    <p class="guideline-text">Dinner for two at a top restaurant in Amman</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-star" style="color: var(--accent);"></i>
                                <div>
                                    <strong>People's Choice</strong>
                                    <p class="guideline-text">Gift vouchers for local artisan products worth 200 JOD</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="py-4 bg-dark text-white mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5>Hidden Jordan</h5>
                <p class="small text-muted">Discover the untold stories and secret places of Jordan's rich landscape and culture.</p>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.html" class="text-decoration-none text-muted">Home</a></li>
                    <li><a href="discover.html" class="text-decoration-none text-muted">Discover</a></li>
                    <li><a href="competition.html" class="text-decoration-none text-muted">Competition</a></li>
                    <li><a href="about.html" class="text-decoration-none text-muted">About</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Connect With Us</h5>
                <div class="d-flex gap-3 fs-5">
                    <a href="#" class="text-muted"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-3 bg-secondary">
        <div class="row align-items-center">
            <div class="col-md-6 small text-muted">
                <p class="mb-0">&copy; 2025 Hidden Jordan. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end small">
                <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-muted">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('upload-dropzone');
        const fileInput = document.getElementById('file-input');
        const thumbnailPreview = document.getElementById('thumbnail-preview');
        const uploadForm = document.getElementById('upload-form');

        // Handle click on dropzone to trigger file input
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        // Handle file selection
        fileInput.addEventListener('change', handleFiles);

        // Handle drag and drop
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function() {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');

            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFiles();
            }
        });

        // Handle form submission
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Here you would typically collect all form data and submit it
            alert('Your entry has been submitted successfully! Thank you for participating in the Hidden Jordan competition.');

            // Redirect back to competition page after submission
            window.location.href = 'competition.html';
        });

        // Function to handle the selected files
        function handleFiles() {
            const files = fileInput.files;

            // Clear the preview
            thumbnailPreview.innerHTML = '';

            // Maximum of 5 files
            const maxFiles = 5;
            const filesToProcess = Array.from(files).slice(0, maxFiles);

            if (files.length > maxFiles) {
                alert(`You can only upload up to ${maxFiles} photos. Only the first ${maxFiles} will be processed.`);
            }

            filesToProcess.forEach(file => {
                if (!file.type.match('image.*')) {
                    alert('Please upload only image files (JPG, PNG)');
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert('Please upload images smaller than 10MB');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const thumbnailItem = document.createElement('div');
                    thumbnailItem.className = 'thumbnail-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const removeButton = document.createElement('div');
                    removeButton.className = 'thumbnail-remove';
                    removeButton.innerHTML = '<i class="fas fa-times"></i>';
                    removeButton.addEventListener('click', function() {
                        thumbnailItem.remove();
                    });

                    thumbnailItem.appendChild(img);
                    thumbnailItem.appendChild(removeButton);
                    thumbnailPreview.appendChild(thumbnailItem);
                };

                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endpush
