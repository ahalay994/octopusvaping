<template>
    <yandex-map
        :settings="settings"
        class="maps"
        :coords="coords"
        :zoom="11"
        @click="onClick"
    >
        <ymap-marker
            :coords="coords"
            marker-id="123"
        />
    </yandex-map>
</template>

<script>
import { yandexMap, ymapMarker } from 'vue-yandex-maps'

export default {
    components: {
        yandexMap,
        ymapMarker
    },
    computed: {},
    props: ['lat', 'lon'],
    emits: ['update'],
    data() {
        return {
            settings: {
                apiKey: '7001f6a9-4ce3-4c47-b366-ac07a4ebc4d3',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            coords: [parseFloat(this.lat), parseFloat(this.lon)],
        }
    },
    methods: {
        onClick(e) {
            if (e.originalEvent !== undefined) {
                this.coords = e.get('coords');
                this.$emit('update', e.get('coords'));
            }
        }
    }
}
</script>

<style lang="scss">
.maps {
    height: 300px;
}
</style>
