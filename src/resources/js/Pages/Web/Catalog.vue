<template>
    <WebLayout>
        <template #default>
            <Breadcrumb :items="{ category, categoryChild, catalog}" :current="catalog" />
            <div class="max-w-7xl mx-auto py-4">
                <h1>{{ catalog.name }}</h1>

                <div class="row">
                    <div class="col-9">
                        <Gallery class="gallery" :images="catalog.images" :path="catalog.image_path" />
                    </div>
                    <div class="col-3">
                        <h4>Производитель: {{ catalog.manufacturer }}</h4>
                        <h4>{{ catalog.description_short }}</h4>
                        <p class="h1">{{ catalog.price }} руб./шт.</p>
                    </div>
                    <div class="col-12 py-4">
                        <ul class="tabs flex m-0 space-x-2 p-0 text-white">
                            <li>
                                <button
                                    @click="currentTab(1)"
                                    :class="['inline-block px-4 py-2 tabs-btn', tab === 1 ? 'bg-gray-600' : 'bg-gray-400']"
                                >
                                    Описание
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="currentTab(2)"
                                    :class="['inline-block px-4 py-2 tabs-btn', tab === 2 ? 'bg-gray-600' : 'bg-gray-400']"
                                >
                                    Характеристики
                                </button>
                            </li>
                        </ul>
                        <div class="py-3 mt-3 bg-white">
                            <div v-if="tab === 1">
                                {{ catalog.description }}
                            </div>
                            <div v-if="tab === 2">
                                <div :class="['grid grid-cols-2 gap-4 py-2', id < catalog.specifications.length ? 'border-bottom' : '']"  v-for="(item, id, i) in catalog.specifications">
                                    <div>{{ item.specification_name }}</div>
                                    <div>{{ item.value }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/Web.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import Gallery from './Components/Gallery'
import Breadcrumb from './Components/Breadcrumbs'

export default {
    components: {
        WebLayout,
        Head, Link,
        Gallery,
        Breadcrumb,
    },
    props: [
        'catalog',
        'category',
        'categoryChild',
    ],
    data() {
        return {
            tab: 1
        }
    },
    mounted() {},
    watch: {},
    methods: {
        currentTab(tabNumber) {
            this.tab = tabNumber;
        }
    },
}
</script>

<style lang="scss">
.tabs {
    &-btn {

    }
}
</style>
