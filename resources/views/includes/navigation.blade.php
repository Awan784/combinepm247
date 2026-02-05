
<nav class="px-4 navbar navbar-dark navbar-theme-primary col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="/">
        <img class="navbar-brand-dark" src="{{asset('assets/img/brand/light.svg')}}" alt="Volt logo" /> <img class="navbar-brand-light" src="{{asset('assets/img/brand/dark.svg')}}" alt="Volt logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<nav id="sidebarMenu" class="text-white bg-gray-800 sidebar d-lg-block collapse" data-simplebar>
    <div class="px-4 pt-3 sidebar-inner">
        <div class="pb-4 user-card d-flex d-md-none justify-content-between justify-content-md-center">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4"><img src="assets/img/team/profile-picture-3.jpg" class="border-white card-img-top rounded-circle" alt="Bonnie Green" /></div>
                <div class="d-block">
                    <h2 class="mb-3 h5">Hi, Jane</h2>
                    <a href="../examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </a>
            </div>
        </div>
        <ul class="pt-3 nav flex-column pt-md-0">
            <li class="nav-item">
                <a href="{{url('/')}}" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon"><img src="{{asset('assets/img/brand/light.svg')}}" height="20" width="20" alt="Volt Logo" /> </span><span class="mt-1 sidebar-text">PM247 </span>
                </a>
            </li>
            <li role="separator" class="mt-4 mb-4 border-gray-700 dropdown-divider"></li>
            @if(auth()->user()->hasReadWritePermission(1) || auth()->user()->hasReadWritePermission(2) || auth()->user()->hasReadWritePermission(3))
                <li class="nav-item">
                    <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-dashboard" aria-expanded="{{strpos(request()->path(),'dashboard/') !== false ? 'true' : 'false'}}">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Dashboard</span> 
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    </span>
                    <div class="multi-level collapse {{strpos(request()->path(),'dashboard/') !== false ? 'show' : ''}}" role="list" id="submenu-dashboard" aria-expanded="{{strpos(request()->path(),'dashboard/') !== false ? 'true' : 'false'}}">
                    <ul class="flex-column nav">
                        @if(auth()->user()->hasReadWritePermission(3))
                            <li class="nav-item text-center {{strpos(request()->path(),'dashboard/assign') !== false ? 'active' : ''}}">
                                <a href="{{ url('dashboard/assign') }}" class="nav-link px-4">
                                    <span class="sidebar-text-contracted">A</span> <span class="sidebar-text">Assign Dashboard</span>
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasReadWritePermission(1))
                            <li class="nav-item text-center {{strpos(request()->path(),'dashboard/engineer') !== false ? 'active' : ''}}">
                                <a href="{{ url('dashboard/engineer') }}" class="nav-link px-4">
                                    <span class="sidebar-text-contracted">E</span> <span class="sidebar-text">Engineer Dashboard</span>
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasReadWritePermission(2))
                            <li class="nav-item text-center {{strpos(request()->path(),'dashboard/contract') !== false ? 'active' : ''}}">
                                <a href="{{ url('dashboard/contract') }}" class="nav-link px-4">
                                    <span class="sidebar-text-contracted">C</span> <span class="sidebar-text">Contract Dashboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                    </div>
                </li>
                <li role="separator" class="mt-4 mb-4 border-gray-700 dropdown-divider"></li>
            @endif
            @if(auth()->user()->hasReadWritePermission(4))
                <li class="nav-item {{strpos(request()->path(),'jobs') !== false ? 'active' : ''}}">
                    <a href="{{url('jobs')}}"  class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Jobs</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
          
           @if(auth()->user()->hasReadWritePermission(4))
                <li class="nav-item {{strpos(request()->path(),'investigation') !== false ? 'active' : ''}}">
                    <a href="{{url('investigation/job')}}"  class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Jobs Investigation</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
          
            @if(auth()->user()->hasReadWritePermission(5))
                <li class="nav-item {{strpos(request()->path(),'contracts') !== false ? 'active' : ''}}">
                    <a href="{{url('contracts')}}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Contracts</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
            @if(auth()->user()->hasReadWritePermission(6))
                <li class="nav-item {{strpos(request()->path(),'payments') !== false ? 'active' : ''}}">
                    <a href="{{url('payments')}}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Payments</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
            @if(auth()->user()->hasReadWritePermission(7))
                <li class="nav-item {{strpos(request()->path(),'engineers') !== false ? 'active' : ''}}">
                    <a href="{{url('engineers')}}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Engineers</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif  
            @if(auth()->user()->hasReadWritePermission(8))
                <li class="nav-item {{strpos(request()->path(),'search') !== false ? 'active' : ''}}">
                    <a href="{{url('search')}}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Search Engineers</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
            @if(auth()->user()->hasReadWritePermission(9))
                <li class="nav-item {{strpos(request()->path(),'users') !== false ? 'active' : ''}}">
                    <a href="{{url('users')}}" class="nav-link {{strpos(request()->path(),'users') !== false ? 'active' : ''}}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Users</span>
                    </a>
                </li>
                <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @endif
            <li class="nav-item">
                <span class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-tv" aria-expanded="{{strpos(request()->path(),'tv/') !== false ? 'true' : 'false'}}">
                   <span>
                      <span class="sidebar-icon">
                         <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                         </svg>
                      </span>
                      <span class="sidebar-text">Tv</span> 
                   </span>
                   <span class="link-arrow">
                      <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                      </svg>
                   </span>
                </span>
                <div class="multi-level collapse {{strpos(request()->path(),'tv-view/') !== false ? 'show' : ''}}" role="list" id="submenu-tv" aria-expanded="{{strpos(request()->path(),'tv/') !== false ? 'true' : 'false'}}">
                   <ul class="flex-column nav">
                      <li class="nav-item text-center {{strpos(request()->path(),'tv/engineer') !== false ? 'active' : ''}}">
                        <a href="{{ url('tv-view/engineer') }}" class="nav-link px-4">
                            <span class="sidebar-text-contracted">E</span> <span class="sidebar-text">Engineer Tv</span>
                        </a>
                        </li>
                        <li class="nav-item text-center {{strpos(request()->path(),'tv/contract') !== false ? 'active' : ''}}">
                            <a href="{{ url('tv-view/contract') }}" class="nav-link px-4">
                                <span class="sidebar-text-contracted">C</span> <span class="sidebar-text">Contract Tv</span>
                            </a>
                        </li>
                        <li class="nav-item text-center {{strpos(request()->path(),'tv/assign') !== false ? 'active' : ''}}">
                            <a href="{{ url('tv-view/assign') }}" class="nav-link px-4">
                                <span class="sidebar-text-contracted">A</span> <span class="sidebar-text">Assign Tv</span>
                            </a>
                        </li>
                   </ul>
                </div>
            </li>
            <li role="separator" class="mt-4 mb-3 border-gray-700 dropdown-divider"></li>
            @if (auth()->user()->anyGmailLogin() === 0 || auth()->user()->gmail_login === 1)
                <li class="nav-item {{strpos(request()->path(),'mails-check') !== false ? 'active' : ''}}">
                    <a href="{{url('mails-check')}}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 -2.5 20 20" version="1.1">
        
                                <title>email [#1572]</title>
                                <desc>Created with Sketch.</desc>
                                <defs>
                            
                            </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -922.000000)" fill="#9ca3af">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M294,774.474 L284,765.649 L284,777 L304,777 L304,765.649 L294,774.474 Z M294.001,771.812 L284,762.981 L284,762 L304,762 L304,762.981 L294.001,771.812 Z" id="email-[#1572]">
                            
                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="sidebar-text">Mails Check</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
