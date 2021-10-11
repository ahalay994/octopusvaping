<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Пользователи</h2>
                <a :href="route('admin.user.add.view')">Добавить пользователя</a>
            </div>
        </template>


        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Имя</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Подтверждение почты</th>
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
                                                <BreezeNavLink :href="route('admin.user.permission.edit', item.id)">
                                                    Изменить роль
                                                </BreezeNavLink>
                                            </li>
                                            <li>
<!--                                                <a @click="deleteNews(item.id)">Заблокировать</a>-->
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
            users: this.data
        }
    }
}
</script>

<style scoped>

</style>
