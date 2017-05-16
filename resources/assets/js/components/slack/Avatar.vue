<template>
    <img class="slack__avatar" :src="profile.thumbnail" :alt="profile.real_name" />
</template>
<script>
export default {
    props: ['profile']
}
</script>