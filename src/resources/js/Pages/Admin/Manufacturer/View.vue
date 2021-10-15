<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Производители</h2>
                <a v-if="state === 'view'" @click="addItem">Добавить производителя</a>
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
                                <th scope="col">Изображение</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(item, index) in manufacturers">
                                <tr>
                                    <th scope="row">
                                        <span v-if="item && item.id">{{ item.id }}</span>
                                    </th>
                                    <td>
                                        <span v-if="item.state === 'view' && item && item.name">{{ item.name }}</span>
                                        <BreezeInput v-if="item.state !== 'view' && item" :id="`name-${index}`" type="text" class="mt-1 block w-full" v-model="manufacturers[index].name"/>
                                    </td>
                                    <td>
                                        <img v-if="item.state === 'view' && item && item.image" :src="item.image"  width="50"/>
                                        <input v-if="item.state !== 'view' && item" type="file" @input="uploadImage($event, item.id ? `${item.id}` : `_${index}`, index)" :name="`image-${index}`" :ref="`image-${index}`" />
                                    </td>
                                    <td v-if="item && item.id">
                                        <div v-if="item.state === 'view'" class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="actions" data-bs-toggle="dropdown"
                                                    aria-expanded="false"></button>
                                            <ul class="dropdown-menu" aria-labelledby="actions">
                                                <li>
                                                    <a @click="editItem(index)">Редактировать</a>
                                                </li>
                                                <li>
                                                    <a @click="deleteItem(index)">Удалить</a>
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
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink'
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
            manufacturers: this.data,
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            state: 'view'
        }
    },
    watch: {
        manufacturers: {
            handler(e) {
                this.form = this.$inertia.form(e, {
                    resetOnSuccess: true,
                    forceFormData: true
                });
            },
            deep: true
        }
    },
    mounted() {},
    methods: {
        get() {
            axios.get(`/api/manufacturer`)
                .then(response => {
                    this.manufacturers = response.data;
                    this.form = this.$inertia.form(this.manufacturers, {
                        resetOnSuccess: true,
                        forceFormData: true
                    });
                    this.state = 'view';
                });
        },

        addItem() {
            this.manufacturers.push({
                id: null,
                name: '',
                image: '',
                state: 'add'
            });
            this.state = 'add';
        },

        editItem(id) {
            this.manufacturers[id].state = 'edit';
            this.state = 'edit';
        },

        deleteItem(id) {
            axios.delete(`/api/manufacturer/delete/${id}`)
                .then(response => {
                    this.get();
                })
                .catch(e => console.log(e))
        },

        submit() {
            this.form = this.$inertia.form({manufacturers: this.manufacturers}, {
                resetOnSuccess: true,
                forceFormData: true
            });
            this.form.post(`/api/manufacturer/save`, {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        this.get();
                    }
                }
            })
        },
        uploadImage(e, fieldName, id) {
            this.form[id][fieldName] = e.target.files[0]
            console.log(this.form);
        }
    }
}
</script>
