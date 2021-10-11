<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Категории</h2>
<!--                <a v-if="status === 'view'" href="javascript:void(0)" @click="add">Добавить категорию</a>-->
                <a :href="route('admin.category.add')">Добавить категорию</a>
            </div>
        </template>


        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <table class="table table-striped">
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
                            <template v-for="item in data">
                                <tr>
                                    <th scope="row">{{ item.id }}</th>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.slug }}</td>
                                    <td>{{ item.deep }}</td>
                                    <td>{{item.parent_id ? item.parent_id : 'Отсутствует'}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="actions" data-bs-toggle="dropdown"
                                                    aria-expanded="false"></button>
                                            <ul class="dropdown-menu" aria-labelledby="actions">
                                                <li>
                                                    <BreezeNavLink :href="route('admin.category.edit', item.id)">
                                                        Редактировать
                                                    </BreezeNavLink>
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

                        <div class="flex items-center justify-end mt-4">
                            <BreezeButton v-if="status === 'edit'" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Сохранить</BreezeButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head} from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeInput from "@/Components/Input";
import BreezeButton from "@/Components/Button";

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,
        Head,
        BreezeInput,
        BreezeButton,
    },
    props: ['data'],
    data() {
        return {
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            status: 'view',
        }
    },
    mounted() {
    },
    watch: {
        status: {
            handler(e) {
                console.log(e);
            }
        }
    },
    methods: {
        categories() {
            axios.get(`/api/categories/1`)
                .then(response => {
                    console.log(response.data);
                    return response.data;
                });
        },
        changeStatus() {
            this.status === 'view' ? this.status = 'edit' : this.status = 'view';
        },
        add() {
            this.status = 'edit';
            // this.data[this.data.length] = {};
        },
        submit() {
            this.status = 'view';
        }
    }
}
</script>
