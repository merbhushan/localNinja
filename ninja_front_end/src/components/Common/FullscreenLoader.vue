<template>
  <div
    class="q-loading fullscreen column flex-center text-grey-1"
    v-show="showing"
  >
    <q-spinner color="primary" size="3em" :thickness="2" />
  </div>
</template>
<script>
export default {
  name: 'FullscreenLoader',
  props: {
    showing: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
