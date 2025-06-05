<template>
  <div class="container-fluid ps-0 mx-0 bg-white">
    <div class="row align-items-center mx-0" style="height:100vh">
      <div class="col-12 d-none d-lg-flex col-xl-6 justify-content-center align-items-center" style="height:100%;background:#E5EEFF">
        <div class="text-center">
          <img src="/images/img-home.png"  style="width: 70%">
          <h1 class="text-dark text-primary mt-4" style="font-size:20pt">Your one-stop platform for all your membership</h1>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-6 px-lg-7 px-4">
          <img src="/images/logo-home.png"  style="position:absolute;top:0;right:50px;width:200px;padding-top:50px">
          <div class="text-center" v-if="loadingVerify">
            <b-spinner
            variant="primary"
            large
            class="mb-2"
            />
            <h2>Loading, please wait...</h2>
          </div>
          <template v-else>
            <template v-if="!isEligible">
              <div class="text-center">
                  <img src="/images/icon-oops.png" style="width: 200px">
                  <div class="text-center mt-3">
                    <h2 style="line-height:1.5em">Sorry, this link is already expired. <br>Please contact our admin to get more information.</h2>
                    <button class="btn btn-lg btn-primary px-4" @click="$router.push({path:'/'})">
                      Back to Home
                    </button>
                  </div>
              </div>
            </template>
            <template v-if="isEligible && !isFinish">
              <div class="text-left mb-5">
                  <!-- <img src="/images/logo.png" style="width: 200px"> -->
                  <h1 class="mb-0" style="font-size:28pt">Verify Account</h1>
                  <p>Update your account's password to verify</p>
              </div>
              <form method="POST" @submit.prevent="submit">
                <!-- <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="Email..." v-model="data.email" required>
                </div> -->
                <div class="form-group ">
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
                  
                  <div class="input-group input-group-merge mt-2">
                    <input :type="show_password_confirmation?'text':`password`" name="password" id="password" v-model="data.password_confirmation" class="form-control form-control-appended form-control-lg" value="{{ old('password') }}" placeholder="Re-enter your password"  required>
                  
                      <button class="btn btn-white" type="button" id="btnpassword" @click="show_password_confirmation = !show_password_confirmation">
                          <i class="fe fe-eye" v-if="!show_password_confirmation"></i>
                          <i class="fe fe-eye-off" v-else></i>
                      </button>
                  </div>
                </div>
                  <!-- <password v-model="data.password" :strength-meter-only="true"/> -->
                <transition name="hint" appear>
                  <div class='hints'>
                    <h3>Password Requirement:</h3>
                    <div :class="`d-flex mb-0 ${passwordValidation.errors.includes(error.message)? '' : 'text-success'}`" v-for='error in rules' :key="error">
                      <i class="fe fe-check-circle me-2"></i>
                      <p class="mb-0">{{error.message}}</p>
                    </div>
                  </div>
                </transition>
                <div class="d-flex justify-content-between align-items-center mt-5">
                  <router-link to="/" class="mb-0">
                    Back to login
                  </router-link>
                  <button class="btn btn-lg fw-bold  btn-primary  px-4" :disabled="loading">
                    <b-spinner
                      v-if="loading"
                      variant="white"
                      small 
                      class="me-2"
                    />
                    Submit
                  </button>
                </div>
                
              </form>
            </template>
             <template v-if="isEligible && isFinish">
              <div class="text-center">
                  <img src="/images/icon-success.png"  style="width: 200px">
     
                  <div class="text-center mt-3">
                    <h2 style="line-height:1.5em">Congratulation! Your account already verified.</h2>
                    <button class="btn btn-lg btn-primary px-4" @click="$router.push({path:'/'})">
                      Back to Home
                    </button>
                  </div>
              </div>
            </template>
          </template>
      </div> <!-- / .row -->
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data: () => {
    return {
      errors: null,
      data: {
        email: null,
        password: '',
        password_confirmation: ''
      },
      show_password:false,
      show_password_confirmation:false,
      loadingVerify:false,
      isEligible: false,
      isFinish: false,
      loading:false,
      rules: [
				{ message:'One lowercase letter required.', regex:/[a-z]+/ },
				{ message:"One uppercase letter required.",  regex:/[A-Z]+/ },
				{ message:"8 characters minimum.", regex:/.{6,}/ },
				{ message:"One number required.", regex:/[0-9]+/ }
			],
    }
  },
  mounted(){
    this.validateToken();
  },
  computed:{
    passwordValidation () {
			let errors = []
			for (let condition of this.rules) {
				if (!condition.regex.test(this.data.password)) {
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
  methods: {
    validateToken(){
      this.loadingVerify = true
      axios.get(`/api/v1/check-verify-token/${this.$route.params.token}`)
      .then(res => {
        console.log(res)
        this.data.token = this.$route.params.token
        this.loadingVerify = false
        this.isEligible = true
      })
      .catch(err => {
        this.loadingVerify = false 
        this.isEligible = false 
      })
    },
    submit() {
      if(this.passwordValidation.valid == false){
        this.$root.toast('Password not valid','error')
        return false
      }
      this.errors = null
      axios.post('/api/v1/verify', this.data)
        .then((response) => {
          this.$root.toast('Success','success')
          this.loading = false
          this.isFinish = true
        })
        .catch((error) => {
          this.loading = false
          let err = ''
          err += error.response.data.message 
          if(error.response.data.errors){
            let obj = error.response.data.errors
            err += '<ul>'
            Object.keys(obj).forEach(key => {
              let errList = obj[key]
              errList.map(val => {
                err += `<li>${val}</li>`
              })
            });
            err += '</ul>'
          }
          
          this.$root.toast(err,'error')
        })
    }
  }
}
</script>
