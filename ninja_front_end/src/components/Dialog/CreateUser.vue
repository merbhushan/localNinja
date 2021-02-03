<template>
  <q-dialog v-model="dialogState" full-width position="bottom">
    <q-card>
      <q-card-section style="padding: 10px 15px;">
        <div class="text-h5 middle-align">
          Add User
        </div>
        <q-space />

        <q-btn
          icon="close"
          flat
          round
          dense
          v-close-popup
          style="position: absolute; top: 10px; right: 10px;"
        />
      </q-card-section>

      <q-separator />

      <q-card-section
        style="max-height: 50vh;"
        class="middle-align scroll relative-center"
      >
        <q-form @submit="onSubmit" class="q-gutter-md">
          <div class="row">
            <div class="col-md-8 col-sm-12">
              <q-input
                filled
                v-model="name"
                label="Name *"
                full-width
                lazy-rules
                class="col-8"
                :rules="[
                  val => (val && val.length > 0) || 'Please Enter Name',
                  val =>
                    (val && /^[A-Za-z-' ]+$/.test(val)) ||
                    'Please enter characters only'
                ]"
              />
            </div>
            <div
              class="col-md-4 col-sm-12"
              style="text-align: center; padding: 12px;"
            >
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
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 col-sm-12">
              <q-avatar square size="240px">
                <img src="https://via.placeholder.com/240" />
              </q-avatar>
            </div>
            <div class="col-md-8 col-sm-12">
              <div>
                <q-btn
                  label="Capture"
                  style="width: 120px;"
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
              <div style="padding-top: 10px;">
                <q-btn
                  label="Upload"
                  style="width: 120px;"
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
            </div>
          </div>
          <div></div>
        </q-form>
      </q-card-section>

      <q-separator />

      <q-card-actions align="right" class="middle-align">
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

.middle-align {
  max-width: 1024px;
  margin: auto;
}

.q-toggle-full {
  width: 100%;

  .q-toggle__label {
    flex: 1 0 0;
  }
}
</style>
