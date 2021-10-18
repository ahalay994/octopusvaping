<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.news.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft />
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    Добавить новость
                </h2>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200">

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-3">
                        <BreezeLabel for="title" value="Заголовок" />
                        <BreezeInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus required/>
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="short_description" value="Краткое описание" />
                        <BreezeTextarea id="short_description" type="text" class="mt-1 block w-full" v-model="form.short_description" required />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="description" value="Описание" />
                        <BreezeTextarea id="description" type="text" class="mt-1 block w-full" v-model="form.description" required />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel value="Изображение" />
                        <label :class="['upload-image', form.image === null ? 'no-image' : '']" for="image" :style="`background-image: url('${image}')`" />
                        <input id="image" type="file" @input="uploadImage($event, 'image')" name="image" ref="image" hidden accept=".webp,.png,.jpg" />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel value="Превью-изображение" />
                        <label :class="['upload-image', form.image_preview === null ? 'no-image' : '']" for="image-preview" :style="`background-image: url('${image_preview}')`" />
                        <input id="image-preview" type="file" @input="uploadImage($event, 'image_preview')" name="image_preview" ref="image_preview" hidden accept=".webp,.png,.jpg" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4 btn btn-success" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Сохранить</BreezeButton>
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
import BreezeTextarea from "@/Components/Textarea";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import { BIconChevronLeft } from 'bootstrap-icons-vue';

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
        BIconChevronLeft,
    },

    props: ['errors'],

    data() {
        return {
            form: this.$inertia.form({
                title: '',
                short_description: '',
                description: '',
                image: null,
                image_preview: null
            }, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            image: null,
            image_preview: null,

        }
    },
    mounted() {
        console.log('errors', this.errors);
    },
    methods: {
        submit() {
            this.form.post(this.route('admin.news.add'), {
                onSuccess: (response) => {}
            })
        },
        uploadImage(e, fieldName) {
            const file = e.target.files[0];
            this.form[fieldName] = file;

            let reader = new FileReader
            reader.onload = e => {
                this[fieldName] = e.target.result
            }
            reader.readAsDataURL(file)
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
.upload-image {
    width: 200px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    cursor: pointer;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    transition: all .2s ease-in-out;
    border-radius: 4px;
    overflow: hidden;
    &::before {
        opacity: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        content: 'Загрузить изображение';
        color: black;
        position: absolute;
        inset: 0;
        background-color: rgba(255, 255, 255, 0.5);
        transition: all .2s ease-in-out;
    }
    &.no-image {
        &::before {
            background-color: rgba(117, 104, 104, 0.5);
            opacity: 1;
        }
    }
    &:hover {
        &::before {
            opacity: 1;
        }
    }
}
</style>
