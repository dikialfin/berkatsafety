<template>
  <div class="container-fluid mx-0 ps-0 bg-white">
    <div class="row align-items-center mx-0" style="height:100vh;padding:15px">
      <div class="col-12 d-none d-lg-flex col-md-6 col-xl-6 bg-primary justify-content-center align-items-center" style="
        height:100%;border-radius:10px;
        background-size: cover;
        /* background-image:url('/images/login-bg.jpg'), linear-gradient(#eb01a5, #d13531); */
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgb(5 44 122)), url(/images/login-bg.jpg) center center / cover no-repeat;
      ">
        <div class="text-center">
          <img src="/images/logo-white.png"  style="height: 100px">
          <h1 class="text-white text-primary mt-4">Manage your data and content in here</h1>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-6  px-lg-7 px-4" >
        <div class="d-flex justify-content-between flex-column " style="min-height:90dvh">
          <div class="d-flex align-items-center gap-3 justify-content-end">
            <img :src="getLogo()"  style="height:70px">
          </div>
          
          <form method="POST" @submit.prevent="login">
            <div class="text-left mb-5">
              <h1 class="mb-2" style="font-size:30pt">Welcome!</h1>
              <p>Please login to continue</p>
          </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control form-control-lg" placeholder="Email..." v-model="data.email" required>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label>Password</label>
                </div>
              </div> 

              <div class="input-group input-group-merge">
                <input :type="show_password?'text':`password`" name="password" id="password" v-model="data.password" class="form-control form-control-appended form-control-lg" value="{{ old('password') }}" placeholder="Enter your password"  required>
                  <button class="btn btn-white" type="button" id="btnpassword" @click="show_password = !show_password">
                      <i class="fe fe-eye" v-if="!show_password"></i>
                      <i class="fe fe-eye-off" v-else></i>
                  </button>
              </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
             <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Remember Me
                </label>
              </div>
              <router-link to="/forgot-password">
                Forgot password
              </router-link>
            </div>
            <div class="d-flex text-end align-items-center mt-5">
              <template v-if="loading">
                <b-spinner
                  variant="primary"
                  small 
                  class="me-2"
                />
                Login, please wait..
              </template>
              <template v-else>
                <!-- <button type="button" @click="googleLogin" class="btn btn-white  d-flex align-items-center">
                  <img src="/images/logo_google.png" style="width:30px" />
                </button> -->
                <button class="btn btn-lg fw-bold  btn-primary  px-4" style="width:100%">
                  Sign In <i class="fe fe-arrow-right ms-3"></i>
                </button>
              </template>
            </div>
            <div class="text-center mt-3" v-if="is_saas">
              <h4 class="mb-0 fw-bold">Doesn't have an account? <router-link to="/register" class="text-primary">Sign Up here</router-link></h4>
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
        axios.post('/api/v1/login', this.data)
        .then((response) => {
          this.loading = false
          this.$root.toast('Login Success','success')
          this.$store.dispatch('set_token',response.data.token)
          this.$store.dispatch('set_dept',response.data.user.role.department_id?response.data.user.role.department_id:1)
          const role_id = response.data.user.role_id
          this.$store.dispatch('attempt_user')
            .then((response) => {
              if(response?.status === 200) {
                if(role_id == 3){
                  this.$router.push({
                    name:'heyjen-learn',
                    params:{
                      slug:this.saas_setting.adaptive_feedback.slug
                    }
                  })
                }else{
                  this.$router.replace({name: 'dashboard'})
                }
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
