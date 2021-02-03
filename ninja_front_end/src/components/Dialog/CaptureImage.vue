<template>
  <q-dialog v-model="dialogState" position="bottom" full-width>
    <q-card>
      <q-card-section>
        <div class="text-h6">Terms of Agreement</div>
      </q-card-section>

      <q-separator />
      <q-card-section style=" max-width: 50%;" class="scroll relative-center">
        <q-select
          v-model="camera"
          :options="devices"
          option-value="deviceId"
          option-label="label"
          label="Select Device"
          map-options
        />
      </q-card-section>
      <q-card-section style=" max-width: 50%;" class="scroll relative-center">
        <div class="row">
          <div class="col-md-6 border">
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
          <div class="col-md-6">
            <figure class="figure">
              <img :src="img" class="img-responsive" />
            </figure>
          </div>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-actions align="right">
        <q-btn label="Capture" @click="onCapture" color="primary" />
        <q-btn
          label="Save & Continue"
          @click="saveAndContinue"
          color="primary"
        />
        <q-btn
          label="Cancel"
          v-close-popup
          color="primary"
          flat
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
