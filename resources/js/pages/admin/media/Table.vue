<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-list bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Media
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
        </div>
      </div>
      <card-page>
        <div class="row">
          <div class="col-12">
            <iframe src="/media?type=image,files" style="width: 100%; height: 600px; border: none;"></iframe>
          </div>
        </div>
      </card-page>
    </div>
  </div>
</template>

<script>
import CardPage from '@/js/components/CardPage.vue'
import { mapGetters } from 'vuex'

export default {
  metaInfo () {
    return { title: 'user' }
  },

  components: {
    CardPage
  },

  props: {
    multiNav: {
      type: Array,
      default: () => []
    }
  },

  watch: {
    
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
  },

  data () {
    return {
    }
  },

  mounted(){
    
  },

  methods: {
    
  }
}
</script>
