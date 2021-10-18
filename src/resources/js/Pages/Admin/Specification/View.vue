<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0 d-flex align-items-center">Характеристики</h2>
                <a class="btn btn-success" href="javascript:void(0)" @click="addItem">Добавить характеристику</a>
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

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Наименование</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(item, index) in specifications">
                            <tr>
                                <th scope="row">
                                    <span v-if="item && item.id">{{ item.id }}</span>
                                </th>
                                <td>
                                    <span v-if="item.state === 'view' && item && item.name">{{ item.name }}</span>
                                    <BreezeInput v-if="item.state !== 'view' && item" :id="`name-${index}`" type="text"
                                                 class="mt-1 block w-full" v-model="specifications[index].name"/>
                                </td>
                                <td v-if="item && item.id">
                                    <div v-if="item.state === 'view'" class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="actions" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="actions">
                                            <li>
                                                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" @click="editItem(index)">Редактировать</a>
                                            </li>
                                            <li>
                                                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" @click="deleteItem(index)">Удалить</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                    <div v-if="state !== 'view'" class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                      :disabled="form.processing">Сохранить
                        </BreezeButton>
                    </div>
                </form>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head} from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink'
import BreezeLabel from "@/Components/Label";
import BreezeInput from "@/Components/Input";
import BreezeButton from "@/Components/Button";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        BreezeInput,
        BreezeButton,
        BreezeLabel,
    },
    props: ['data'],
    data() {
        return {
            specifications: this.data,
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            state: 'view',
            search: ''
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
                this.specifications = filter;
            }
        }
    },
    methods: {
        get() {
            axios.get(this.route('api.specification.get'))
                .then(response => {
                    this.specifications = response.data;
                    this.state = 'view';
                });
        },

        addItem() {
            window.scrollTo(0,document.body.scrollHeight);
            this.specifications.push({
                id: null,
                name: '',
                state: 'add'
            });
            this.state = 'add';
        },

        editItem(id) {
            this.specifications[id].state = 'edit';
            this.state = 'edit';
        },

        deleteItem(id) {
            axios.delete(this.route('admin.specification.delete', id))
                .then(response => {
                    this.get();
                })
                .catch(e => console.log(e))
        },

        submit() {
            this.form = this.$inertia.form({specifications: this.specifications}, {
                resetOnSuccess: true,
                forceFormData: true
            });
            this.form.post(this.route('admin.specification.save'), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        this.get();
                        window.scrollTo(0,0);
                    }
                }
            })
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
