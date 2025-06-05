<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Setting'">
          <div class="row align-items-center mt-4">
            <div class="col-lg-2 col-12">
              <label>Make a Request Number <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="params.make_a_request" required>
            </div>
            <div class="col-lg-2 col-12">
              <label>Ask our Admin Number <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="params.ask_our_admin" required>
            </div>
          </div>
        </card-form>
      </div>
      <div class="col-12">
        <div class="row">
            <div class="col-12">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" :disabled="loading">
                  <b-spinner
                    v-if="loading"
                    small class="me-3" variant="white"
                  />
                  Submit
                </button>
              </div>
            </div>
          </div>
      </div>
    </div>
  </form>
</template>

<script>
import axios from 'axios'
import CardForm from '../../../components/CardForm.vue'
import { mapGetters } from 'vuex'
import CardPage from '@/js/components/CardPage.vue'

export default {
  components: {
    CardForm,
    CardPage
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {

    return {
      params: {
        make_a_request: '6282134001088',
        ask_our_admin: '6282134001088'
      },
      loading:false
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
    title () {
      if (!isNaN(this.$route.params.id)) {
        return 'Edit'
      } else {
        return 'Create'
      }
    },
  },
  watch: {

  },

  async created () {
    this.getData()
  },

  methods: {
    async getData () {
      await axios.get(`/api/v1/setting`).then(res => {
        let data = res.data.data
        this.params = {
          make_a_request: data.make_a_request,
          ask_our_admin: data.ask_our_admin
        }
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      const param = new FormData()
      for (var key in this.params) {
        if (this.params[key] != null) {
          if (Array.isArray(this.params[key])) {
            this.params[key].forEach((value, index) => {
              param.append(`${key}[${index}]`, value);
            });
          } else {
            param.append(key, this.params[key]);
          }
        }
      }
      this.loading = true
        await axios.post('/api/v1/setting', param).then(res => {
          this.loading = false
          this.$root.toast('Data created!','success')
          this.getData()
        }).catch(err => {
          console.log(err.response, 'babi');
          this.loading = false
          this.$root.toast(err.response.data.message,'error')
        })
    }
  }
}
</script>

<style>
  
</style>