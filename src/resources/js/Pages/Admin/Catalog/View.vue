<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Каталог</h2>
                <a :href="route('admin.catalog.add')">Добавить продукцию</a>
            </div>
        </template>


        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Наименование</th>
                            <th scope="col">Краткое описание</th>
                            <th scope="col">Описание</th>
                            <th scope="col">Изображения</th>
                            <th scope="col">Превью-изображение</th>
                            <th scope="col">Категория</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in data">
                            <tr>
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.name }}</td>
                                <td>{{ item.short_description }}</td>
                                <td>{{ item.description }}</td>
                                <td>
                                    <div v-if="item.images && item.images.length > 0" v-for="image in item.images" class="d-flex">
                                        <img :src="'/images/catalog/' + image.name" width="50"/>
                                    </div>
                                </td>
                                <td><img v-if="item.preview_image" :src="'/images/catalog/' + item.preview_image" width="50"/></td>
                                <td>{{ item.category_id }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="actions" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="actions">
                                            <li>
                                                <BreezeNavLink :href="route('admin.catalog.edit', item.id)">
                                                    Редактировать
                                                </BreezeNavLink>
                                            </li>
                                            <li>
                                                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" @click="deleteItem(item.id)">Удалить</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink.vue'

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,
        Head,
    },
    props: ['data'],
    data() {
        return {
        }
    },
    mounted() {},
    methods: {
        deleteItem(id) {

        }
    }
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
