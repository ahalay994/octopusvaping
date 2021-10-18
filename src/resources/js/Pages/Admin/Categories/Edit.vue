<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.category.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft />
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    Редактировать категорию #{{ data.id }}
                </h2>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200">

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-3">
                        <BreezeLabel for="name" value="Наименование" />
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="deep" value="Глубина" />
                        <BreezeInput id="deep" type="text" class="mt-1 block w-full" v-model="form.deep" />
                    </div>

                    <div>
                        <BreezeLabel for="parent_id" value="Родительский компонент" />
                        <Multiselect
                            id="parent_id"
                            v-model="form.parent_id"
                            :options="categories"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4 btn btn-success" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Редактировать</BreezeButton>
                    </div>
                </form>

            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeButton from "@/Components/Button";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import Multiselect from '@vueform/multiselect'
import { BIconChevronLeft } from 'bootstrap-icons-vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        Multiselect,
        BIconChevronLeft,
    },

    props: ['data'],

    data() {
        return {
            form: this.$inertia.form(this.data, {
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
            axios.get(this.route('api.category.get.all', this.data.id))
                .then(response => {
                    this.categories = response.data;
                });
        },

        submit() {
            console.log(this.form);
            this.form.post(this.route('admin.category.edit', this.data.id), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);
                    }
                }
            })
        }
    }
}
</script>

<style lang="scss">
.icon {
    font-size: 20px;
    color: black;
    padding: 10px 0;
    margin-right: 10px;
}
</style>
