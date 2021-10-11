<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Добавить категорию
            </h2>
        </template>
        <div class="container">
            <div class="row">
            <div class="col-12">

                <BreezeValidationErrors class="mb-4" />

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div>
                        <BreezeLabel for="name" value="Наименование" />
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                    </div>

                    <div>
                        <BreezeLabel for="deep" value="Глубина" />
                        <BreezeInput id="deep" type="text" class="mt-1 block w-full" v-model="form.deep" />
                    </div>

                    <div>
                        <BreezeLabel for="parent_id" value="Родительский компонент" />
                        <select v-if="categories" v-model="form.parent_id">
                            <option></option>
                            <template v-for="(category, index) in categories">
                                <option :value="index">{{category}}</option>
                            </template>
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Добавить</BreezeButton>
                    </div>
                </form>

            </div>
        </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeButton from "@/Components/Button";
import BreezeCheckbox from "@/Components/Checkbox";
import BreezeInput from "@/Components/Input";
import BreezeTextarea from "@/Components/Textarea";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import BreezeDropdown from "@/Components/Dropdown";

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeCheckbox,
        BreezeInput,
        BreezeTextarea,
        BreezeLabel,
        BreezeValidationErrors,
        BreezeDropdown,
        Head,
        Link,
        useForm,
    },

    props: ['data'],

    data() {
        return {
            form: this.$inertia.form({
                'name': '',
                'parent_id': '',
                'deep': '',
            }, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            categories: {}
        }
    },
    mounted() {
        this.getCategories();
    },
    methods: {
        getCategories() {
            axios.get(`/api/categories`)
                .then(response => {
                    this.categories = response.data;
                });
        },

        submit() {
            this.form.post('/api/categories/add', {
                onSuccess: (response) => {
                    console.log(response);
                    console.log(this.form);
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);
                    }
                }
            })
        },
    }
}
</script>
