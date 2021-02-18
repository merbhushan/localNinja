<template>
  <q-dialog
    ref="dialog"
    :position="$q.platform.is.mobile ? 'bottom' : 'standard'"
    @hide="onDialogHide"
  >
    <q-card class="removeCartItem">
      <q-card-section class="row items-center q-pb-none">
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section class="inner text-center">
        <h5 v-html="text" />
        <p v-html="description" />
        <p>
          <q-btn
            no-caps
            unelevated
            rounded
            outline
            color="tertiary"
            label="No"
            class="q-ma-sm q-py-xs q-px-md radius-lg"
            @click="onCancelClick"
          />
          <q-btn
            no-caps
            unelevated
            rounded
            color="primary"
            label="Yes"
            class="q-ma-sm q-py-xs q-px-md radius-lg"
            @click="onOKClick"
          />
        </p>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
export default {
  name: 'EmptyCartConfirm',
  props: {
    text: {
      type: String,
      default: '',
    },
    description: {
      type: String,
      default: '',
    },
  },
  methods: {
    // following method is REQUIRED
    // (don't change its name --> "show")
    show() {
      this.$refs.dialog.show()
    },

    // following method is REQUIRED
    // (don't change its name --> "hide")
    hide() {
      this.$refs.dialog.hide()
    },

    onDialogHide() {
      // required to be emitted
      // when QDialog emits "hide" event
      this.$emit('hide')
    },

    onOKClick() {
      // on OK, it is REQUIRED to
      // emit "ok" event (with optional payload)
      // before hiding the QDialog
      this.$emit('ok')
      // or with payload: this.$emit('ok', { ... })

      // then hiding dialog
      this.hide()
    },

    onCancelClick() {
      // we just need to hide dialog
      this.hide()
    },
  },
}
</script>
<style lang="scss">
.removeCartItem {
  h5 {
    margin: 0;
    padding: 0 15px 12px;
    font-size: 20px;
    line-height: normal;
    @media (max-width: 767px) {
      font-size: 17px;
    }
  }
  p {
    margin: 0;
    padding: 0 15px 12px;
    font-size: 14px;
    line-height: normal;
    @media (max-width: 767px) {
      font-size: 13px;
    }
  }
}
</style>
