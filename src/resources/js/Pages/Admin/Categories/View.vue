<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0 d-flex align-items-center">Категории</h2>
                <Link class="btn btn-success" :href="route('admin.category.add')">Добавить категорию</Link>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                <div class="mb-3">
                    <div class="mb-2">Фильтры:</div>
                    <div class="d-flex">
                        <div>
                            <BreezeLabel for="search" value="Поиск" />
                            <BreezeInput id="search" type="text" class="block" v-model="search" />
                        </div>
                    </div>
                </div>
                <table v-if="categories.length > 0" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Наименование</th>
                        <th scope="col">Машинное имя</th>
                        <th scope="col">Глубина сортировки</th>
                        <th scope="col">Родительcкая категория</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="category in categories">
                        <tr>
                            <th scope="row">{{ category.id }}</th>
                            <td>{{ category.name }}</td>
                            <td>{{ category.slug }}</td>
                            <td>{{ category.deep }}</td>
                            <td>{{ category.parent_id ? category.parent_id : 'Отсутствует' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="actions" data-bs-toggle="dropdown"
                                            aria-expanded="false"></button>
                                    <ul class="dropdown-menu" aria-labelledby="actions">
                                        <li>
                                            <BreezeNavLink :href="route('admin.category.edit', category.id)">
                                                Редактировать
                                            </BreezeNavLink>
                                        </li>
                                        <li>
                                            <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" @click="deleteItem(category.id)">Удалить</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
            </table>
                <h3 v-if="categories.length === 0" class="mb-0">Категорий не найдено</h3>
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
        Head,
        Link,
        BreezeLabel,
        BreezeInput,
    },
    props: ['data'],
    data() {
        return {
            categories: this.data,
            search: ''
        }
    },
    mounted() {
        this.get();
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
                this.categories = filter;
            }
        }
    },
    methods: {
        get() {
            axios.get(this.route('api.category.get'))
                .then(response => {
                    this.categories = response.data;
                })
                .catch(e => console.log(e))
        },

        deleteItem(id) {
            axios.delete(this.route('admin.category.delete', id))
                .then(response => {
                    this.get();
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
