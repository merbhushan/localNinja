<template>
  <q-dialog v-model="dialogState" position="bottom">
    <q-card style="width: 40%;">
      <q-card-section>
        <q-toolbar class="q-pa-none">
          <span class="text-h5">
            Add User
          </span>
          <q-space />

          <q-btn icon="close" flat round dense v-close-popup />
        </q-toolbar>
      </q-card-section>

      <q-separator />

      <q-card-section style="max-height: 50vh;" class="scroll relative-center">
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            filled
            v-model="name"
            label="Name *"
            full-width
            lazy-rules
            :rules="[
              val => (val && val.length > 0) || 'Please Enter Name',
              val =>
                (val && /^[A-Za-z-' ]+$/.test(val)) ||
                'Please enter characters only'
            ]"
          />

          <div class="col-12">
            <q-toolbar class="q-pa-none">
              <span class="text-h6">Gender:</span>
              <q-radio
                name="gender"
                v-model="strGender"
                val="MALE"
                label="Male"
              />
              <q-radio
                name="gender"
                v-model="strGender"
                val="FEMALE"
                label="Female"
              />
            </q-toolbar>
          </div>

          <div>
            <q-avatar size="120px">
              <img src="https://via.placeholder.com/120" />
            </q-avatar>
          </div>
          <div>
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
      strGender: "",
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
