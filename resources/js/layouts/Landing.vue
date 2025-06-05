<template>
  <main>
    <navigation></navigation>
    <div class="container-fluid mx-auto">
      <router-view></router-view>
      
    </div> 
    <footers></footers>
  </main>
</template>

<script>
import Navigation from '@/js/components/Navigation'
import Footers from '@/js/components/Footers'

export default {
  components: {
    Navigation,
    Footers
  },
  computed: {
    user() {
      return this.$store.getters.user
    }
  },
}
</script>
