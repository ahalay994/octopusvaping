<template>
    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.catalog.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft/>
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">Редактировать товар #{{form.id}}</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6">
                <div class="container bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                    <BreezeValidationErrors class="mb-4"/>
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-main-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-main" type="button" role="tab"
                                        aria-controls="nav-main"
                                        aria-selected="true">Основная информация
                                </button>
                                <button class="nav-link" id="nav-meta-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-meta" type="button" role="tab"
                                        aria-controls="nav-meta"
                                        aria-selected="false">Мета информация
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content py-4" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-main" role="tabpanel"
                                 aria-labelledby="nav-main-tab">
                                <div class="mb-3">
                                    <BreezeLabel for="name" value="Заголовок"/>
                                    <BreezeInput id="name" type="text" class="mt-1 block w-full"
                                                 v-model="form.name"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="short_description" value="Краткое описание"/>
                                    <BreezeTextarea id="short_description" type="text" class="mt-1 block w-full"
                                                    v-model="form.short_description"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="description" value="Описание"/>
                                    <BreezeTextarea id="description" type="text" class="mt-1 block w-full"
                                                    v-model="form.description"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="price" value="Цена"/>
                                    <BreezeInput id="price" type="text" class="mt-1 block w-full"
                                                 v-model="form.price"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="price_old" value="Старая цена"/>
                                    <BreezeInput id="price_old" type="text" class="mt-1 block w-full"
                                                 v-model="form.price_old"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="manufacturer_id" value="Производитель"/>
                                    <Multiselect
                                        id="category_id"
                                        v-model="form.manufacturer"
                                        :options="manufacturers"
                                    />
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="category_id" value="Категория"/>
                                    <Multiselect
                                        id="category_id"
                                        v-model="form.category"
                                        :options="categories"
                                    />
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="images" value="Изображения"/>
                                    <ImageUploader
                                        :images="form.images_url"
                                        :files="form.images"
                                        :multiple="true"
                                        name="images"
                                        refName="images"
                                        :dirImage="form.dir_image"
                                    />
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="image_preview" value="Превью-изображение"/>
                                    <ImageUploader
                                        :images="form.image_preview_url"
                                        :files="form.image_preview"
                                        name="image_preview"
                                        refName="image_preview"
                                        :dirImage="form.dir_image"
                                    />
                                </div>

                                <h4 class="mt-4 mb-4">Характеристики</h4>
                                <template v-if="form.specifications.length > 0"
                                          v-for="(item, index) in form.specifications">
                                    <div class="mb-3 row">
                                        <div class="col-6">
                                            <Multiselect
                                                v-if="form.specifications[index]"
                                                v-model="form.specifications[index].name"
                                                :options="specifications"
                                            />
                                        </div>
                                        <div class="col-6 d-flex">
                                            <BreezeInput id="name" type="text" class="block w-full"
                                                         v-model="form.specifications[index].value"/>
                                            <a class="d-flex justify-content-center align-items-center h-100 ml-3 px-3 delete-specification"
                                               href="javascript:void(0)" @click="deleteSpecification(index)">
                                                <BIconXLg/>
                                            </a>
                                        </div>
                                    </div>
                                </template>

                                <a class="btn btn-secondary" @click="addSpecification">Добавить
                                    характеристику</a>
                            </div>

                            <div class="tab-pane fade" id="nav-meta" role="tabpanel"
                                 aria-labelledby="nav-meta-tab">
                                <div class="mb-3">
                                    <BreezeLabel for="meta_title" value="Заголовок"/>
                                    <BreezeInput id="meta_title" type="text" class="mt-1 block w-full"
                                                 v-model="form.meta_title"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="meta_description" value="Описание"/>
                                    <BreezeTextarea id="meta_description" type="text" class="mt-1 block w-full"
                                                    v-model="form.meta_description"/>
                                </div>
                                <div class="mb-3">
                                    <BreezeLabel for="meta_image" value="Изображение"/>
                                    <ImageUploader
                                        :images="form.meta_image_url"
                                        :files="form.meta_image"
                                        name="meta_image"
                                        refName="meta_image"
                                        :dirImage="form.dir_image"
                                    />
                                </div>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <BreezeButton class="ml-4 btn btn-success"
                                          :class="{ 'opacity-25': form.processing }"
                                          :disabled="form.processing">Сохранить
                            </BreezeButton>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeButton from "@/Components/Button";
import BreezeCheckbox from "@/Components/Checkbox";
import BreezeInput from "@/Components/Input";
import BreezeTextarea from "@/Components/Textarea";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import Multiselect from '@vueform/multiselect'
import ImageUploader from '@/Components/Admin/ImagesUploader'
import {BIconChevronLeft, BIconXLg} from 'bootstrap-icons-vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeCheckbox,
        BreezeInput,
        BreezeTextarea,
        BreezeLabel,
        BreezeValidationErrors,
        Head, Link,
        ImageUploader,
        Multiselect,
        BIconChevronLeft, BIconXLg
    },
    props: ['data'],
    data() {
        return {
            categories: {},
            manufacturers: {},
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            specifications: {},
        }
    },
    mounted() {
        this.getCategories();
        this.getManufacturers();
        this.getSpecifications();
    },
    methods: {
        getCategories() {
            axios.get(this.route('api.category.get.names'))
                .then(response => {
                    this.categories = response.data;
                });
        },

        getManufacturers() {
            axios.get(this.route('api.manufacturer.get.names'))
                .then(response => {
                    this.manufacturers = response.data;
                });
        },

        getSpecifications() {
            axios.get(this.route('api.specification.get.names'))
                .then(response => {
                    this.specifications = response.data;
                });
        },

        addSpecification() {
            this.form.specifications.push({
                name: '',
                value: ''
            });
        },

        submit() {
            this.form.post(this.route('admin.catalog.edit', this.form.id), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);

                    }
                }
            })
        },
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

.delete-specification {
    color: black;
}
</style>
