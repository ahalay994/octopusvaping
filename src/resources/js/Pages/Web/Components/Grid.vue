<template>
<div :class="['grid', classWrap]">
    <Card v-if="data && data.length > 0" v-for="item in data" :data="item" :classWrap="classItem" />
</div>
</template>

<script>
import Card from './Card'
export default {
    components: {
        Card,
    },
    props: ['data', 'classWrap', 'classItem']
}
</script>

<style lang="scss">
.grid {
    &.main {
        grid-template-columns: repeat(4, 1fr);
    }

    &.catalog {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
