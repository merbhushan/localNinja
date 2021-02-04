<template>
  <q-dialog v-model="dialogState" full-width position="bottom">
    <q-card>
      <q-card-section style="padding: 10px 15px;">
        <div class="text-h5 middle-align" style="padding-left: 15px;">
          Capture Image
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
      <q-card-section style="max-height: 50vh;" class="middle-align scroll ">
        <q-banner dense class="bg-red text-white" v-if="blnDisplayError">
          Sorry! We were not able to detect your web cam.
        </q-banner>
        <q-select
          v-model="camera"
          :options="devices"
          option-value="deviceId"
          option-label="label"
          label="Select Device"
          map-options
        />

        <div class="row q-pt-sm">
          <div class="col-sm-12 col-md-6 border">
            <vue-web-cam
              ref="webcam"
              height="250"
              :selectFirstDevice="true"
              :device-id="deviceId"
              @started="onStarted"
              @stopped="onStopped"
              @error="onError"
              @cameras="onCameras"
              @camera-change="onCameraChange"
            />
          </div>
          <div class="col-sm-12 col-md-6" v-if="img">
            <q-img
              height="250px"
              width="250px"
              :src="img"
              class="img-responsive"
            />
          </div>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-actions align="right">
        <q-btn
          label="Capture"
          @click="onCapture"
          :disabled="blnDisplayError"
          color="primary"
        />
        <q-btn
          label="Save & Continue"
          @click="saveAndContinue"
          :disabled="blnDisplayError"
          color="primary"
        />
        <q-btn
          label="Cancel"
          style="background-color: #bf1d1d; color: white;"
          v-close-popup
          class="q-ml-sm"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { WebCam } from "vue-web-cam";
import { mapGetters } from "vuex";

export default {
  name: "App",
  components: {
    "vue-web-cam": WebCam
  },
  props: {
    continue: {
      type: Function,
      default: () => {}
    },
    emitEventOnSelect: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      dialog: "captureImage",
      name: null,
      img: null,
      camera: null,
      blnDisplayError: false,
      deviceId: null,
      devices: []
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

    device: function() {
      return this.devices.find(n => n.deviceId === this.deviceId);
    }
  },
  watch: {
    camera: function(id) {
      this.deviceId = id;
    },
    devices: function() {
      // Once we have a list select the first one
      const [first, ...tail] = this.devices;
      if (first) {
        this.camera = first.deviceId;
        this.deviceId = first.deviceId;
      }
    }
  },
  methods: {
    saveAndContinue() {
      this.continue(this.img);
      if (this.emitEventOnSelect) {
        this.$root.$emit("captured-image-data", this.img);
      }
      this.dialogState = false;
    },

    onCapture() {
      this.img = this.$refs.webcam.capture();
    },
    onStarted(stream) {
      console.log("On Started Event", stream);
    },
    onStopped(stream) {
      console.log("On Stopped Event", stream);
    },
    onStop() {
      this.$refs.webcam.stop();
    },
    onStart() {
      this.$refs.webcam.start();
    },
    onError(error) {
      this.blnDisplayError = true;
      console.log("On Error Event", error);
    },
    onCameras(cameras) {
      this.devices = cameras;
      console.log("On Cameras Event", cameras);
    },
    onCameraChange(deviceId) {
      this.deviceId = deviceId;
      this.camera = deviceId;
      console.log("On Camera Change Event", deviceId);
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
