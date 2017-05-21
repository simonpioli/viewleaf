<template>
    <img v-if="emoji.image != ''" class="slack__emoji" :src="emoji.image" :alt="emoji.label" />
    <span v-else-if="emoji.symbol != ''" class="slack__emoji">{{ emoji.symbol }}</span>
</template>
<script>
export default {
    props: ['emoji']
}
</script>