<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('adminBSB-master/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('adminBSB-master/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('adminBSB-master/css/style.css') }}" rel="stylesheet">

    <style>
        html, body {
            color: #eeeeee !important;
            background-color: #025287 !important;
        }

        /* a {
            color: #58bafc;
        } */
        
        .menu-modul:hover {
            cursor: hand;
            text-decoration: none;
            color: #58bafc;
        }

        hr.hr1 {
            border: 2px solid #a9a9a9;
            border-radius: 3px;
        }
    </style>

  </head>

  <body class="four-zero-four">
      <div class="four-zero-four-container">
            <div> 
                <h2>Welcome to centrin internal portal</h2> 
            </div>
            <div> 
                <h4>Select the modul or <a href="{{ route('logout') }}" class="col-green" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">click here to logout</a></h4>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <div class="container">
                <hr class="hr1">
              <!-- Widgets -->
              <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="https://laravel.com/docs" class="menu-modul" >
                        <div class="info-box bg-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                            <div class="content">
                                <div class="text-left"><h4>PURCHASING</h4></div>
                                <div class="text-left">Purchasing Area</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="https://laravel.com/docs" class="menu-modul" >
                        <div class="info-box bg-cyan hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">person</i>
                            </div>
                            <div class="content">
                                <div class="text-left"><h4>HRD</h4></div>
                                <div class="text-left">HRD Area</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="https://laravel.com/docs" class="menu-modul" >
                        <div class="info-box bg-light-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">build</i>
                            </div>
                            <div class="content">
                                <div class="text-left"><h4>TECHNICAL</h4></div>
                                <div class="text-left">Technical Area</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="https://laravel.com/docs" class="menu-modul" >
                        <div class="info-box bg-orange hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">trending_up</i>
                            </div>
                            <div class="content">
                                <div class="text-left"><h4>SALES</h4></div>
                                <div class="text-left">Sales Area</div>
                            </div>
                        </div>
                    </a>
                </div>
              </div>
              <!-- #END# Widgets -->
            </div>
          
      </div>

      <!-- Jquery Core Js -->
      <script src="{{ asset('adminBSB-master/plugins/jquery/jquery.min.js') }}"></script>

      <!-- Bootstrap Core Js -->
      <script src="{{ asset('adminBSB-master/plugins/bootstrap/js/bootstrap.js') }}"></script>

      <!-- Waves Effect Plugin Js -->
      <script src="{{ asset('adminBSB-master/plugins/node-waves/waves.js') }}"></script>
  </body>

</html>