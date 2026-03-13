<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> Mundo Vegano BO </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/font-awesome.min.css') }}">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/smartadmin-production-plugins.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/smartadmin-production.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/smartadmin-skins.min.css') }}">

		<!-- SmartAdmin RTL Support  -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/smartadmin-rtl.min.css') }}">

		<!-- Admin Styles  -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/admin.css') }}">

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<!--<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/demo.min.css') }}">-->

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/storage/img/favicon/faviconadmin.ico" type="image/x-icon">
		<link rel="icon" href="/storage/img/favicon/faviconadmin.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
		<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="storage/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="storage/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="storage/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="storage/img/splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="storage/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="storage/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="storage/img/splash/iphone.png" media="screen and (max-device-width: 320px)">
		
		
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/fm.tagator.jquery.min.css') }}">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.23.0/ui/trumbowyg.min.css">

	</head>
	
	<!--

	TABLE OF CONTENTS.
	
	Use search to find needed section.
	
	===================================================================
	
	|  01. #CSS Links                |  all CSS links and file paths  |
	|  02. #FAVICONS                 |  Favicon links and file paths  |
	|  03. #GOOGLE FONT              |  Google font link              |
	|  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
	|  05. #BODY                     |  body tag                      |
	|  06. #HEADER                   |  header tag                    |
	|  07. #PROJECTS                 |  project lists                 |
	|  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
	|  09. #MOBILE                   |  mobile view dropdown          |
	|  10. #SEARCH                   |  search field                  |
	|  11. #NAVIGATION               |  left panel & navigation       |
	|  12. #RIGHT PANEL              |  right panel userlist          |
	|  13. #MAIN PANEL               |  main panel                    |
	|  14. #MAIN CONTENT             |  content holder                |
	|  15. #PAGE FOOTER              |  page footer                   |
	|  16. #SHORTCUT AREA            |  dropdown shortcuts area       |
	|  17. #PLUGINS                  |  all scripts and plugins       |
	
	===================================================================
	
	-->
	
	<!-- #BODY -->
	<!-- Possible Classes

		* 'smart-style-{SKIN#}'
		* 'smart-rtl'         - Switch theme mode to RTL
		* 'menu-on-top'       - Switch to top navigation (no DOM change required)
		* 'no-menu'			  - Hides the menu completely
		* 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
		* 'fixed-header'      - Fixes the header
		* 'fixed-navigation'  - Fixes the main menu
		* 'fixed-ribbon'      - Fixes breadcrumb
		* 'fixed-page-footer' - Fixes footer
		* 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
	-->
	<body class="fixed-header fixed-navigation fixed-ribbon">
		@isset($currentUser)
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- LOGO -->
				<span id="logo"> 
					<!--<img src="storage/img/logo.png" alt="SmartAdmin"> -->
					MV - Backoffice
				</span>
				<!-- LOGO -->

			</div>


			<!-- pulled right: nav area -->
			<div class="pull-right">
				
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				<!-- Top menu profile link : this shows only when top menu is active -->
				<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
					<li class="">
						<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
							<!-- <img src="storage/img/avatars/sunny.png" alt="John Doe" class="online" />  -->
						</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="{{ route('logout') }}" class="padding-10 padding-top-5 padding-bottom-5"><i class="fa fa-sign-out fa-lg"></i> <strong>Sair</strong></a>
							</li>
						</ul>
					</li>
				</ul>

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="{{ route('logout') }}" title="Sair" ><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<!-- end fullscreen button -->
				

			</div>
			<!-- end pulled right: nav area -->

		</header>
		<!-- END HEADER -->
		@endisset
		@isset($currentUser)
		<!-- Left panel : Navigation area -->
		@include('admin.inc.nav')
		@endisset
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" class="{{ isset($currentUser) ? 'logged' : 'logout'}}" role="main">
			@isset($currentUser)
			<!-- RIBBON -->
			<div id="ribbon">
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					@foreach($data["breadcrumbs"] as $breadcrumb)
						<li>{{ $breadcrumb }}</li>
					@endforeach
				</ol>
				<!-- end breadcrumb -->

			</div>
            <!-- END RIBBON -->
			@endisset
            
            <!-- MAIN CONTENT -->
            <div id="content">
				@include('admin.inc.messages')
                @yield('content')
            </div>
            <!-- END MAIN CONTENT -->
            
        </div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white">Mundo Vegano © 2021</span>
				</div>

			</div>
		</div>
		<!-- END PAGE FOOTER -->

		<!--================================================== -->

		<script>
			// Environment Variables Access
			window.env_basepath = '{{ env('APP_URL') }}';
		</script>

		<script src="/js/app.js"></script>

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="/js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="/js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="/js/plugin/fastclick/fastclick.min.js"></script>

		<!-- Tagator -->
		<script src="/js/plugin/jquery-tagator/fm.tagator.jquery.js"></script>

		<!-- Trumbowyg -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.23.0/trumbowyg.min.js"></script>

		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="/js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="/js/speech/voicecommand.min.js"></script>

		<!-- SmartChat UI : plugin -->
		<script src="/js/smart-chat-ui/smart.chat.ui.min.js"></script>
		<script src="/js/smart-chat-ui/smart.chat.manager.min.js"></script>
		
		<!-- PAGE RELATED PLUGIN(S) -->
		
		<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="/js/plugin/flot/jquery.flot.cust.min.js"></script>
		<script src="/js/plugin/flot/jquery.flot.resize.min.js"></script>
		<script src="/js/plugin/flot/jquery.flot.time.min.js"></script>
		<script src="/js/plugin/flot/jquery.flot.tooltip.min.js"></script>
		
		<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
		<script src="/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>
		
		<!-- Full Calendar -->
		<script src="/js/plugin/moment/moment.min.js"></script>
		<script src="/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>

		<script>
			$(document).ready(function() {

				// DO NOT REMOVE : GLOBAL FUNCTIONS!
				pageSetUp();

			});

		</script>
		@stack('scripts')
		<!-- Your GOOGLE ANALYTICS CODE Below -->
		{{-- <script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script> --}}

	</body>

</html>