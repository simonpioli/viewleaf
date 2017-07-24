<template>
  <span class="wind-direction" :style="'transform: rotate(' + bearing + ');'">↑</span>
</template>
<script>
export default {
    props: ['bearing']
}
</script>