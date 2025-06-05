<template>
  <div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(1px);">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-card card" >
          <div class="card-header">
            <div class="row align-items-center">
                <div class="col text-center" style="margin-top:30px;margin-bottom:30px">
                  <h2 class="card-header-title" id="exampleModalCenterTitle">
                    Are you sure to change the user status?
                  </h2>
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-transparent" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button> 
                </div>
            </div> 
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-md-12 text-center">
                  <button class="btn btn-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" :disabled="loading" @click="submit" class="btn btn-warning waves-effect waves-light">
                    <b-spinner
                      v-if="loading"
                      small
                    />
                    Submit
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
import axios from 'axios'
export default {
  name: 'ModalStatus',
  data(){
    return{
      loading: false
    }
  },
  methods:{
    submit(){
      this.loading = true
      axios.get(`${this.$store.getters.status_data.status_url}`).then(res => {
        this.loading = false
        this.$root.toast('Data changed!','success')
        $('#status-modal').modal('hide');
      }).catch(error => {
        this.loading = false
        this.$root.toast('Oops something went wrong!','error')
      })
    }
  }
}
</script>
