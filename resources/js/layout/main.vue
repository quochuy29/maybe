<template>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <nav class="px-0 mx-4 mt-4 shadow-none navbar navbar-main navbar-expand-lg border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="px-3 py-1 container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="px-0 pt-1 pb-0 mb-0 bg-transparent breadcrumb me-sm-6">
                        <li class="text-sm breadcrumb-item">
                            <a class="opacity-8" href="#">Pages</a>
                        </li>
                        <li class="text-sm breadcrumb-item active text-dark" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="mb-0 font-weight-bolder">Dashboard</h6>
                </nav>
                <div id="navbar" class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4 me-sm-4">
                    <div class="pe-md-3 d-flex align-items-center ms-md-auto">
                        <div class="input-group">
                            <span class="input-group-text text-body" style="z-index: 100;">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="#/sign-in" class="px-0 nav-link font-weight-bold text-body">
                                <i class="fa fa-user me-sm-1" aria-hidden="true"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a id="iconNavbarSidenav" href="#" class="p-0 nav-link text-body">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="px-3 nav-item d-flex align-items-center">
                            <a class="p-0 nav-link text-body">
                                <i class="cursor-pointer fa fa-cog fixed-plugin-button-nav" aria-hidden="true"></i>
                            </a>
                        </li>
                        <!--<li class="nav-item dropdown d-flex align-items-center pe-2">
                            <a id="dropdownMenuButton" href="#" class="p-0 nav-link text-body" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="cursor-pointer fa fa-bell" aria-hidden="true"></i>
                            </a>
                            <ul class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="py-1 d-flex">
                                            <div class="my-auto">
                                                <img src="../../../public/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user image">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-1 text-sm font-weight-normal">
                                                    <span class="font-weight-bold">New message</span> from Laur 
                                                </h6>
                                                <p class="mb-0 text-xs text-secondary">
                                                    <i class="fa fa-clock me-1" aria-hidden="true"></i> 13 minutes ago 
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="py-1 d-flex">
                                            <div class="my-auto">
                                                <img src="../../../public/img/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark me-3" alt="logo spotify">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-1 text-sm font-weight-normal">
                                                    <span class="font-weight-bold">New album</span> by Travis Scott 
                                                </h6>
                                                <p class="mb-0 text-xs text-secondary">
                                                    <i class="fa fa-clock me-1" aria-hidden="true"></i> 1 day 
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <sidenav nav-text="Billing" :to="{ name: 'index' }">
                                        <template #icon>
                                            <icon name="billing" />
                                        </template>
                                    </sidenav>
                                </li>
                            </ul>
                        </li>-->
                    </ul>
                </div>
            </div>
        </nav>
        <div class="py-4 container-fluid" style="min-height: 500px;">
            <router-view></router-view>
        </div>
		<Footer></Footer>
    </main>
</template>

<script>
    import sidenav from "./sidenav.vue";
	import Footer from './footer.vue';
    export default {
        components: {
			Footer,
            sidenav
        }
    }
</script>

<style lang="scss" scoped>
</style>
