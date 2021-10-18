<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0 d-flex align-items-center">Пользователи</h2>
                <Link class="btn btn-success" :href="route('admin.user.add.view')">Добавить пользователя</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="container p-6 bg-white border-b border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Имя</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Подтверждение почты</th>
                                    <th scope="col">Роль</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="item in users">
                                    <tr>
                                        <th scope="row">{{ item.id }}</th>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.email }}</td>
                                        <td>{{ item.email_verified_at ? 'Да' : 'Нет' }}</td>
                                        <td>{{ item.role }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="actions" data-bs-toggle="dropdown"
                                                        aria-expanded="false"></button>
                                                <ul class="dropdown-menu" aria-labelledby="actions">
                                                    <li>
                                                        <BreezeNavLink :href="route('admin.user.edit.view', item.id)">
                                                            Редактировать
                                                        </BreezeNavLink>
                                                    </li>
                                                    <li>
                                                        <BreezeNavLink
                                                            :href="route('admin.user.role.edit', item.id)">
                                                            Изменить роль
                                                        </BreezeNavLink>
                                                    </li>
                                                    <li>
                                                        <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" @click="deleteItem(item.id)">Заблокировать</a>
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
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink.vue'

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeNavLink,
        Head,
        Link,
    },
    props: ['data'],
    data() {
        return {
            users: this.data
        }
    },
    methods: {
        deleteItem(id) {
            axios.delete(this.route('admin.user.delete', id))
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
