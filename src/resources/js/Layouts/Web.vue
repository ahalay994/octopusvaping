<template>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-8">
                <div class="header-menu flex justify-content-between row">
                    <Link class="col-auto" href="#">Гарантия</Link>
                    <Link class="col-auto" href="#">Магазины</Link>
                    <Link class="col-auto" href="#">О нас</Link>
                </div>

                <div class="header-social row">
                    <a class="block col-auto" href="#" target="_blank">
                        <BIconInstagram class="header-social__icon header-social__instagram" />
                    </a>
                </div>
            </div>
        </div>

        <nav class="bg-gray-200">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">

                    <!-- Mobile menu button-->
                    <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
                        <button @click="toggleMenu" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path v-if="!showMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Desktop main menu with icon -->
                    <div class="flex-1 flex items-center justify-center lg:items-stretch lg:justify-start">
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('home')">
                                <img class="h-12 w-auto" src="/images/logo.svg" alt="logo">
                            </Link>
                        </div>
                        <div class="hidden lg:flex align-items-center lg:ml-6 w-100">
                            <div class="flex justify-content-between space-x-4 w-100">
                                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                                <MainMenu />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div :class="['lg:hidden', showMenu ? 'block' : 'hidden']" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>

                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <slot />
    </main>

    <footer class="bg-gray-200 pt-5 pb-2">
        <div class="">
            <div class="row max-w-7xl mx-auto">
                <div class="col-4 footer-social">
                    <a class="col-auto" href="#" target="_blank">
                        <BIconInstagram class="footer-social__icon footer-social__instagram" />
                    </a>
                </div>
                <div class="col-4">
                    <ul class="mb-0 p-0 flex w-100 flex flex-column justify-content-between">
                        <template v-for="(item, id, i) in $page.props.menu">
                            <li class="" v-if="Object.keys($page.props.menu).length / 2 > i">
                                <Link :href="route('category', item.slug)" class="" aria-current="page">
                                    {{ item.name }}
                                </Link>
                                <ul class="m-0 p-0" v-if="item.children.length > 0">
                                    <li class="" v-for="child in item.children">
                                        <Link :href="route('category', [item.slug, child.slug])" class="" aria-current="page">
                                            {{ child.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </template>
                    </ul>

                </div>
                <div class="col-4">
                    <ul class="mb-0 p-0 flex w-100 flex flex-column justify-content-between">
                        <template v-for="(item, id, i) in $page.props.menu">
                            <li class="" v-if="Object.keys($page.props.menu).length / 2 <= i">
                                <Link :href="route('category', item.slug)" class="" aria-current="page">
                                    {{ item.name }}
                                </Link>
                                <ul class="m-0 p-0" v-if="item.children.length > 0">
                                    <li class="" v-for="child in item.children">
                                        <Link :href="route('category', [item.slug, child.slug])" class="" aria-current="page">
                                            {{ child.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </template>
                    </ul>

                </div>
                <div class="col-12 copyright">
                    <p>{{new Date().getFullYear()}} © OCTOPUSVAPING</p>
                    <p>ООО "OCTOPUSVAPING" УНП 000000000
                        СВИДЕТЕЛЬСТВО О ГОС. РЕГИСТРАЦИИ №000000000 ОТ 00.00.0000 ВЫДАНО МИНСКИМ ГОРИСПОЛКОМОМ
                        РЕГИТРАЦИЯ В ТОРГОВОМ РЕЕСТРЕ РБ ОТ 20.04.2021 №000000</p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import AdminSidebar from '@/Components/Admin/Sidebar.vue'
import { Link } from '@inertiajs/inertia-vue3';
import { BIconInstagram } from 'bootstrap-icons-vue';
import MainMenu from './Components/MainMenu'

export default {
    components: {
        BreezeApplicationLogo,
        BreezeDropdown,
        BreezeDropdownLink,
        BreezeNavLink,
        BreezeResponsiveNavLink,
        AdminSidebar,
        Link,
        BIconInstagram,
        MainMenu,
    },

    data() {
        return {
            showMenu: false,
        }
    },
    methods: {
        toggleMenu() {
            this.showMenu = !this.showMenu;
        }
    }
}
</script>

<style lang="scss">
.header {
    z-index: 10;
}

.header-social {
    &__icon {

    }
    &__instagram {
        color: #830055;
    }
}

footer {
    .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    .footer {
        &-social {
            &__icon {
                font-size: 32px;
            }
        }
    }
    .copyright {
        p {
            margin: 0;
            font-size: 11px;
            line-height: 120%;
        }
    }
}
</style>
