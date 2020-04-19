<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>{{ config('app.name', 'GestockDSI') }}</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesdesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="{{asset('assets/images/faviicon.png')}}">

        <!--Morris Chart CSS -->
        <!-- <link rel="stylesheet" href="{{asset('assets/plugins/morris/morris.css')}}"> -->

        <!-- App css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href= "{{asset('assets/css/icons.css')}}"rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('assets/css/bootstrap4-toggle.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Plugins css -->
        <link href="{{asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />

        <!-- App css -->

        @yield('css')

    </head>


    <body>

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Navigation Bar-->
        <header id="topnav">
            @include('layouts.navigation_bar')
        </header>
        <!-- End Navigation Bar-->


        <div class="wrapper">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                          <div class="btn-group pull-right">
                              <div class="button-items">
                                @yield('breadcrumb')
                              </div>
                          </div>

                          <div class="button-items">
                            <!-- <h5 class="page-title">@yield('page')</h5> -->
                            @yield('page')
                            @yield('buttons')
                          </div>
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->

                @yield('content')
                <!-- end row -->

            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->


        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        © 2020 GT - DSI <i class="mdi mdi-heart text-danger"></i>.
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->


        <!-- jQuery  -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/js/popper.min.js')}}"></script>
        <script src= "{{asset('assets/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/js/modernizr.min.js')}}"></script>
        <script src= "{{asset('assets/js/waves.js')}}"></script>
        <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('assets/js/jquery.scrollTo.min.js')}}"></script>

        <!--Morris Chart-->
        <!-- <script src= "{{asset('assets/plugins/morris/morris.min.js')}}"></script> -->
        <!-- <script src= "{{asset('assets/plugins/raphael/raphael-min.js')}}"></script> -->

        <!-- <script src="{{asset('assets/pages/dashborad.js')}}"></script> -->

        <!-- App js -->
        <script src="{{asset('assets/js/app.js')}}"></script>

        <!-- script js -->
        <script src="{{asset('assets/js/script.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap4-toggle.min.js')}}"></script>



        <!-- jQuery  -->

        <!-- Plugins js -->
        <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>

        <!-- Plugins Init js -->
        <script src="{{asset('assets/pages/form-advanced.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/app.js')}}"></script>

        @yield('js')

    </body>
</html>
