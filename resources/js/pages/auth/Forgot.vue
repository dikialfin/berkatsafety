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
          <div class="text-left mb-5">
              <!-- <img src="/images/logo.png" style="width: 200px"> -->
              <h1 class="mb-0" style="font-size:28pt">Forgot Password</h1>
              <p>Please input your email</p>
          </div>
          <form method="POST" @submit.prevent="submit">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control form-control-lg" placeholder="Email..." v-model="data.email" required>
            </div>
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
      </div> <!-- / .row -->
    </div>
  </div>
</template>

<script>

export default {
  data: () => {
    return {
      errors: null,
      data: {
        email: null,
      },
      loading:false
    }
  },
  methods: {
    submit() {
      this.errors = null
      this.loading = true
        axios.post('/api/v1/forgot', this.data)
        .then((response) => {
          this.loading = false
          this.$root.toast('Request sent','success')
        })
        .catch((error) => {
          this.loading = false
          this.$root.toast('Request sent','success')
        })
    }
  }
}
</script>
