<template>
    <swiper :style="{'--swiper-navigation-color': '#000','--swiper-pagination-color': '#000'}"
            :spaceBetween="10"
            :pagination='{"clickable": true}'
            :navigation="true"
            :thumbs="{ swiper: thumbsSwiper }"
            :lazy="true"
            :zoom="true"
            class="gallery__main"
    >
        <swiper-slide v-for="image in images">
            <div class="swiper-zoom-container">
                <img class="swiper-lazy" :data-src="`/${path}${image.name}`"/>
                <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
            </div>
        </swiper-slide>
    </swiper>
    <swiper @swiper="setThumbsSwiper"
            :spaceBetween="10"
            :slidesPerView="4"
            :freeMode="true"
            :watchSlidesProgress="true"
            :lazy="true"
            :zoom="true"
            class="gallery__thumbs"
    >
        <swiper-slide v-for="image in images">
            <div class="swiper-zoom-container">
                <img class="swiper-lazy" :data-src="`/${path}${image.name}`"/>
                <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
            </div>
        </swiper-slide>
    </swiper>
</template>

<script>
import {Swiper, SwiperSlide} from 'swiper/vue';
import SwiperCore, {
    Navigation, Thumbs, Lazy, Zoom
} from 'swiper';

SwiperCore.use([Navigation, Thumbs, Lazy, Zoom]);

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    props: ['images', 'path'],
    data() {
        return {
            thumbsSwiper: null
        };
    },
    methods: {
        setThumbsSwiper(swiper) {
            this.thumbsSwiper = swiper;
        },
    }
}
</script>

<style lang="scss">
.gallery {
    &__main {
        height: 500px;
        margin-bottom: 10px;
    }
    &__thumbs {
        height: 70px;
        img {
            cursor: pointer;
        }
    }
}
</style>
