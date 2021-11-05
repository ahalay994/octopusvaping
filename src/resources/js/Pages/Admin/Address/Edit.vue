<template>
    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.address.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft />
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    Редактировать адрес #{{ data.id }}
                </h2>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200">

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-3">
                        <BreezeLabel for="name" value="Название точки" />
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus required/>
                    </div>

                    <div class="mb-3">
                        <BreezeLabel for="work" value="Название точки" />
                        <editor
                            :initialValue="form.work"
                            apiKey="s4rgje0b0q5pmnhppultaedzyhpm8qobhkx9l5q33zfpdqyf"
                            :init="editorConfig"
                            v-model="form.work"
                        >
                        </editor>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <BreezeLabel for="lat" value="Долгота" />
                                <BreezeInput id="lat" type="text" class="mt-1 block w-full" v-model="form.lat" required />
                            </div>

                            <div class="mb-3">
                                <BreezeLabel for="lon" value="Широта" />
                                <BreezeInput id="lon" type="text" class="mt-1 block w-full" v-model="form.lon" required />
                            </div>
                        </div>
                        <div class="col-6">
                            <Maps @update="getCoords" :lat="form.lat" :lon="form.lon"  />
                        </div>
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
import Maps from '../../../Components/Admin/Maps'
import Editor from '@tinymce/tinymce-vue';

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
        Maps,
        Editor,
    },
    props: ['data'],
    data() {
        return {
            form: this.$inertia.form(this.data, {
                resetOnSuccess: true,
                forceFormData: true
            }),
            editorConfig: {
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap',
                    'searchreplace visualblocks code fullscreen',
                    'print preview anchor insertdatetime media',
                    'paste code help wordcount table'
                ],
                toolbar:
                    'undo redo | formatselect | bold italic | \
                    alignleft aligncenter alignright | \
                    bullist numlist outdent indent | help'
            },
        }
    },
    mounted() {},
    methods: {
        submit() {
            this.form.post(this.route('admin.address.edit', this.data.id), {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);
                    }
                }
            })
        },
        getCoords(val) {
            this.form.lat = val[0];
            this.form.lon = val[1];
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
    &:hover {
        &::before {
            opacity: 1;
        }
    }
}
</style>
