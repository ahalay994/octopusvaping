<template>
<ul class="breadcrumbs max-w-7xl mx-auto">
    <li class="breadcrumbs-item mr-2">
        <Link :href="route('home')">Главная <BIconArrowRightShort class="ml-2" /></Link>
    </li>
    <li class="breadcrumbs-item mr-2" v-if="items.category && Object.keys(items.category).length > 0">
        <Link :href="route('category', items.category.slug)">{{items.category.name}} <BIconArrowRightShort class="ml-2"/></Link>
    </li>
    <li class="breadcrumbs-item mr-2" v-if="items.categoryChild && Object.keys(items.categoryChild).length > 0">
        <Link :href="route('category', items.categoryChild.slug)">{{items.categoryChild.name}} <BIconArrowRightShort class="ml-2" /></Link>
    </li>
    <li class="breadcrumbs-item mr-2" v-if="current && Object.keys(current).length > 0">
        {{current.name}}
    </li>
</ul>
</template>

<script>
import { BIconArrowRightShort } from 'bootstrap-icons-vue';
import {Link} from '@inertiajs/inertia-vue3';

export default {
    props: {
        items: {},
        current: '',
    },
    components: {
        BIconArrowRightShort,
        Link,
    }
};
</script>

<style lang="scss">
.breadcrumbs {
    padding: 24px 0 0 0;
    display: flex;
    margin-bottom: 0;
    &-item {
        a {
            display: flex;
            align-items: center;
        }
    }
}
</style>
