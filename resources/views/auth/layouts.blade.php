<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Swasti Saba</title>
		<meta charset="utf-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="shortcut icon" href="{{asset('assets')}}/media/logos/favicon.png" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="{{asset('assets')}}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{asset('assets')}}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />

		<link href="{{asset('assets')}}/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
        <style>
            .loader {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -25px;
            margin-left: -25px;
            z-index: 9999;
            display: none; /* Mulai dengan status tersembunyi */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        </style>
        @yield('style')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header">
						<!--begin::Header top-->
						<div class="header-top d-flex align-items-stretch flex-grow-1">
							<!--begin::Container-->
							<div class="d-flex container-xxl align-items-stretch">
								<!--begin::Brand-->
								<div class="d-flex align-items-center align-items-lg-stretch me-5 flex-row-fluid">
									<!--begin::Heaeder navs toggle-->
									<button class="d-lg-none btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px ms-n3 me-2" id="kt_header_navs_toggle">
										<i class="ki-duotone ki-abstract-14 fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</button>
									<!--end::Heaeder navs toggle-->
									<!--begin::Logo-->
									<a href="index.html" class="d-flex align-items-center">
										<img alt="Logo" src="{{asset('assets')}}/media/logos/logo.png" class="h-25px h-lg-70px" />
									</a>
									<!--end::Logo-->
									<!--begin::Tabs wrapper-->
									<div class="align-self-end overflow-auto" id="kt_brand_tabs">
										<!--begin::Header tabs wrapper-->
										<div class="header-tabs overflow-auto mx-4 ms-lg-10 mb-5 mb-lg-0" id="kt_header_tabs" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_header_navs_wrapper', lg: '#kt_brand_tabs'}">
											<!--begin::Header tabs-->
											<ul class="nav flex-nowrap text-nowrap">
												@if (Auth::user()->role == 'pemda')
                                                <li class="nav-item">
													<a class="nav-link {{ Request::is('homepage') ? 'active' : '' }}" href="{{ route('dashboard.pemda') }}">Dashboard</a>
												</li>
												<li class="nav-item">
													<a class="nav-link {{ Request::is('pengisianform/*') ? 'active' : '' }}""  data-bs-toggle="tab" href="#kt_header_navs_tab_2">Intrument Penilaian</a>
												</li>
                                                @endif
                                                @if (Auth::user()->role == 'dinas')
                                                <li class="nav-item">
													<a class="nav-link {{ Request::is('penilaian') ? 'active' : '' }}" href="{{ route('dashboard.dinas') }}">Dashboard</a>
												</li>
                                                <li class="nav-item">
													<a class="nav-link {{ Request::is('validator') || Request::is('validator/*') ? 'active' : '' }}" data-bs-toggle="tab" href="#kt_header_navs_tab_4">Validasi</a>
												</li>
                                                <li class="nav-item">
													<a class="nav-link  {{ Request::is('pelaporan/*') ? 'active' : '' }}" data-bs-toggle="tab" href="#kt_header_navs_tab_5">Pelaporan</a>
												</li>
                                                @endif
                                                @if (Auth::user()->role == 'admin')
                                                <li class="nav-item">
													<a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
												</li>
												<li class="nav-item">
													<a class="nav-link {{ Request::is('periode/*') ? 'active' : '' }}" data-bs-toggle="tab" href="#kt_header_navs_tab_3">Assesment</a>
												</li>
                                                <li class="nav-item">
													<a class="nav-link  {{ Request::is('pelaporan/*') ? 'active' : '' }}" data-bs-toggle="tab" href="#kt_header_navs_tab_5">Pelaporan</a>
												</li>
                                                <li class="nav-item">
													<a class="nav-link {{ Request::is('master/*') ? 'active' : '' }}" data-bs-toggle="tab" href="#kt_header_navs_tab_6">Master</a>
												</li>
                                                <li class="nav-item">
													<a class="nav-link" data-bs-toggle="tab" href="#kt_header_navs_tab_7">Setting</a>
												</li>
                                                @endif
											</ul>
											<!--begin::Header tabs-->
										</div>
										<!--end::Header tabs wrapper-->
									</div>
									<!--end::Tabs wrapper-->
								</div>
								<!--end::Brand-->
								<!--begin::Topbar-->
								<div class="d-flex align-items-center flex-row-auto">

									<!--begin::User-->
									<div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
										<!--begin::User info-->
										<div class="btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 py-2 ps-2 pe-2 me-n2" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
											<!--begin::Name-->
											<div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
												<span class="text-white opacity-75 fs-8 fw-semibold lh-1 mb-1"> {{ Auth::user()->name }}</span>
												<span class="text-white fs-8 fw-bold lh-1">{{ Auth::user()->role }}</span>
											</div>
											<!--end::Name-->
											<!--begin::Symbol-->
											<!--begin::Menu wrapper-->
                                            <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                                <img alt="Logo" src="https://ui-avatars.com/api/?name={{Auth::user()->name}}&color=7F9CF5&background=EBF4FF" alt="" class="rounded-full h-20 w-20 object-cover"/>
                                            </div>
											<!--end::Symbol-->
										</div>
										<!--end::User info-->
										<!--begin::User account menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content d-flex align-items-center px-3">
													<!--begin::Avatar-->
													<div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                                        <img alt="Logo" src="https://ui-avatars.com/api/?name={{Auth::user()->name}}&color=7F9CF5&background=EBF4FF" alt="" class="rounded-full h-20 w-20 object-cover"/>
                                                    </div>
													<!--end::Avatar-->
													<!--begin::Username-->
													<div class="d-flex flex-column">
														<div class="fw-bold d-flex align-items-center fs-5 ms-2">{{ Auth::user()->name }}</div>
                                                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7 ms-2">{{ Auth::user()->role }}</a>
													</div>

												</div>
											</div>
											<!--end::Menu item-->
                                            <div class="separator my-2"></div>

											<!--begin::Menu item-->
											<div class="menu-item px-5">
                                                <a class="menu-link px-5" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"
                                                >Logout</a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                </form>

											</div>
											<!--end::Menu item-->
										</div>
										<!--end::User account menu-->
									</div>
									<!--end::User -->
								</div>
								<!--end::Topbar-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Header top-->
						<!--begin::Header navs-->
						<div class="header-navs d-flex align-items-stretch flex-stack h-lg-70px w-100 py-5 py-lg-0 overflow-hidden overflow-lg-visible" id="kt_header_navs" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_navs_toggle" data-kt-swapper="true" data-kt-swapper-mode="append" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header'}">
							<!--begin::Container-->
							<div class="d-lg-flex container-xxl w-100">
								<!--begin::Wrapper-->
								<div class="d-lg-flex flex-column justify-content-lg-center w-100" id="kt_header_navs_wrapper">
									<!--begin::Header tab content-->
									<div class="tab-content" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: false}" data-kt-scroll-height="auto" data-kt-scroll-offset="70px">
										<!--begin::Tab panel-->
										<div class="tab-pane fade" id="kt_header_navs_tab_1">
											<!--begin::Menu wrapper-->
											<div class="header-menu flex-column align-items-stretch flex-lg-row">
                                                <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">Dashboard
                                                    <!--begin::Separator-->
                                                    <span class="h-20px border-gray-500 border-start mx-3"></span>
                                                    <!--end::Separator-->
                                                    <!--begin::Description-->
                                                    <small class="text-gray-500 fs-7 fw-semibold my-1">#Dashboard</small>
                                                    <!--end::Description--></h1>
											</div>
											<!--end::Menu wrapper-->
										</div>
										<!--end::Tab panel-->
                                        @if (Auth::user()->role == 'pemda')
										<!--begin::Tab panel-->
										<div class="tab-pane fade {{ Request::is('pengisianform/*') ? 'active show' : '' }}"" id="kt_header_navs_tab_2">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
                                                    <a class="btn btn-sm btn-light-success fw-bold"  href="{{ route('pengisianform.tatanan') }}">Tatanan</a>
                                                    <a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('pengisianform.lembaga') }}">Kelembagaan</a>
                                               </div>
											</div>

											<!--end::Wrapper-->
										</div>
                                        @endif
										<!--end::Tab panel-->
                                        @if (Auth::user()->role == 'dinas')
										<div class="tab-pane fade {{ Request::is('validator/*') || Request::is('validator') ? 'active show' : '' }}" id="kt_header_navs_tab_4">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
													<a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('validator.periode') }}">Periode</a>
                                                </div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
										<!--begin::Tab panel-->
										<div class="tab-pane fade {{ Request::is('pelaporan/*') || Request::is('pelaporan') ? 'active show' : '' }}" id="kt_header_navs_tab_5">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
                                                    <a class="btn btn-sm btn-light-success fw-bold"  href="{{ route('pelaporan.tatanan') }}">Tatanan</a>
                                                    <a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('pelaporan.lembaga') }}">Kelembagaan</a>
                                               </div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->

                                        <!--begin::Tab panel-->
										<div class="tab-pane fade" id="kt_header_navs_tab_7">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">

											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
                                        @endif
                                        @if (Auth::user()->role == 'admin')
										<!--begin::Tab panel-->
                                        <div class="tab-pane fade {{ Request::is('periode/*') ? 'active show' : '' }}" id="kt_header_navs_tab_3">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
													<a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('periode.tatanan') }}">Assesment Periode</a>
                                                </div>
											</div>
											<!--end::Wrapper-->
										</div>

                                        <!--end::Tab panel-->
										<!--end::Tab panel-->
										<!--begin::Tab panel-->
										<div class="tab-pane fade {{ Request::is('validasi/*') ? 'active show' : '' }}" id="kt_header_navs_tab_4">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
													<a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('validator.periode') }}">Periode</a>
                                                </div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
										<!--begin::Tab panel-->
										<div class="tab-pane fade {{ Request::is('pelaporan/*') || Request::is('pelaporan') ? 'active show' : '' }}" id="kt_header_navs_tab_5">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
                                                    <a class="btn btn-sm btn-light-success fw-bold"  href="{{ route('pelaporan.tatanan') }}">Tatanan</a>
                                                    <a class="btn btn-sm btn-light-primary fw-bold"  href="{{ route('pelaporan.lembaga') }}">Kelembagaan</a>
                                               </div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
                                        <!--begin::Tab panel-->
										<div class="tab-pane fade {{ Request::is('master/*') ? 'active show' : '' }}" id="kt_header_navs_tab_6">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                                                <div class="d-flex flex-column flex-lg-row gap-2">
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.wilayah') }}">Wilayah</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.dinas') }}">Dinas</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.indikator') }}">Indikator</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.tatanan') }}">Tatanan</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.pertanyaan-tatanan') }}">Pertanyaan Tatanan</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.kelembagaan') }}">Kelembagaan</a>
                                                    <a class="btn btn-sm btn-light-success fw-bold" href="{{ route('master.pertanyaan-lembaga') }}">Pertanyaan Kelembagaan</a>

                                                </div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
                                        <!--begin::Tab panel-->
										<div class="tab-pane fade" id="kt_header_navs_tab_7">
											<!--begin::Wrapper-->
											<div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">

											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab panel-->
                                        @endif
									</div>
									<!--end::Header tab content-->
								</div>
								<!--end::Wrapper-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Header navs-->
					</div>
					<!--end::Header-->
					{{-- disini untuk kontek --}}
                    <div id="loader" class="loader"></div>
                    @yield('content')

				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<script src="{{asset('assets')}}/plugins/global/plugins.bundle.js"></script>
		<script src="{{asset('assets')}}/js/scripts.bundle.js"></script>
        <script src="{{asset('assets')}}/plugins/custom/datatables/datatables.bundle.js"></script>

        <script>
            // Ambil semua elemen menu
            var menuItems = document.querySelectorAll('.nav-link');

            // Tambahkan event listener untuk setiap elemen menu
            menuItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    // Hapus kelas 'active' dari semua elemen menu
                    menuItems.forEach(function(menuItem) {
                        menuItem.classList.remove('active');
                    });

                    // Tambahkan kelas 'active' pada elemen menu yang diklik
                    this.classList.add('active');
                });
            });
            $(document).ready(function() {
                // Sembunyikan loader saat halaman pertama dimuat
                $('#loader').hide();

                // Tampilkan loader saat halaman dimuat ulang atau pindah menu
                $(window).on('beforeunload', function() {
                    $('#loader').show();
                });

                // Sembunyikan loader setelah halaman dimuat ulang atau pindah menu selesai
                $(window).on('load', function() {
                    $('#loader').hide();
                });
            });
        </script>
        @yield('scripts')
	</body>
	<!--end::Body-->
</html>
