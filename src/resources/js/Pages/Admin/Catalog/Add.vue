<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Добавить продукцию
            </h2>
        </template>

<!--        <div class="row">-->
            <div class="col-12">

                <BreezeValidationErrors class="mb-4" />

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-main-tab" data-bs-toggle="tab" data-bs-target="#nav-main" type="button" role="tab" aria-controls="nav-main" aria-selected="true">Основная информация</button>
                            <button class="nav-link" id="nav-meta-tab" data-bs-toggle="tab" data-bs-target="#nav-meta" type="button" role="tab" aria-controls="nav-meta" aria-selected="false">Мета информация</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-main" role="tabpanel" aria-labelledby="nav-main-tab">
                            <div>
                                <BreezeLabel for="name" value="Заголовок" />
                                <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                            </div>
                            <div>
                                <BreezeLabel for="short_description" value="Краткое описание" />
                                <BreezeTextarea id="short_description" type="text" class="mt-1 block w-full" v-model="form.short_description" />
                            </div>
                            <div>
                                <BreezeLabel for="description" value="Описание" />
                                <BreezeTextarea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            </div>
                            <div>
                                <BreezeLabel for="category_id" value="Категория" />
                                <select>
                                    <option></option>
                                    <template v-for="(category, index) in categories">
                                        <option :value="index" v-text="category" />
                                    </template>
                                </select>
                            </div>
                            <div>
                                <BreezeLabel for="image" value="Изображение" />
                                <input type="file" @input="uploadImage($event, 'image')" name="image" ref="image" />
                            </div>
                            <div>
                                <BreezeLabel for="image_preview" value="Превью-изображение" />
                                <input type="file" @input="uploadImage($event, 'image_preview')" name="image_preview" ref="image_preview" />
                            </div>

                            <h4 class="mt-4 mb-4">Характеристики</h4>
                        </div>

                        <div class="tab-pane fade" id="nav-meta" role="tabpanel" aria-labelledby="nav-meta-tab">
                            <div>
                                <BreezeLabel for="meta_title" value="Заголовок" />
                                <BreezeInput id="meta_title" type="text" class="mt-1 block w-full" v-model="form.meta_title" autofocus />
                            </div>
                            <div>
                                <BreezeLabel for="meta_description" value="Описание" />
                                <BreezeTextarea id="meta_description" type="text" class="mt-1 block w-full" v-model="form.meta_description" autofocus />
                            </div>
                            <div>
                                <BreezeLabel for="meta_image" value="Изображение" />
                                <input type="file" @input="uploadImage($event, 'meta_image')" name="meta_image" ref="meta_image" />
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Сохранить</BreezeButton>
                    </div>
                </form>

            </div>
<!--        </div>-->

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

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeCheckbox,
        BreezeInput,
        BreezeTextarea,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        useForm,
    },

    props: {
        status: String,
    },

    data() {
        return {
            categories: {},
            form: this.$inertia.form({
                name: '',
                short_description: '',
                description: '',
                image: null,
                image_preview: null,
                meta_title: '',
                meta_description: '',
                meta_image: null,
            }, {
                resetOnSuccess: true,
                forceFormData: true
            })
        }
    },
    mounted() {
        this.getCategories();
    },
    methods: {
        getCategories() {
            axios.get(`/api/categories/`)
                .then(response => {
                    this.categories = response.data;
                });
        },
        submit() {
            /*this.form.post('/api/news/add', {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);

                    }
                }
            })*/
        },
        uploadImage(e, fieldName) {
            this.form[fieldName] = e.target.files[0]
        }
    }
}
</script>
