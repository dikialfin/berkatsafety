<template>
  <div class="row">
    <div class="col-12">
      <card-form :title="'User'">
        <form @submit.prevent="save">
         <div class="row align-items-center">
          <div class="col-lg-2 col-12">
              <img class="avatar-img rounded-circle" :src="previewImg" style="width:100px;height:100px"/>
          </div>
          <div class="col-lg-10 col-12">
            <label>Photo Profile </label>
            <input class="form-control" type="file" @change="changePreview" id="fileinput" accept="image/*">
          </div>
        </div>
        <div class="row align-items-center mt-4">
          <div class="col-lg-2 col-12">
            <label>Name <span class="text-danger">*</span></label>
          </div>
          <div class="col-lg-4 col-12">
            <input class="form-control" v-model="params.name" required>
          </div>
          <div class="col-lg-2 col-12">
            <label>Email <span class="text-danger">*</span></label>
          </div>
           <div class="col-lg-4 col-12">
            <input type="email" class="form-control" v-model="params.email" required>
          </div>
        </div>

        <div class="row align-items-start mt-4" >
            <div class="col-lg-2 col-12">
              <label>Password </label>
            </div>
            <div class="col-lg-4 col-12">
              <input type="password" class="form-control" v-model="params.password" autocomplete="false">
              <transition name="hint" appear v-if="params.password">
                <div class='hints'>
                  <div :class="`d-flex mb-0 ${passwordValidation.errors.includes(error.message)? '' : 'text-success'}`" v-for='error in rules' :key="error">
                    <i class="fe fe-check-circle me-2"></i>
                    <p class="mb-0">{{error.message}}</p>
                  </div>
                </div>
              </transition>
            </div>
            <div class="col-lg-2 col-12">
              <label>Confirm Password </label>
            </div>
            <div class="col-lg-4 col-12">
              <input type="password" class="form-control" v-model="params.confirm_password" >
            </div>
        </div>

        <div class="row align-items-center mt-4">
          <div class="col-lg-2 col-12">
            <label>Role</label>
          </div>
          <div class="col-lg-4 col-12">
            <select-2
              :api="'/api/v1/role/select'"
              :value="params.role_id"
              :required="true"
              :select="({id,text}) => this.params.role_id = id"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <hr>
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
      params: {},
      loading:false,
      image: null,
      previewImg:'/images/default-user.png'
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
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
      await axios.get(`/api/v1/user/edit/${this.$route.params.id}`).then(res => {
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
      const param = new FormData()
      for ( var key in this.params ) {
        if(this.params[key] != null) param.append(key, this.params[key]);
      }
      this.loading = true
      if (!isNaN(this.$route.params.id)) {
        await axios.post(`/api/v1/user/update`, param).then(res => {
          this.$router.go(-1)
          this.loading = false
          this.$root.toast('Data updated!','success')
        }).catch(err => {
          this.loading = false
          this.$root.toast(err.response.data.message,'error')
        })
      } else {
        await axios.post('/api/v1/user/add', param).then(res => {
          this.$router.go(-1)
          this.loading = false
          this.$root.toast('Data created!','success')
        }).catch(err => {
          this.loading = false
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
