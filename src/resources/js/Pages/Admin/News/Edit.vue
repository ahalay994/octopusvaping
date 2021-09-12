<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Редактировать новость {{ data.id }}
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
                        <BreezeLabel for="title" value="Заголовок" />
                        <BreezeInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus />
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
                        <img :src="'/images/news/' + data.image" width="200">
                        <input type="file" @input="uploadImage($event, 'image')" name="image" ref="image" />
                    </div>

                    <div>
                        <img :src="'/images/news/' + data.image_preview" width="200">
                        <input type="file" @input="uploadImage($event, 'image_preview')" name="image_preview" ref="image_preview" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Редактировать</BreezeButton>
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
            this.form.post(`/api/news/edit/${this.data.id}`, {
                onSuccess: (response) => {
                    if (response && response.props && response.props.data) {
                        console.log('response', response.props.data);

                    }
                }
            })
        },
        uploadImage(e, fieldName) {
            this.form[fieldName] = e.target.files[0]
        }
    }
}
</script>
