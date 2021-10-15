<template>
<div class="sidebar">
    <div class="sidebar__logo">
        <img src="/images/logo_admin.jpg" alt="logo">
    </div>
        <ul class="sidebar__menu accordion" id="accordionMenu">
            <li class="accordion-item" v-for="(item, index) in menu">
                <template v-if="item.children">
                    <h2 class="accordion-header" :id="`heading-${index}`">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="`#collapse-${index}`" aria-expanded="true" :aria-controls="`collapse-${index}`">
                            {{ item.title }}
                        </button>
                    </h2>
                    <div :id="`collapse-${index}`" class="accordion-collapse collapse show" :aria-labelledby="`heading-${index}`">
                        <div class="accordion-body">
                            <BreezeNavLink v-for="children in item.children" :href="route(children.link)" v-bind:class="[route().current(children.link) ? 'active' : '', 'd-block', 'py-3']">
                                {{ children.title }}
                            </BreezeNavLink>
                        </div>
                    </div>
                </template>

                <BreezeNavLink v-else :href="route(item.link)" v-bind:class="[route().current(item.link) ? 'active' : '', 'd-block', 'py-3']">
                    {{ item.title }}
                </BreezeNavLink>

            </li>
        </ul>


<!--        <li class="sidebar__menu-item" v-for="item in menu">
            <h4 v-if="item.children">
                {{ item.title }}
            </h4>
            <BreezeNavLink v-else :href="route(item.link)" v-bind:class="[route().current(item.link) ? 'active' : '']">
                {{ item.title }}
            </BreezeNavLink>

            <template v-if="item.children" v-for="children in item.children">
                <BreezeNavLink :href="route(children.link)" v-bind:class="[route().current(children.link) ? 'active' : '']">
                    {{ children.title }}
                </BreezeNavLink>
            </template>
        </li>-->
</div>
</template>

<script>
import BreezeNavLink from '@/Components/NavLink.vue'

export default {
    components: {
        BreezeNavLink
    },

    data() {
        return {
            menu: [
                /*{
                    title: 'Dashboard',
                    link: 'admin.dashboard',
                    icon: ''
                },*/
                {
                    title: 'Администрирование',
                    children: [
                        {
                            title: 'Пользователи',
                            link: 'admin.user.view',
                            icon: ''
                        },
                    ]
                },
                {
                    title: 'Параметры',
                    children: [
                        {
                            title: 'Категории',
                            link: 'admin.category.view',
                            icon: ''
                        },
                        {
                            title: 'Характеристики',
                            link: 'admin.specification.view',
                            icon: ''
                        },
                        {
                            title: 'Производители',
                            link: 'admin.manufacturer.view',
                            icon: ''
                        },
                    ]
                },
                {
                    title: 'Новости',
                    link: 'admin.news.view',
                    icon: ''
                },
                {
                    title: 'Каталог',
                    link: 'admin.catalog.view',
                    icon: ''
                },

            ]
        }
    }
}
</script>

<style lang="scss">
.sidebar {
    box-shadow: 0 2px 2px 0 rgba(var(--elevation-base-color),.14),0 3px 1px -2px rgba(var(--elevation-base-color),.12),0 1px 5px 0 rgba(var(--elevation-base-color),.2);
    transition: box-shadow .3s .15s,transform .3s,margin-left .3s,margin-right .3s,width .3s,z-index 0s ease .3s,-webkit-box-shadow .3s .15s,-webkit-transform .3s;
    background: #3c4b64;
    min-width: 300px;

    &__logo {
        display: flex;
        justify-content: center;
        padding: 10px 0;

        img {
            height: 50px;
            width: auto;
        }
    }

    &__menu {
        padding: 0;
        margin-top: 10px;
        &-item {
            &:hover, &:focus, &:active {
                border: none!important;
            }
            a {
                padding: .8445rem 1rem!important;
                width: 100%;
                display: block;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                &.active {
                    color: #fff;
                    background: hsla(0,0%,100%,.05);
                }
                &:hover, &:focus, &:active {
                    color: rgba(255, 255, 255, 1);
                    background: hsla(0,0%,100%,.2);
                    text-decoration: none;
                    border: none!important;
                }
            }
        }
    }
}
</style>
