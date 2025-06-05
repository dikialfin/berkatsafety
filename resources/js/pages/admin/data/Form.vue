<template>
  <div class="row">
    <div class="col-12">
      <card-form :title="'Data'">
        <form @submit.prevent="save">
        <div class="row align-items-center ">
          <div class="col-lg-2 col-12">
            <label>Name <span class="text-danger">*</span></label>
          </div>
          <div class="col-lg-4 col-12">
            <input class="form-control" v-model="params.name" required>
          </div>
          <div class="col-lg-2 col-12">
            <label>Status <span class="text-danger">*</span></label>
          </div>
          <div class="col-lg-4 col-12">
            <select class="form-control" v-model="params.status" required>
              <option value="draft">Draft</option>
              <option value="release">Release</option>
              <option value="process">Process</option>
              <option value="done">Done</option>
              <option value="failed">Failed</option>
            </select>
          </div>
        </div>

         <div class="row align-items-top mt-4">
          <div class="col-lg-2 col-12">
            <label>Description </label>
          </div>
          <div class="col-lg-10 col-12">
            <textarea class="form-control" v-model="params.description"></textarea>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <hr>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button class="btn btn-primary">
                Submit
              </button>
            </div>
          </div>
        </div>
      </form>
    </card-form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import CardForm from '../../../components/CardForm.vue'
import { mapGetters } from 'vuex'
import Select2 from '../../../components/Select2.vue'

export default {
  components: {
    CardForm,
    Select2
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {
   
    return {
      params: {
        status:'draft',
      },
      image: null,
      previewImg:'/images/default-user.png'
    }
  },

  computed: {
    ...mapGetters({
      user: 'user'
    }),
    hasID () {
      return !isNaN(this.$route.params.id)
    },
    title () {
      if (!isNaN(this.$route.params.id)) {
        return 'Edit'
      } else {
        return 'Create'
      }
    },
  },

  async created () {
    if (!isNaN(this.$route.params.id)) {
      this.getData()
    }
  },

  methods: {
    async getData () {
      await axios.get(`/api/v1/data/edit/${this.$route.params.id}`).then(res => {
        this.params = res.data.data
        if(res.data.data.photo){
          this.previewImg = res.data.data.photo
        }
        this.params.photo = null
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      if (!isNaN(this.$route.params.id)) {
        await axios.post(`/api/v1/data/update`, {...this.params,user_id:this.user.id}).then(res => {
          this.$router.go(-1)
          this.$root.toast('Data updated!','success')
        }).catch(err => {
          this.$root.toast(err.response.data.message,'error')
        })
      } else {
        await axios.post('/api/v1/data/add', {...this.params,user_id:this.user.id}).then(res => {
          this.$router.go(-1)
          this.$root.toast('Data created!','success')
        }).catch(err => {
         this.$root.toast(err.response.data.message,'error')
        })
      }
    },

    changePreview(e){
      const [file] = e.target.files
      if (file) {
        if(file.size > 500000){ //500kb
          this.$root.toast('File cannot more than 500kb!','error')
          $('#fileinput').val(null)
          this.previewImg = '/images/default-user.png'
          this.params.photo = null
          return false
        }
        this.params.photo = file
        this.previewImg = URL.createObjectURL(file)
      }else{
        this.params.photo = null
        this.previewImg = '/images/default-user.png'
      }
    },

    setParam (field, value) {
      this.params[field] = value
    },

    getParam (field) {
      return this.params[field] ? this.params[field] : ''
    }
  }
}
</script>
