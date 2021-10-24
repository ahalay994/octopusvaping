<template>
    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.news.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft />
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    Редактировать новость #{{ data.id }}
                </h2>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200">

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-3">
                        <BreezeLabel for="visible" value="Видимость" />
                        <Toggle v-model="form.visible" />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="title" value="Заголовок" />
                        <BreezeInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="short_description" value="Краткое описание" />
                        <BreezeTextarea id="short_description" type="text" class="mt-1 block w-full" v-model="form.short_description" />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="description" value="Описание" />
                        <BreezeTextarea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel value="Изображение" />
                        <ImageUploader
                            id="image"
                            :images="form.image_url"
                            :files="form.image"
                            name="image"
                            refName="image"
                            :dirImage="form.dir_image"
                        />
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="image_preview" value="Превью-изображение" />
                        <ImageUploader
                            id="image_preview"
                            :images="form.image_preview_url"
                            :files="form.image_preview"
                            name="image_preview"
                            refName="image_preview"
                            :dirImage="form.dir_image"
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
import BreezeTextarea from "@/Components/Textarea";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import { BIconChevronLeft } from 'bootstrap-icons-vue';
import ImageUploader from '@/Components/Admin/ImagesUploader';
import Toggle from '@vueform/toggle';

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
        ImageUploader,
        Toggle,
    },
    props: ['data'],
    data() {
        return {
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            })
        }
    },
    mounted() {},
    methods: {
        submit() {
            this.form.post(this.route('admin.news.edit', this.data.id), {
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
    &:hover {
        &::before {
            opacity: 1;
        }
    }
}
</style>
