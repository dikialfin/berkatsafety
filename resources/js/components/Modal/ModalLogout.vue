<template>
  <div class="modal fade" id="logout-modal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(1px);">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-card card" >
          <div class="card-header">
            <div class="row align-items-center">
                <div class="col text-center" style="margin-top:30px;margin-bottom:30px">

                <!-- Title -->
                <h2 class="card-header-title" id="exampleModalCenterTitle">
                    Are you sure to logout?
                </h2>
            
                </div>
                <div class="col-auto">
                <!-- Close -->
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  -->
            
                </div>
            </div> 
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-md-12 text-center">
                      <button class="btn btn-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" :disabled="loading" @click="logout" class="btn btn-danger waves-effect waves-light">
                        <b-spinner
                          v-if="loading"
                          small
                        />
                        Logout
                      </button>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalLogout',

  data(){
    return{
      loading: false
    }
  },
  methods:{
    logout(){
      this.loading = true
      this.$store.dispatch('logout')
        .catch((error) => {
          if(error.response?.status !== 401) {
              console.error(error)
          }
        })
        .finally(() => {
          location.replace('/')
        })
    }
  }
}
</script>
