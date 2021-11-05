<template>
    <div class="address flex">
        <div class="address-list col-4">
            <div class="address-item" v-for="item in data" @click="changeLocations(item.id)">
                <h3 class="address-name" v-text="item.name" />
                <div class="address-work" v-html="item.work" />
            </div>
        </div>
        <div class="address-map col-8">
            <yandex-map
                :settings="settings"
                class="maps"
                :coords="coords"
                :zoom="zoom"
                :scroll-zoom="false"
            >
                <ymap-marker
                    v-for="item in data"
                    :coords="[parseFloat(item.lat), parseFloat(item.lon)]"
                    :marker-id="item.id"
                    :hint-content="item.name"
                    @click="onClickMarker"
                />
            </yandex-map>
        </div>
    </div>
</template>

<script>
import { loadYmap, yandexMap, ymapMarker } from 'vue-yandex-maps'

export default {
    components: {
        yandexMap,
        ymapMarker
    },
    props: ['data'],
    async mounted() {
        await loadYmap(this.settings);
    },
    data() {
        return {
            settings: {
                apiKey: '7001f6a9-4ce3-4c47-b366-ac07a4ebc4d3',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            coords: [53.910918006701806, 27.558691578615726],
            zoom: 11,
            check: 0,
        }
    },
    methods: {
        onClickMarker(e) {
            console.log(e.get('coords'));
        },
        async changeLocations(id) {
            const address = this.data[id];
            this.coords = [parseFloat(address.lat), parseFloat(address.lon)];
            this.zoom = 17;

            if (this.check === 0) {
                setTimeout(() => {
                    this.changeLocations(id);
                    this.check = 1;
                }, 100);
            }
            this.check = 0;
        },
    }
}
</script>

<style lang="scss">
.address {
    overflow-y: auto;
    &-item {
        padding: 10px;
        transition: all .2s ease-in-out;

        &:hover {
            background-color: #2F2F2F0D;
            cursor: pointer;
        }
    }
    &-work {
        p {
            margin-bottom: 0;
        }
    }
}
.maps {
    height: 400px;
}
</style>
