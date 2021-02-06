<template>
  <q-inner-loading :showing="showing">
    <q-spinner
      color="primary"
      :size="$q.platform.is.mobile ? '2em' : '3em'"
      :thickness="$q.platform.is.mobile ? 2 : 3"
    />
  </q-inner-loading>
</template>
<script>
export default {
  name: 'InnerLoadingSpinner',
  props: {
    showing: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
<style lang="scss">
.q-inner-loading {
  z-index: 999;
}
</style>
