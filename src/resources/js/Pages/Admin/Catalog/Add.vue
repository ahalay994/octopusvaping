<template>
    <Head title="Dashboard"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.catalog.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft/>
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">Добавить продукцию</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6">
                <div class="container bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                    <div class="row">
                        <div class="col-12">

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
                                            <BreezeLabel for="manufacturer" value="Производитель"/>
                                            <Multiselect
                                                id="manufacturer"
                                                v-model="form.manufacturer"
                                                :options="manufacturers"
                                            />
                                        </div>
                                        <div class="mb-3">
                                            <BreezeLabel for="category" value="Категория"/>
                                            <Multiselect
                                                id="category"
                                                v-model="form.category"
                                                :options="categories"
                                            />
                                        </div>
                                        <div class="mb-3">
                                            <BreezeLabel for="images" value="Изображения"/>
                                            <ImageUploader
                                                id="images"
                                                :images="form.images_url"
                                                :files="form.images"
                                                :multiple="true"
                                                name="images"
                                                refName="images"
                                            />
                                        </div>
                                        <div class="mb-3">
                                            <BreezeLabel for="image_preview" value="Превью-изображение"/>
                                            <ImageUploader
                                                id="image_preview"
                                                :images="form.image_preview_url"
                                                :files="form.image_preview"
                                                name="image_preview"
                                                refName="image_preview"
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
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeButton from "@/Components/Button";
import BreezeInput from "@/Components/Input";
import BreezeTextarea from "@/Components/Textarea";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import Multiselect from '@vueform/multiselect'
import {BIconChevronLeft, BIconXLg} from 'bootstrap-icons-vue';
import ImageUploader from '@/Components/Admin/ImagesUploader'

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeInput,
        BreezeTextarea,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        Multiselect,
        BIconChevronLeft, BIconXLg,
        ImageUploader,
    },

    props: {
        status: String,
    },

    watch: {},

    data() {
        return {
            categories: {},
            manufacturers: {},
            form: this.$inertia.form({
                name: '',
                short_description: '',
                description: '',
                images: [],
                images_url: [],
                image_preview: [],
                image_preview_url: [],
                price: '',
                price_old: '',
                meta_title: '',
                meta_description: '',
                meta_image_url: [],
                meta_image: [],
                category: '',
                manufacturer: '',
                specifications: []
            }, {
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
            this.form.post(this.route('admin.catalog.add'), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);
                    }
                }
            })
        },
        deleteSpecification(id) {
            this.form.specifications.splice(1, 1);
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
