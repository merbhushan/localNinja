import { Notify } from "quasar";
const timeout = 2000;
export const NotifyMethods = {
  showSuccess(message, actions = []) {
    if (message)
      Notify.create({
        message: `<i aria-hidden="true" role="presentation" class="material-icons q-icon notranslate text-positive q-ma-xs">check_circle</i>${message}`,
        position: "bottom",
        timeout: timeout,
        actions: actions,
        html: true
      });
  },
  showError(message) {
    Notify.create({
      message: `<i aria-hidden="true" role="presentation" class="material-icons q-icon notranslate text-negative q-ma-xs">error</i>${message}`,
      position: "bottom",
      timeout: timeout,
      html: true
    });
  },
  showWarning(message) {
    Notify.create({
      message: `<i aria-hidden="true" role="presentation" class="material-icons q-icon notranslate text-warning q-ma-xs">warning</i>${message}`,
      position: "bottom",
      timeout: timeout,
      html: true
    });
  },
  showInfo(message) {}
};

export default ({ Vue }) => {
  Vue.mixin({
    methods: NotifyMethods
  });
};
