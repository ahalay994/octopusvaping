<template>
    <div :class="['sidebar', state === true ? 'open' : '']">
        <div class="sidebar__logo">
            <img src="/images/logo.svg" alt="logo">
        </div>
        <ul class="sidebar__menu accordion" id="accordionMenu">
            <li class="accordion-item" v-for="(item, index) in menu">
                <template v-if="item.children">
                    <h2 class="accordion-header" :id="`heading-${index}`">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                :data-bs-target="`#collapse-${index}`" aria-expanded="true"
                                :aria-controls="`collapse-${index}`">
                            {{ item.title }}
                        </button>
                    </h2>
                    <div :id="`collapse-${index}`" class="accordion-collapse collapse show"
                         :aria-labelledby="`heading-${index}`">
                        <div class="accordion-body">
                            <BreezeNavLink v-for="children in item.children" :href="route(children.link)"
                                           v-bind:class="[route().current(children.link) ? 'active' : '', 'd-block', 'py-3']">
                                {{ children.title }}
                            </BreezeNavLink>
                        </div>
                    </div>
                </template>

                <BreezeNavLink v-else :href="route(item.link)"
                               v-bind:class="[route().current(item.link) ? 'active' : '', 'd-block', 'py-3']">
                    {{ item.title }}
                </BreezeNavLink>
            </li>
        </ul>
    </div>
</template>

<script>
import BreezeNavLink from '@/Components/NavLink.vue'

export default {
    components: {
        BreezeNavLink
    },
    props: ['state'],
    data() {
        return {
            menu: []
        }
    },
    mounted() {
        this.menu = [];
        const roles = this.$page.props.auth.user.roles.find(role => role.slug === 'admin') || this.$page.props.auth.user.roles.find(role => role.slug === 'developer');
        if (roles !== undefined) {
            this.menu.push({
                title: 'Администрирование',
                children: [
                    {
                        title: 'Пользователи',
                        link: 'admin.user.view',
                        icon: ''
                    },
                ]
            });
        }
        this.menu.push(
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
                title: 'Слайдер',
                link: 'admin.slider.view',
                icon: ''
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
        );
    }
}
</script>

<style lang="scss">
.sidebar {
    margin-left: -300px;
    left: 0;
    position: fixed;
    top: 0;
    bottom: 0;
    z-index: 1030;
    box-shadow: 0 2px 2px 0 rgba(var(--elevation-base-color), .14), 0 3px 1px -2px rgba(var(--elevation-base-color), .12), 0 1px 5px 0 rgba(var(--elevation-base-color), .2);
    transition: box-shadow .3s .15s, transform .3s, margin-left .3s, margin-right .3s, width .3s, z-index 0s ease .3s, -webkit-box-shadow .3s .15s, -webkit-transform .3s;
    background: #585191;
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
                border: none !important;
            }

            a {
                padding: .8445rem 1rem !important;
                width: 100%;
                display: block;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;

                &.active {
                    color: #fff;
                    background: hsla(0, 0%, 100%, .05);
                }

                &:hover, &:focus, &:active {
                    color: rgba(255, 255, 255, 1);
                    background: hsla(0, 0%, 100%, .2);
                    text-decoration: none;
                    border: none !important;
                }
            }
        }
    }

    &.open {
        margin-left: 0;
    }

    .accordion {
        &-item {
            background-color: transparent;
            padding: 0;
            a {
                color: hsla(0,0%,100%,.8);
                padding: 15px 20px !important;
                text-decoration: none;
                font-size: 16px;
            }
        }
        &-header {

        }
        &-button {
            background-color: #80808085;
            color: white;
            &::after {
                filter: invert(100%) sepia(100%) saturate(0) hue-rotate(283deg) brightness(121%) contrast(120%);
            }
        }
        &-collapse {

        }
        &-body {
            padding: 0;
            a {
                color: hsla(0,0%,100%,.8);
                padding: 15px 20px !important;
                text-decoration: none;
                font-size: 16px;
            }
        }
    }
}
</style>
