<template>
    <swiper
        v-if="type === 'gallery'"
        :spaceBetween="30"
        :centeredSlides="true"
        :autoplay='{"delay": 250000, "disableOnInteraction": false}'
        :pagination='{"clickable": true}'
        :loop="true"
        :navigation="true"
        class="mySwiper"
    >
        <template v-for="image in images">
            <swiper-slide>
                <div class="img" :style="`background-image: url('${path}/${image}')`"></div>
            </swiper-slide>
        </template>
    </swiper>
    <swiper
        v-else-if="type === 'carousel'"
        :slides-per-view="6"
        :spaceBetween="30"
        :autoplay='{"delay": 250000, "disableOnInteraction": false}'
        :pagination='{"clickable": true}'
        :loop="true"
        :navigation="true"
        class="mySwiper"
    >
        <template v-for="image in images">
            <swiper-slide>
                <img :src="`${path}/${image}`" alt="" title="" />
            </swiper-slide>
        </template>
    </swiper>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import SwiperCore, {
    Autoplay,Pagination,Navigation
} from 'swiper';

SwiperCore.use([Autoplay,Pagination,Navigation]);

export default {
    components: {
        Swiper, SwiperSlide,
    },
    props: ['images', 'path', 'type']
}
</script>

<style lang="scss">
.gallery {
    .swiper {
        &-container {
            height: 500px;
        }
        &-slide {
            display: flex;
            justify-content: center;
            position: relative;
        }
        &-button {
            &-prev {
                padding: 30px 20px;
                &::after {
                    color: black;
                }
                &:hover {
                    background-color: rgba(255, 255, 255, 0.05);
                }
            }
            &-next {
                padding: 30px 20px;
                &::after {
                    color: black;
                }
                &:hover {
                    background-color: rgba(255, 255, 255, 0.05);
                }
            }
        }
    }
    .img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
}

.carousel {
    .swiper {
        &-container {
            height: 75px;
        }
        &-slide {
            display: flex;
            justify-content: center;
            position: relative;
        }
        &-button {
            &-prev {
                width: 10px;
                height: 10px;
                padding: 20px;
                left: 0;
                &::after {
                    font-size: 16px;
                    color: black;
                }
            }
            &-next {
                width: 10px;
                height: 20px;
                padding: 20px;
                right: 0;
                &::after {
                    font-size: 16px;
                    color: black;
                }
            }
        }
    }
}

</style>
