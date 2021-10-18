<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="d-flex align-items-center">
                <Link :href="route('admin.user.view')" class="d-block">
                    <div class="icon">
                        <BIconChevronLeft />
                    </div>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                    Добавить пользователя
                </h2>
            </div>
        </template>

        <div class="mx-auto sm:px-6 py-12">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit">
                    <div>
                        <BreezeLabel for="name" value="Имя" />
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="email" value="Email" />
                        <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="password" value="Пароль" />
                        <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton class="ml-4 btn btn-success" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Добавить
                        </BreezeButton>
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
        Multiselect,
        Head,
        Link,
        BIconChevronLeft,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                terms: false,
            })
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('admin.user.add'), {
                onFinish: () => this.form.reset('password'),
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
