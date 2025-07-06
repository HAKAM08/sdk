<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Fishing Tackle Shop Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        
        #wrapper {
            display: flex;
        }
        
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
            transition: all 0.3s;
        }
        
        #sidebar .sidebar-brand {
            padding: 1.5rem 1rem;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }
        
        #sidebar .nav-item {
            position: relative;
        }
        
        #sidebar .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        #sidebar .nav-item .nav-link:hover {
            color: #fff;
        }
        
        #sidebar .nav-item .nav-link i {
            margin-right: 0.5rem;
        }
        
        #sidebar .nav-item.active .nav-link {
            color: #fff;
            font-weight: 700;
        }
        
        #content {
            flex: 1;
            width: 100%;
        }
        
        .topbar {
            height: 4.375rem;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .dropdown-menu {
            position: absolute;
        }
        
        .card {
            margin-bottom: 24px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="sidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-fish"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin Panel</div>
            </a>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Heading -->
            <div class="sidebar-heading text-white-50 px-3 py-1">
                Shop Management
            </div>
            
            <!-- Nav Item - Products -->
            <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-fw fa-fish"></i>
                    <span>Products</span>
                </a>
            </li>
            
            <!-- Nav Item - Categories -->
            <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Categories</span>
                </a>
            </li>
            
            <!-- Nav Item - Orders -->
            <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            
            <!-- Nav Item - Content -->
            <li class="nav-item {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.content.index') }}">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Fishing Tips</span>
                </a>
            </li>
            
            <!-- Nav Item - Slideshows -->
            <li class="nav-item {{ request()->routeIs('admin.slideshows.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.slideshows.index') }}">
                    <i class="fas fa-fw fa-images"></i>
                    <span>Slideshows</span>
                </a>
            </li>
            
            <!-- Nav Item - Ad Spaces -->
            <li class="nav-item {{ request()->routeIs('admin.adspaces.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.adspaces.index') }}">
                    <i class="fas fa-fw fa-ad"></i>
                    <span>Ad Spaces</span>
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Heading -->
            <div class="sidebar-heading text-white-50 px-3 py-1">
                User Management
            </div>
            
            <!-- Nav Item - Users -->
            <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline mt-3">
                <button class="rounded-circle border-0" id="sidebarToggle">
                    <i class="fas fa-angle-left text-white"></i>
                </button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        
        <!-- Content Wrapper -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('home') }}">
                                <i class="fas fa-store fa-sm fa-fw mr-2 text-gray-400"></i>
                                Visit Store
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->
            
            <!-- Begin Page Content -->
            <main>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
            <!-- End of Page Content -->
            
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Fishing Tackle Shop {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom scripts -->
    <script>
        // Toggle the side navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    document.body.classList.toggle('sidebar-toggled');
                    sidebar.classList.toggle('toggled');
                    
                    if (sidebar.classList.contains('toggled')) {
                        sidebarToggle.querySelector('i').classList.remove('fa-angle-left');
                        sidebarToggle.querySelector('i').classList.add('fa-angle-right');
                    } else {
                        sidebarToggle.querySelector('i').classList.remove('fa-angle-right');
                        sidebarToggle.querySelector('i').classList.add('fa-angle-left');
                    }
                });
            }
            
            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', function(e) {
                    document.body.classList.toggle('sidebar-toggled');
                    sidebar.classList.toggle('toggled');
                });
            }
            
            // Close any open menu accordions when window is resized below 768px
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('toggled');
                }
            });
            
            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            const fixedNavigation = document.querySelector('body.fixed-nav .sidebar');
            if (fixedNavigation) {
                fixedNavigation.addEventListener('mousewheel DOMMouseScroll wheel', function(e) {
                    if (window.innerWidth > 768) {
                        const e0 = e.originalEvent;
                        const delta = e0.wheelDelta || -e0.detail;
                        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>