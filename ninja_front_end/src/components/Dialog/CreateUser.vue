<template>
  <q-dialog
    v-model="dialogState"
    full-width
    position="bottom"
    @before-show="beforeShow"
  >
    <q-card>
      <q-card-section style="padding: 10px 15px;">
        <div class="text-h5 middle-align" style="padding-left: 15px;">
          {{ this.user ? "Modify User" : "Add User" }}
        </div>

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
        <q-form ref="userForm" class="q-gutter-md">
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <q-input
                filled
                v-model="name"
                label="Name *"
                full-width
                lazy-rules
                class="col-8"
                :disable="user ? true : false"
                :rules="[
                  val => (val && val.length > 0) || 'Please Enter Name',
                  val =>
                    (val && /^[A-Za-z-0-9_' ]+$/.test(val)) ||
                    'Name can be alpha numeric only',
                  val => isUnique(val)
                ]"
              />
            </div>
            <!-- <div
              class="col-md-4 col-sm-12"
              style="text-align: center; padding: 12px;"
            >
              <span class="text-h6">Gender:</span>
              <q-radio
                name="gender"
                v-model="strGender"
                val="Male"
                label="Male"
              />
              <q-radio
                name="gender"
                v-model="strGender"
                val="Female"
                label="Female"
              />
            </div> -->
          </div>

          <div class="row">
            <div class="col-md-7 col-sm-12">
              <span v-if="avtar_error_message" style="color: red;">
                Please select user avatar.
              </span>
              <q-avatar square class="ration-43">
                <q-img
                  :src="
                    capturedImg ||
                      avatarUrl ||
                      'https://via.placeholder.com/240'
                  "
                />
              </q-avatar>
            </div>
            <div class="col-md-5 col-sm-12">
              <div>
                <q-btn
                  label="Capture"
                  style="width: 120px;"
                  @click="
                    () => {
                      this.$store.commit('showcase/SET_DIALOG_PROPERTIES', {
                        dialog: 'captureImage',
                        properties: {
                          continue: this.onCapture
                        },
                        refresh: true
                      });

                      this.$store.commit('showcase/updateDialogState', {
                        dialog: 'captureImage',
                        val: true,
                        parentDialog: this.dialog
                      });
                    }
                  "
                  color="primary"
                />
              </div>
              <div style="display: none">
                <q-file
                  ref="userAvatar"
                  v-model="userAvatar"
                  label="Pick File"
                  filled
                  style="max-width: 120px; max-height: 100px;"
                  @input="loadFileData"
                />
              </div>
              <div style="padding-top: 10px;">
                <q-btn
                  label="Gallery"
                  style="width: 120px;"
                  color="primary"
                  @click="
                    () => {
                      this.$refs.userAvatar.pickFiles();
                    }
                  "
                />
                <!-- <input
                  ref="fileUpload"
                  style="display: none"
                  type="file"
                  id="fileUpload"
                  @change="loadFileData($event)"
                /> -->
              </div>
            </div>
          </div>
        </q-form>
      </q-card-section>

      <q-separator />

      <q-card-actions align="right" class="middle-align">
        <q-btn
          :label="user ? 'Modify' : 'Create'"
          @click="createOrModifyUser"
          color="primary"
        />
        <q-btn
          label="Cancel"
          v-close-popup
          style="background-color: #bf1d1d; color: white;"
          class="q-ml-sm"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    callback: {
      type: Function,
      default: () => {}
    }
  },
  data() {
    return {
      avtar_error_message: null,
      // strGender: "Male",
      isFresh: false,
      dialog: "createNinjaUser",
      name: null,
      capturedImg: null,
      reader: new FileReader(),
      userAvatar: null,
      avatarUrl: null
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
    },
    objRequest() {
      var formData = new FormData();
      formData.append("name", this.name);
      formData.append("gender", this.strGender);
      if (this.capturedImg) {
        formData.append("avatar", this.dataURLtoFile(this.capturedImg));
      }
      return formData;
    },
    user() {
      return this.properties.user || null;
    }
  },

  methods: {
    dataURLtoFile(dataurl) {
      var arr = dataurl.split(","),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      let filename = this.name.trim() + "." + mime.split("/")[1];
      return new File([u8arr], filename, { type: mime });
    },
    createOrModifyUser() {
      this.$refs.userForm.validate().then(isValid => {
        if (!isValid) {
          return;
        }

        if (!this.capturedImg && !this.user) {
          this.avtar_error_message = "Please select user's avatar.";
          return;
        } else {
          this.avtar_error_message = "";
        }

        this.$store.commit("common/SET_FULL_LOADING", true);

        let userEvent = "user/createUser";
        let params = null;

        if (this.user && this.user.id) {
          userEvent = "user/modifyUser";
          params = { params: this.objRequest, userId: this.user.id };
        } else {
          params = this.objRequest;
        }

        this.$store.dispatch(userEvent, params).then(response => {
          if (response && response.status == 200) {
            this.showSuccess(
              this.user
                ? "User Modified Successfully."
                : "User created successfully."
            );
            this.callback();
            this.dialogState = false;
          } else {
            this.showSuccess(
              this.user
                ? "Error occurred while modifying a user."
                : "Error occurred while creation a user."
            );
          }
          this.$store.commit("common/SET_FULL_LOADING", false);
        });
      });

      return;
    },
    loadFileData(objFile) {
      if (objFile) {
        this.reader.readAsDataURL(objFile);
      }
    },
    onCapture(imageData) {
      this.capturedImg = imageData;
    },
    async isUnique(name) {
      let response = await this.$store.dispatch("user/users", {
        name
      });

      if (
        response.status === 200 &&
        (!response.data.total ||
          (this.user &&
            response.data.total == 1 &&
            response.data.data[0].id === this.user.id))
      ) {
        return true;
      }
      return "This name is already taken";
    },
    beforeShow() {
      this.resetData();
    },
    resetData() {
      let user = this.properties.user || {};
      this.name = user.name || null;
      this.strGender = user.gender || "Male";
      this.capturedImg = null;
      this.avatarUrl = user.avatar_url || null;
      this.userAvatar = null;
      this.avtar_error_message = null;
    }
  },
  mounted() {
    this.reader.addEventListener("load", event => {
      this.capturedImg = event.target.result;
    });
  }
};
</script>

<style>
.q-dialog__inner {
  padding: 0px !important;
}

.ration-43 {
  width: 400px;
  height: 300px;
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
