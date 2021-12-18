<template>
    <div :class="['card', classWrap]">
        <Link class="card-container p-4 flex flex-column" :href="data.category_parent_slug ? route('catalog', [data.category_slug, data.category_parent_slug, data.slug]) : route('category', [data.category_slug, data.slug])">
            <div class="card-image flex justify-content-center">
                <img :src="`/images/catalog/${data.image_preview}`" :alt="data.name" :title="data.name" />
            </div>
            <div v-if="data.name.length > 0" class="card-title">{{ data.name }}</div>
            <div v-if="data.manufacturer.length > 0" class="card-manufacturer">Производитель: {{ data.manufacturer }}</div>
            <div v-if="data.short_description.length > 0" class="card-description">{{ data.short_description }}</div>
            <div v-if="data.price" class="card-price flex align-items-end h-100">{{ parseFloat(data.price).toFixed(2) }} руб.</div>
        </Link>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Link
    },
    props: ['data', 'classWrap']
}
</script>

<style lang="scss">
.card {
    border-radius: 0;
    border: none;

    &-container {
        box-shadow: inset 0 0 0 1px #ececec;
        transition: box-shadow ease-out 0.2s;
        height: 100%;
        &:hover {
            color: #313131;
        }
    }

    &-image {
        height: 150px;
        width: auto;
        padding: 10px 0;
    }
    &-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 10px;
    }
    &-description {
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 10px;
    }
    &-manufacturer {
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 10px;
        font-style: italic;
    }
    &-price {
        font-weight: bold;
        font-size: 18px;
    }
}
</style>
