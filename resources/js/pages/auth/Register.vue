<template>
  <div class="container-fluid mx-0 ps-0 bg-white">
    <div class="row align-items-center mx-0" style="height:100vh;padding:15px">
      <div class="col-12 d-none d-lg-flex col-md-6 col-xl-6 justify-content-center align-items-center" style="height:100%;background:#FDCA4C;border-radius:10px">
        <div class="text-center">
          <img src="/images/img-home.png"  style="width: 70%">
          <!-- <h1 class="text-white text-primary mt-4" style="font-size:20pt">Your one-stop platform for all your membership</h1> -->
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-6  px-lg-7 px-4" >
        <div class="d-flex justify-content-between flex-column " style="min-height:90dvh">
          <div class="d-flex align-items-center justify-content-end gap-3 ">
            <img :src="getLogo()"  style="height:70px">
          </div>
          
          <form method="POST" @submit.prevent="login">
            <div class="text-left mb-5">
                <h1 class="mb-2" style="font-size:30pt">Sign Up</h1>
                <p>Enter your details to create your account</p>
            </div>
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="fullname" class="form-control form-control-lg" placeholder="Enter your fullname..." v-model="data.name" required>
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email..." v-model="data.email" required>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label>Password</label>
                </div>
              </div> 

              <div class="input-group input-group-merge">
                <input :type="show_password?'text':`password`" minlength="6" name="password" id="password" v-model="data.password" class="form-control form-control-appended form-control-lg" value="{{ old('password') }}" placeholder="Enter your password"  required>
                  <button class="btn btn-white" type="button" id="btnpassword" @click="show_password = !show_password">
                      <i class="fe fe-eye" v-if="!show_password"></i>
                      <i class="fe fe-eye-off" v-else></i>
                  </button>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label>Confirm Password</label>
                </div>
              </div> 

              <div class="input-group input-group-merge">
                <input :type="show_password_confirm?'text':`password`" minlength="6" name="password_confirm" id="password_confirm" v-model="data.password_confirm" class="form-control form-control-appended form-control-lg" value="{{ old('password') }}" placeholder="Enter your password"  required>
                  <button class="btn btn-white" type="button"  id="btnpassword" @click="show_password_confirm = !show_password_confirm">
                      <i class="fe fe-eye" v-if="!show_password_confirm"></i>
                      <i class="fe fe-eye-off" v-else></i>
                  </button>
              </div>
            </div>
            <div class="d-flex text-end align-items-center mt-5">
              <template v-if="loading">
                <b-spinner
                  variant="primary"
                  small 
                  class="me-2"
                />
                Loading, please wait..
              </template>
              <template v-else>
                <!-- <button type="button" @click="googleLogin" class="btn btn-white  d-flex align-items-center">
                  <img src="/images/logo_google.png" style="width:30px" />
                </button> -->
                <button class="btn btn-lg fw-bold  btn-primary  px-4" style="width:100%">
                  Sign Up <i class="fe fe-arrow-right ms-3"></i>
                </button>
              </template>
            </div>
            <div class="text-center mt-3">
              <h4 class="mb-0 fw-bold">Already have an account? <router-link :to="{name:'login'}" class="text-primary">Sign In instead</router-link></h4>
            </div>
            
          </form>

          <div class="text-center">
            <!-- <p class="mb-0 text-gray">HeyJen &copy 2024. Powered by <a href="https://heyhi.sg" target="_blank">HeyHi</a>.</p> -->
          </div>
        </div>
      </div> <!-- / .row -->
    </div>
  </div>
</template>

<script>
import { decodeCredential,googleTokenLogin } from 'vue3-google-login'
import axios from 'axios'

export default {
  data: () => {
    return {
      errors: null,
      show_password:false,
      show_password_confirm:false,
      data: {
        email: null,
        password: null,
        remember: null,
      },
      loading:false,
      is_saas: false,
      saas_setting:{}
    }
  },
  async beforeCreate(){
    var currentURL = window.location.host;
    await axios.post('/api/v1/check-saas',{url:currentURL}).then(res => {
      if(res.data.data){
        this.is_saas = true
        this.saas_setting = res.data.data
      }
    })
  },
  methods: {
     getLogo(){
      let logo = '/images/logo-home.png'
      if(this.is_saas){
        logo = this.saas_setting.website_logo
      }
      return logo
    },
    googleLogin(){
      googleTokenLogin().then(async (response) => {
        await this.verifyCode(response.access_token)
      }).catch(err => {
        console.log(err)
      })
    },
    async verifyCode(token){
      this.loading = true
      axios.post('/api/v1/login-google', {token:token})
        .then((response) => {
          this.loading = false
          this.$root.toast('Login Success','success')
          this.$store.dispatch('set_token',response.data.token)
          this.$store.dispatch('set_dept',response.data.user.role.department_id?response.data.user.role.department_id:1)

          this.$store.dispatch('attempt_user')
            .then((response) => {
              if(response?.status === 200) {
                this.$router.replace({name: 'dashboard'})
              }
            })
            .catch((error) => {
              this.errors = error.response.data
              this.$root.toast(error.response.data.message,'error')
            })
        })
        .catch((error) => {
          this.loading = false
          this.errors = error.response.data
          this.$root.toast(error.response.data.message,'error')
        })
      return response.data
    },
    callback(response){
      console.log(response)
       const userData = decodeCredential(response.credential)
      console.log("Handle the userData", userData)
    },
    login() {
      this.errors = null
      this.loading = true
        axios.post('/api/v1/register', {...this.data,company_id:this.saas_setting.company_id})
        .then((response) => {
          this.loading = false
          this.$root.toast('Register Success','success')
          this.$store.dispatch('set_token',response.data.token)
          // this.$store.dispatch('set_dept',response.data.user.role.department_id?response.data.user.role.department_id:1)

          this.$store.dispatch('attempt_user')
            .then((response) => {
              if(response?.status === 200) {
                this.$router.push({
                  name: 'heyjen-learn',
                  params:{slug:this.saas_setting.adaptive_feedback.slug}
                })
              }
            })
            .catch((error) => {
              console.log(error)
              this.errors = error.response.data
              this.$root.toast(error.response.data.message,'error')
            })
        })
        .catch((error) => {
          this.loading = false
          this.errors = error.response.data
          this.$root.toast(error.response.data.message,'error')
        })
    }
  }
}
</script>
<style scoped>
.form-control{
  height: 60px;
  font-size: 18px;
}
.btn{
  height: 60px;
  font-size: 20px;
}
</style>
