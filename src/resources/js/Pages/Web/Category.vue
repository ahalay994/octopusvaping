<template>
    <WebLayout>
        <template #default>
            <div class="max-w-7xl mx-auto py-4">
<!--                <Breadcrumbs />-->
                <h1>{{ category.name }}</h1>
                <div class="row">
                    <div class="col-3 filter">
                        <div class="filter-block py-3" v-if="Object.keys(specifications).length > 0" v-for="(values, id) in filter">
                            <h3>{{specifications[id].name}}</h3>
                            <div class="filter-button" v-for="(value, _id) in values">
                                <label class="checkbox" :for="specifications[id].slug + '-' + _id">
                                    <input
                                        @change="selectFilter($event, specifications[id].slug, value)"
                                        :id="specifications[id].slug + '-' + _id"
                                        type="checkbox"
                                        :checked="filterParams[specifications[id].slug] && filterParams[specifications[id].slug].indexOf(value) !== -1"
                                    >
                                    {{value}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <Grid :data="catalogData" class-wrap="catalog pt-3" />
                    </div>
                </div>
            </div>
        </template>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/Web.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import Grid from './Components/Grid';
import Checkbox from 'vue-material-checkbox';
import Breadcrumbs from "@/Pages/Web/Components/Breadcrumbs";

export default {
    components: {
        WebLayout,
        Head, Link,
        Grid,
        Checkbox,
        Breadcrumbs,
    },
    props: [
        'category',
        'categoriesChild',
        'catalog',
        'filter',
    ],
    data() {
        return {
            specifications: {},
            filterParams: {},
            catalogData: {},
        }
    },
    mounted() {
        this.catalogData = this.catalog;

        axios.get(this.route('api.specification.get.array'))
            .then(response => {
                this.specifications = response.data;

                if (location.search.length > 0) {
                    const searchParams = new URLSearchParams(location.search.slice(1));
                    for (const param of searchParams.entries()) {
                        this.filterParams[param[0]] = param[1].split(',');
                    }
                }
                this.filterCatalog();
            });
    },
    watch: {},
    methods: {
        selectFilter(event, category, value) {
            const url = new URL(location.href);
            const getKeyParam = url.searchParams.get(category);
            /*** Добавление параметра ***/
            if (event.target.checked === true) {
                // Если параметра с данным ключом нет
                if (getKeyParam === null) {
                    url.searchParams.append(category, value);
                }
                // Если ключ есть, то додбавляем значение
                else {
                    url.searchParams.set(category, (getKeyParam + ',' + value))
                }
            }
            /*** Удаление параметра ***/
            else  {
                let listParams = getKeyParam.split(',');
                // Если один парамент в списке
                if (listParams.length === 1) {
                    url.searchParams.delete(category);
                }
                // Если несколько параметров в списке
                else {
                    listParams = listParams.filter(param => { return param !== value });
                    url.searchParams.set(category, listParams.join());
                }
            }
            if (url.search.length > 0) {
                window.history.pushState({}, '', url.search);
            } else {
                window.history.pushState({}, '', location.pathname);
            }
            this.filterCatalog();
        },
        filterCatalog() {
            axios.get(this.route('api.catalog.filter', {
                'url': location.href
            }))
            .then(response => {
                this.catalogData = response.data;
            })
        },
    },
}
</script>

<style lang="scss">
.checkbox {
    display: flex;
    align-items: center;
    gap: 0.5em;

    input {
        appearance: none;
        background-color: #ffffff;
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 1.15em;
        height: 1.15em;
        border: 0.15em solid #002981;
        border-radius: 0.15em;
        transform: translateY(-0.075em);
        display: grid;
        place-content: center;

        &::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
            transform: scale(0);
            transform-origin: bottom left;
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #ffffff;
            background-color: #ffffff;
        }

        &:checked::before {
            transform: scale(1);
        }

        &:focus {
            outline: max(2px, 0.15em) solid #002981;
            outline-offset: max(2px, 0.15em);
        }
    }
}

.filter-button {
    + .filter-button {
        margin-top: 8px;
    }
}

.filter-block {
    + .filter-block {
        border-top: 1px solid rgba(162, 162, 162, 0.5);
    }
}

</style>
