<template>
    <ul class="menu mb-0 p-0 flex w-100 flex justify-content-between">
        <li class="menu-item relative" v-for="item in $page.props.menu">
            <Link :href="route('category', item.slug)" class="menu-link flex align-items-center bg-gray-200 hover:bg-gray-300 px-4 py-2 text-sm font-medium block" aria-current="page">
                {{ item.name }}
                <BIconCaretDownFill class="ml-2" v-if="item.children.length > 0" />
            </Link>
            <ul class="menu-children absolute mb-0 p-0" v-if="item.children.length > 0">
                <li class="menu-item" v-for="child in item.children">
                    <Link :href="route('category', [item.slug, child.slug])" class="menu-link bg-gray-200 hover:bg-gray-300 px-4 py-2 text-sm font-medium block" aria-current="page">
                        {{ child.name }}
                    </Link>
                </li>
            </ul>
        </li>
    </ul>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { BIconCaretDownFill } from 'bootstrap-icons-vue';

export default {
    name: "MainMenu",
    components: {
        Link,
        BIconCaretDownFill,
    }
}
</script>

<style lang="scss">
.menu {
    &-item {
        &:hover {
            .menu-link + .menu-children {
                display: block;
            }
        }
    }
    &-link {
        white-space: nowrap;
        color: #2d3748;
        border-radius: 2px;
        font-size: 16px;
    }
    &-children {
        display: none;
        min-width: 100%;
        z-index: 10;
        .menu-children {
            border-radius: 0;
        }
    }
}
</style>

