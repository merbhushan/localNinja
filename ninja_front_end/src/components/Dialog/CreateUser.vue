<template>
  <q-dialog v-model="dialogState" position="bottom" full-width>
    <q-card>
      <q-card-section>
        <div class="text-h6">Terms of Agreement</div>
      </q-card-section>

      <q-separator />

      <q-card-section
        style="max-height: 50vh; max-width: 50%;"
        class="scroll relative-center"
      >
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            filled
            v-model="name"
            label="Name *"
            lazy-rules
            :rules="[
              val => (val && val.length > 0) || 'Please Enter Name',
              val =>
                (val && /^[A-Za-z-' ]+$/.test(val)) ||
                'Please enter characters only'
            ]"
          />

          <div class="col-12">
            <q-toggle
              size="md"
              v-model="isFresh"
              label="Fresh"
              left-label
              class="q-toggle-full"
            />
          </div>

          <div>
            <q-avatar size="28px">
              <img src="https://cdn.quasar.dev/app-icons/icon-128x128.png" />
            </q-avatar>
            <q-btn
              label="Capture New"
              @click="
                () => {
                  $store.commit('showcase/updateDialogState', {
                    dialog: 'captureImage',
                    val: true,
                    parentDialog: this.dialog
                  });
                }
              "
              color="primary"
            />
          </div>
        </q-form>
      </q-card-section>

      <q-separator />

      <q-card-actions align="right">
        <q-btn label="Submit" type="submit" color="primary" />
        <q-btn
          label="Reset"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "App",
  data() {
    return {
      isFresh: false,
      dialog: "createNinjaUser",
      name: null,
      capturedImg: null
    };
  },
  computed: {
    ...mapGetters("showcase", ["getDialogState", "getDialogProperties"]),
    dialogState: {
      get() {
        return this.getDialogState(this.dialog);
      },
      set(val) {
        this.$store.commit("showcase/updateDialogState", {
          dialog: this.dialog,
          val
        });
      }
    },
    properties() {
      return this.getDialogProperties(this.dialog);
    }
  },

  methods: {
    onSubmit() {
      console.log("on submit created");
    },
    onReset() {
      console.log("on reset created");
    },
    onCapture(imageData) {
      this.capturedImg = imageData;
    }
  }
};
</script>

<style>
.q-dialog__inner {
  padding: 0px !important;
}

.q-toggle-full {
  width: 100%;

  .q-toggle__label {
    flex: 1 0 0;
  }
}
</style>
