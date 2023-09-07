<template>
    <aside id="sidenav-main" style="z-index:100;"
        class="my-3 overflow-auto border-0 sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl ms-3 bg-transparent fixed-start"
        data-color="success">
        <div class="sidenav-header">
            <i id="iconSidenav"
                class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
                aria-hidden="true">
            </i>
            <a class="m-0 navbar-brand" href="/">
                <img src="/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Vite Soft UI Dashboard</span>
            </a>
        </div>
        <hr class="mt-0 horizontal dark">
        <div id="sidenav-collapse-main" class="w-auto h-auto collapse navbar-collapse max-height-vh-100 h-100">
            <ul class="navbar-nav">
                <li class="mt-3 nav-item">
                    <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">Management</h6>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Dashboard" :to="{ name: 'dashboard' }">
                        <template #icon>
                            <icon name="dashboard" />
                        </template>
                    </sidenav>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Tables" :to="{ name: 'index' }">
                        <template #icon>
                            <icon name="tables" />
                        </template>
                    </sidenav>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Billing" :to="{ name: 'member' }">
                        <template #icon>
                            <icon name="billing" />
                        </template>
                    </sidenav>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Virtual-reality" :to="{ name: 'product' }">
                        <template #icon>
                            <icon name="virtual-reality" />
                        </template>
                    </sidenav>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Rtl-page" :to="{ name: 'category' }">
                        <template #icon>
                            <icon name="rtl-page" />
                        </template>
                    </sidenav>
                </li>
                <li class="mt-3 nav-item">
                    <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">PAGES</h6>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Profile" :to="{ name: 'permission' }">
                        <template #icon>
                            <icon name="customer-support" />
                        </template>
                    </sidenav>
                </li>
                <li class="nav-item">
                    <sidenav nav-text="Sign-in" :to="{ name: 'permission' }">
                        <template #icon>
                            <icon name="sign-in" />
                        </template>
                    </sidenav>
                </li>
            <li class="nav-item">
                <sidenav nav-text="Sign-up" :to="{ name: 'permission' }">
                    <template #icon>
                        <icon name="sign-up" />
                    </template>
                </sidenav>
            </li>
        </ul>
    </div>
</aside>
</template>

<script>
    import Icon from "./icon.vue";
    import sidenav from "./sidenav.vue";
    export default {
        components: {
            Icon,
            sidenav
        }
    }
</script>

<style lang="css">
</style>