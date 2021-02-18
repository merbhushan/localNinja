import InnerLoadingSpinner from "src/components/Common/InnerLoadingSpinner";
import LoadMoreSpinner from "src/components/Common/LoadMoreSpinner";
import FullscreenLoader from "src/components/Common/FullscreenLoader";

export default async ({ Vue }) => {
  Vue.component("InnerLoadingSpinner", InnerLoadingSpinner);
  Vue.component("LoadMoreSpinner", LoadMoreSpinner);
  Vue.component("FullscreenLoader", FullscreenLoader);
};
