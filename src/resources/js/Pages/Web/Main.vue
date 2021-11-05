<template>
    <WebLayout>
        <template #default>
            <div class="max-w-7xl mx-auto">
                <section class="py-3 gallery">
                    <Slider path="/images/slider" :images="slider" type="gallery" />
                </section>
                <section class="py-3">
                    <h2 class="mb-3">Лучшие предложения</h2>
                    <Grid :data="catalog" class-wrap="main"/>
                </section>
                <section class="py-3 carousel">
                    <h2 class="mb-3">Бренды</h2>
                    <Slider path="/images/manufacturer" :images="manufacturers" type="carousel" />
                </section>
                <section class="py-3">
                    <h2 class="mb-3">Наши адреса</h2>
                    <Map :data="addresses" />
                </section>
            </div>
        </template>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/Web.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import Slider from './Components/Slider';
import Grid from './Components/Grid'
import Map from './Components/Map'

export default {
    components: {
        WebLayout,
        Head, Link,
        Slider,
        Grid,
        Map,
    },
    props: [
        'slider',
        'catalog',
        'manufacturers',
        'addresses'
    ],
    data() {
        return {

        }
    },
    mounted() {},
    watch: {},
    methods: {},
}
</script>

<style lang="scss">
th, td {
    vertical-align: middle;
}
.dropdown-menu {
    padding: 0;
    a {
        display: block;
        width: 100%;
        padding: 10px !important;
        text-decoration: none;
        font-size: 16px;
        color: black;
        cursor: pointer;
        &:hover {
            border: 1px solid rgba(209, 213, 219, var(--tw-border-opacity));
        }
    }
}
</style>
