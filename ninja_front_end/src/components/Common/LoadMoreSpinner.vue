<template>
  <div class="row justify-center q-my-md">
    <q-spinner
      color="primary"
      :size="$q.platform.is.mobile ? '2em' : '3em'"
      :thickness="$q.platform.is.mobile ? 2 : 3"
    />
  </div>
</template>
