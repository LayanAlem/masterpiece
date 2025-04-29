@extends('public.layouts.main')
@section('title', 'Hidden Gem')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
@endpush

@section('content')
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 22L12 2L21 22H3Z" fill="#92400b"/>
            </svg>
            Hidden Jordan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Competition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Winners</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <a href="#" class="share-btn">Share Your Place</a>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <div class="row">
            <!-- Main content area -->
            <div class="col-lg-8">
                <h1 class="page-title">Hidden Places Competition</h1>

                <!-- Filter options -->
                <div class="filter-section">
                    <div class="sort-dropdown">
                        Most Recent <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="view-toggle btn-group">
                        <button type="button" class="btn active">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button type="button" class="btn">
                            <i class="fas fa-th-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Entry cards -->
                <div class="entry-card">
                    <div class="entry-image">
                        <img src="/api/placeholder/800/600" alt="Hidden Cave in Little Petra">
                    </div>
                    <div class="entry-content">
                        <div class="entry-header">
                            <div>
                                <h2 class="entry-title">Hidden Cave in Little Petra</h2>
                                <div class="entry-author">
                                    <div class="author-avatar">
                                        <img src="/api/placeholder/100/100" alt="Ahmad Hassan">
                                    </div>
                                    <div>
                                        <div class="author-name">Ahmad Hassan</div>
                                        <div class="entry-date">Posted 2 days ago</div>
                                    </div>
                                </div>
                            </div>
                            <div class="upvote">
                                <i class="fas fa-arrow-up"></i>
                                <span class="upvote-count">234</span>
                            </div>
                        </div>
                        <p class="entry-description">
                            Discovered this amazing hidden cave just 2km from Little Petra. The natural light creates incredible patterns...
                        </p>
                        <div class="entry-footer">
                            <div class="d-flex gap-4">
                                <div class="comments">
                                    <i class="far fa-comment"></i> 24 comments
                                </div>
                                <div class="views">
                                    <i class="far fa-eye"></i> 1.2k views
                                </div>
                            </div>
                            <a href="#" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>

                <div class="entry-card">
                    <div class="entry-image">
                        <img src="/api/placeholder/800/600" alt="Secret Oasis in Wadi Rum">
                    </div>
                    <div class="entry-content">
                        <div class="entry-header">
                            <div>
                                <h2 class="entry-title">Secret Oasis in Wadi Rum</h2>
                                <div class="entry-author">
                                    <div class="author-avatar">
                                        <img src="/api/placeholder/100/100" alt="Sara Ameer">
                                    </div>
                                    <div>
                                        <div class="author-name">Sara Ameer</div>
                                        <div class="entry-date">Posted 3 days ago</div>
                                    </div>
                                </div>
                            </div>
                            <div class="upvote">
                                <i class="fas fa-arrow-up"></i>
                                <span class="upvote-count">186</span>
                            </div>
                        </div>
                        <p class="entry-description">
                            Found this incredible hidden oasis during my desert expedition. A true miracle in the heart of Wadi Rum...
                        </p>
                        <div class="entry-footer">
                            <div class="d-flex gap-4">
                                <div class="comments">
                                    <i class="far fa-comment"></i> 18 comments
                                </div>
                                <div class="views">
                                    <i class="far fa-eye"></i> 956 views
                                </div>
                            </div>
                            <a href="#" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>

                <!-- Add Entry Button -->
                <div class="add-entry-container">
                    <a href="upload.html" class="add-entry-btn">
                        <i class="fas fa-plus-circle"></i> Participate in Competition
                    </a>
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
                    </ul>
                </div>

                <!-- Top Contributors Section -->
                <div class="sidebar contributors-section">
                    <h3 class="sidebar-title">Top Contributors</h3>
                    <div class="contributor-item">
                        <div class="contributor-avatar">
                            <img src="/api/placeholder/100/100" alt="Ahmad Hassan">
                        </div>
                        <div class="contributor-info">
                            <div class="contributor-name">Ahmad Hassan</div>
                            <div class="contributor-stats">12 places shared</div>
                        </div>
                        <div class="contributor-votes">2.1k votes</div>
                    </div>
                    <div class="contributor-item">
                        <div class="contributor-avatar">
                            <img src="/api/placeholder/100/100" alt="Sara Ameer">
                        </div>
                        <div class="contributor-info">
                            <div class="contributor-name">Sara Ameer</div>
                            <div class="contributor-stats">8 places shared</div>
                        </div>
                        <div class="contributor-votes">1.8k votes</div>
                    </div>
                    <div class="contributor-item">
                        <div class="contributor-avatar">
                            <img src="/api/placeholder/100/100" alt="Omar Khalil">
                        </div>
                        <div class="contributor-info">
                            <div class="contributor-name">Omar Khalil</div>
                            <div class="contributor-stats">6 places shared</div>
                        </div>
                        <div class="contributor-votes">1.5k votes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
