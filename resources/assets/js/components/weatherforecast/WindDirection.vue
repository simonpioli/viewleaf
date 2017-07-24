<template>
  <span class="wind-direction" :style="style">↑</span>
</template>
<script>
export default {
    props: ['bearing'],
    computed: {
        style: function() {
            return 'transform: rotate(' + this.bearing + 'deg);';
        }
    }
}
</script>