<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Новости
            </h2>
        </template>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    data() {
        return {
            news: [],
            config: {
                headers: {
                    // Authorization: 'Bearer ' + window.Laravel.api_token,
                    // Accept: 'application/json'
                }
            },
        }
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            axios.get('/api/news')
                .then(response => this.news = response)
                .catch(e => console.log(e))

            // axios.get('/api/news', this, {
            //     headers: {
            //         // 'Authorization': `Bearer ${localStorage.getItem("XSRF-TOKEN")}`,
            //         'X-CSRF-TOKEN': 'eyJpdiI6ImxvcFJKZUQwR1JGN1M0Q21VVlBSbXc9PSIsInZhbHVlIjoidGNxMkhFakdTNlpZbEFPYWs3aXY3VWllSjFRbFhUaWdTZGh2T0JDZFZkZS93NTB2Mm50Lzd0OGNJcVJiWEZER3kvTHhKdXJJd0dVaWcrKzVNMDFYenA0cFRaOU05WVBXeHMyTVRIZ3RxMlpnb0Z2b2pVZkhUQTdQL3hEVXRPUmgiLCJtYWMiOiIyMGQyNDY3MjI3NDM1NTZjMTkzMzk2ZjkyMWNlYmQzMzU0ZjAwZjMyZjM0YzY3YTUzNjA2ODViZTFlNmU5M2M2IiwidGFnIjoiIn0%3D',
            //         'Accept': 'application/json',
            //         'Content-Type': 'application/json'
            //     }
            // })
            //     .then(response => this.news = response)
            //     .catch(e => console.log(e))
        }
    }
}
</script>
