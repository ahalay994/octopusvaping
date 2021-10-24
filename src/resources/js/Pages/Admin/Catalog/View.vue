<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0 d-flex align-items-center">
                    Каталог</h2>
                <Link class="btn btn-success" :href="route('admin.catalog.add')">Добавить продукцию</Link>
            </div>
        </template>


        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                <div class="mb-3">
                    <div class="mb-2">Фильтры:</div>
                    <div class="d-flex">
                        <div>
                            <BreezeLabel for="search" value="Поиск"/>
                            <BreezeInput id="search" type="text" class="block" v-model="search"/>
                        </div>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Наименование</th>
                        <th scope="col">Краткое описание</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Производитель</th>
                        <th scope="col">Категория</th>
                        <th scope="col">Видимость</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="item in catalog">
                        <tr>
                            <th scope="row">{{ item.id }}</th>
                            <td>{{ item.name }}</td>
                            <td>{{ item.short_description }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.manufacturer_id }}</td>
                            <td>{{ item.category_id }}</td>
                            <td>{{ item.visible === 1 ? 'Да' : 'Нет' }}</td>
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
                                            <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                               @click="deleteItem(item.id)">Удалить</a>
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
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeLabel from "@/Components/Label";
import BreezeInput from "@/Components/Input";

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,
        Head, Link,
        BreezeLabel,
        BreezeInput,
    },
    props: ['data'],
    data() {
        return {
            catalog: this.data,
            search: '',
        }
    },
    mounted() {
    },
    watch: {
        search: {
            handler(e) {
                let filter = [];
                this.data.forEach(item => {
                    if (item.name.toLowerCase().indexOf(e.toLowerCase()) > -1) {
                        filter.push(item);
                    }
                })
                this.catalog = filter;
            }
        }
    },
    methods: {
        getCatalog() {
            axios.get(this.route('api.catalog.get'))
                .then(response => {
                    this.catalog = response.data;
                });
        },

        deleteItem(id) {
            axios.delete(this.route('admin.catalog.delete', id))
                .then(response => {
                    this.getCatalog();
                })
                .catch(e => console.log(e))
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
