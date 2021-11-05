<template>
    <WebLayout>
        <template #default>
            <div class="max-w-7xl mx-auto py-4">
                <h1>{{ category.name }}</h1>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-9">
                        <Grid :data="catalog" class-wrap="catalog" />
                    </div>
                </div>
            </div>
        </template>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/Web.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import Grid from './Components/Grid'

export default {
    components: {
        WebLayout,
        Head, Link,
        Grid,
    },
    props: [
        'category',
        'categoriesChild',
        'catalog',
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

</style>
