<div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">
                        <!-- Text Logo -->
                        <!--<a href="index.html" class="logo">-->
                        <!--Upcube-->
                        <!--</a>-->
                        <!-- Image Logo -->
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22" class="logo-small">
                            <img src="{{ asset('assets/images/logo_gabon.jpg') }}" alt="" height="50" class="logo-large"> {{ config('app.name', 'GestockDSI') }}
                        </a>

                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras topbar-custom">

                        <!-- Search input -->
                        <div class="search-wrap" id="search-wrap">
                            <div class="search-bar">
                                <input class="search-input" type="search" placeholder="Search" />
                                <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </div>
                        </div>

                        <ul class="list-inline float-right mb-0">
                            <!-- Search -->


                            <!-- Messages-->


                            <!-- notification-->

                            <!-- User-->
                            <!-- Authentication Links -->
                            @guest
                              <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                  <img src="{{asset('uploads/users/images/default-user-image.png')}}" alt="user" class="rounded-circle">
                                </a>

                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                  <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>

                                  @if (Route::has('register'))
                                    <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                                  @endif
                                </div>
                              </li>
                            @else
                              <li class="list-inline-item dropdown notification-list">

                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                  <div class="user-wrapper">
                                    <div class="img-user">
                                      <img src="{{asset('uploads/users/images/default-user-image.png')}}" alt="user" class="rounded-circle">
                                    </div>
                                    <div class="text-user">
                                      <p>{{ Auth::user()->name }}</p>
                                    </div>
                                  </div>
                                </a>

                                <!-- <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                  <img src="{{asset('uploads/users/images/default-user-image.png')}}" alt="user" class="rounded-circle">
                                </a> -->

                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                  <!-- <a class="dropdown-item" href="#"><i class="text-muted"></i> {{ Auth::user()->name }}</a> -->

                                  <a class="dropdown-item" href="#"><i class="dripicons-user text-muted"></i> Profile</a>
                                  <div class="dropdown-divider"></div>

                                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                  </form>
                                </div>
                              </li>
                            @endguest

                            <li class="menu-item list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <!-- MENU Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        @include('layouts.navigation_menu')
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
