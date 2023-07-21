<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/index.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    {{-- poppin  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- custom js -->
    <script src="/js/custom.js"></script>
</head>

<body>
    <!-- language change input -->
    <div class="float-right mt-3" id="langBox">
            <select onchange="redirectToRoute(this.value)">
                <option value="">{{ __('messages.Language') }}</option>
                <option value="{{ route('set.language', 'en') }}">{{ __('messages.English') }}</option>
                <option value="{{ route('set.language', 'my') }}">{{ __('messages.Myanmar') }}</option>
            </select>
    </div>
    
    <div class="wrapper d-flex align-items-stretch">
        <!-- side bar -->
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars" style="color: #007BFF;" onmouseover="this.style.color='#6C757D';" onmouseout="this.style.color='#007BFF';"></i>
                    <span class="sr-only">Toggle Menu</span>

                </button>
            </div>
            <h1><a href="#" class="logo">{{ __('messages.Employee-Assign Management') }}</a></h1>
            <ul class="list-unstyled components mb-5">
                <li>
                    <a href="{{ route('employees') }}"><span class="fa fa-home mr-3"></span>{{ __('messages.Home') }}</a>
                </li>
                <li>
                    <a href="{{ route('employees') }}"><span class="fa fa-users mr-3"></span>{{ __('messages.Employees') }}</a>
                </li>
                <li>
                    <a href="{{ route('employees.create') }}"><span class="fa fa-user-plus mr-3"></span>{{ __('messages.Add New Employee') }}</a>
                </li>
                <li>
                    <a href="{{ route('project-assignments.create') }}"><span class="fa fa-sticky-note mr-3"></span>{{ __('messages.Project Assignment') }}</a>
                </li>
                <li>
                    <button type="submit" class="btn btn-link ml-3" data-toggle="modal" data-target="#logoutModal">
                        <span class="fa fa-sign-out mr-3" aria-hidden="true"></span>{{ __('messages.Logout') }}
                    </button>
                </li>
            </ul>
        </nav>
        <div id="content" class="pt-2 pl-3">
            <h3 class="mb-4 ml-5 mt-1 text-secondary" id="heading">@yield('heading')</h3>
            <span class="top-right-span text-secondary">{{ __('messages.Logged in ID') }} - {{ session('login_id') }}</span>
            <div class="container px-5">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- logout modal confirm box -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('messages.Logout') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('messages.Are you sure to logout your account?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
                    <!-- actual logout button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('messages.Logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    // toggle hamburger menu
    jQuery.noConflict();
    jQuery(document).ready(function ($) 
    {
        (function ($) {
            "use strict";

            var fullHeight = function () {
                jQuery(".js-fullheight").css("height", jQuery(window).height());
                jQuery(window).resize(function () {
                    jQuery(".js-fullheight").css("height", jQuery(window).height());
                });
            };
            fullHeight();

            jQuery("#sidebarCollapse").on("click", function () {
                jQuery("#sidebar").toggleClass("active");
            });
        })(jQuery);
    });

    // highlight sidebar menu while active
    document.addEventListener("DOMContentLoaded", function () 
    {
        var currentUrl = window.location.href;
        var navLinks = document.querySelectorAll(".list-unstyled.components li a");

        navLinks.forEach(function (link) {
            var href = link.getAttribute("href");
            if (currentUrl === href) {
                link.parentNode.classList.add("active");
            }
        });
    });
</script>

</html>