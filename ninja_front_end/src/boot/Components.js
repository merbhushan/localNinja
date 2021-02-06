import InnerLoadingSpinner from 'src/components/common/InnerLoadingSpinner'
import LoadMoreSpinner from 'src/components/common/LoadMoreSpinner'
import FullscreenLoader from 'src/components/common/FullscreenLoader'

export default async ({ Vue }) => {
  Vue.component('InnerLoadingSpinner', InnerLoadingSpinner)
  Vue.component('LoadMoreSpinner', LoadMoreSpinner)
  Vue.component('FullscreenLoader', FullscreenLoader)
}
