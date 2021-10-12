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
                            <th scope="col">Изображение</th>
                            <th scope="col">Превью-изображение</th>
                            <th scope="col">Категория</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in news">
                            <tr>
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.name }}</td>
                                <td>{{ item.short_description }}</td>
                                <td>{{ item.description }}</td>
                                <td><img v-if="item.image" :src="'/images/category/' + item.image" width="100"/></td>
                                <td><img v-if="item.image_preview" :src="'/images/category/' + item.image_preview" width="100"/></td>
                                <td>{{ item.category_id }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="actions" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="actions">
                                            <li>
<!--                                                <BreezeNavLink :href="route('admin.news.edit', item.id)">-->
<!--                                                    Редактировать-->
<!--                                                </BreezeNavLink>-->
                                            </li>
                                            <li>
<!--                                                <a @click="deleteNews(item.id)">Удалить</a>-->
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
    }
}
</script>
