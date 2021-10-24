<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0 d-flex align-items-center">Слайдер на главной странице</h2>
                <a class="btn btn-success" href="javascript:void(0)" @click="addItem">Добавить слайдер</a>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Изображение</th>
                                <th scope="col">Вес</th>
                                <th scope="col">Видимость</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(item, index) in sliders">
                                <tr>
                                    <th scope="row">
                                        <span v-if="item && item.id">{{ item.id }}</span>
                                    </th>
                                    <td>
                                        <img v-if="item.state === 'view' && item && item.image" :src="`/images/slider/${item.image}`"  width="50"/>
                                        <input v-if="item.state !== 'view' && item" type="file" @input="uploadImage($event, item.id ? `${item.id}` : `_${index}`, index)" :name="`image-${index}`" :ref="`image-${index}`" />
                                    </td>
                                    <td>
                                        <span v-if="item.state === 'view' && item && item.order">{{ item.order }}</span>
                                        <BreezeInput v-if="item.state !== 'view' && item" id="order" type="text" class="block" v-model="item.order" />
                                    </td>
                                    <td>
                                        <span v-if="item.state === 'view' && item">{{ item.visible === 0 ? 'Нет' : 'Да' }}</span>
                                        <Toggle v-if="item.state !== 'view' && item" v-model="item.visible" />
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
                            <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Сохранить</BreezeButton>
                        </div>
                    </form>
                </div>
            </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import BreezeLabel from "@/Components/Label";
import BreezeInput from "@/Components/Input";
import BreezeButton from "@/Components/Button";
import Toggle from '@vueform/toggle';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        BreezeLabel,
        BreezeInput,
        BreezeButton,
        Toggle,
    },
    props: ['data'],
    data() {
        return {
            sliders: this.data,
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            state: 'view',
        }
    },
    watch: {
        sliders: {
            handler(e) {
                this.form = this.$inertia.form(e, {
                    resetOnSuccess: true,
                    forceFormData: true
                });
            },
            deep: true
        },
    },
    mounted() {},
    methods: {
        get() {
            axios.get(this.route('api.slider.get'))
                .then(response => {
                    this.sliders = response.data;
                    this.form = this.$inertia.form(this.sliders, {
                        resetOnSuccess: true,
                        forceFormData: true
                    });
                    this.state = 'view';
                });
        },

        addItem() {
            this.sliders.push({
                id: null,
                image: '',
                order: '',
                visible: 1,
                state: 'add'
            });
            this.state = 'add';
        },

        editItem(id) {
            this.sliders[id].state = 'edit';
            this.state = 'edit';
        },

        deleteItem(id) {
            axios.delete(this.route('admin.slider.delete', id))
                .then(response => {
                    this.get();
                })
                .catch(e => console.log(e))
        },

        submit() {
            this.form = this.$inertia.form({sliders: this.sliders}, {
                resetOnSuccess: true,
                forceFormData: true
            });
            this.form.post(this.route('admin.slider.save'), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        this.get();
                    }
                }
            })
        },
        uploadImage(e, fieldName, id) {
            this.form[id][fieldName] = e.target.files[0];
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
