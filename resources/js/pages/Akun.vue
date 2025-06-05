<template>
  <form @submit.prevent="save">
    <div class="row">
      <div class="col-12">
        <card-page :title="'Account'">
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
            <div class="col-lg-10 col-12">
              <input class="form-control" v-model="params.name" required>
            </div>
          </div>

          <div class="row align-items-center mt-4">
            <div class="col-lg-2 col-12">
              <label>Email <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input type="email" class="form-control" v-model="params.email" required>
            </div>
            <div class="col-lg-2 col-12">
              <label>Phone <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input type="text" class="form-control" @keydown.space.prevent v-model="params.phone" required>
            </div>
          </div>
        </card-page>
        <card-page :title="'Account Security'">
          <div class="row align-items-start" v-if="!params.is_sso">
            <div class="col-12">
              <p class="text-info"><i class="fe fe-info me-2"></i> if want to change your account's password</p>
            </div>
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
        </card-page>
      </div>
      
    </div>
    <div class="row pb-4">
      <div class="col-12">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button class="btn btn-primary" :disabled="loading">
            Submit
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import axios from 'axios'
import CardForm from '../components/CardForm.vue'
import { mapGetters } from 'vuex'
import Select2 from '../components/Select2.vue'
import CardPage from '../components/CardPage.vue'

export default {
  components: {
    CardForm,
    Select2,
    CardPage
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {
   
    return {
      params: {
        // role_id:3
        password:null
      },
      image: null,
      previewImg:'/images/default-user.png',
      previewLogo: '/images/default-img.png',
      rules: [
				{ message:'One lowercase letter required.', regex:/[a-z]+/ },
				{ message:"One uppercase letter required.",  regex:/[A-Z]+/ },
				{ message:"8 characters minimum.", regex:/.{6,}/ },
				{ message:"One number required.", regex:/[0-9]+/ }
			],
      loading:false
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
    passwordValidation () {
			let errors = []
			for (let condition of this.rules) {
				if (!condition.regex.test(this.params.password)) {
					errors.push(condition.message)
				}
			}
			if (errors.length === 0) {
				return { valid:true, errors }
			} else {
				return { valid:false, errors }
			}
		}
  },

  async mounted () {
    if (!isNaN(this.user.id)) {
      this.getData()
    }
  },

  methods: {
    async getData () {
      await axios.get(`/api/v1/user/account`).then(res => {
        this.params = res.data.data
        this.params.password = ''
        if(res.data.data.photo){
          this.previewImg = res.data.data.photo
        }
        if(res.data.data.company.brand_logo){
          this.previewLogo = res.data.data.company.brand_logo
        }
        this.params.brand_color = res.data.data.company.brand_color
        this.params.photo = null
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      if(this.params.password){
        if(this.passwordValidation.valid == false){
          this.$root.toast('Password not valid','error')
          return false
        }
      }
      const param = new FormData()
      for ( var key in this.params ) {
        if(this.params[key] != null) param.append(key, this.params[key]);
      }
      this.loading = true
      await axios.post(`/api/v1/user/update-account`, param).then(res => {
        this.$root.toast('Data updated!','success')
          this.loading = false
        }).catch(err => {
          this.loading = false
            this.$root.toast(err.response.data.message,'error')
        })
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

    changePreviewLogo(e){
      const [file] = e.target.files
      if (file) {
        if(file.size > 500000){ //500kb
          this.$root.toast('File cannot more than 500kb!','error')
          $('#fileinput-logo').val(null)
          this.previewLogo = '/images/default-img.png'
          this.params.logo = null
          return false
        }
        this.params.logo = file
        this.previewLogo = URL.createObjectURL(file)
      }else{
        this.params.logo = null
        this.previewLogo = '/images/default-img.png'
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
