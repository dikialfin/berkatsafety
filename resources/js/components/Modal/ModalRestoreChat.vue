<template>
  <div class="modal fade" id="restore-chat-modal" tabindex="-1" role="dialog" aria-hidden="true"
    style="backdrop-filter: blur(1px);">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-card card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col text-center" style="margin-top:30px;margin-bottom:30px">

                <!-- Title -->
                <h2 class="card-header-title" id="exampleModalCenterTitle">
                  Do you want to restore your previous chat?
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
                <button class="btn btn-default btn-sm" data-bs-dismiss="modal">No</button>
                <button type="button" :disabled="loading" @click="affirmation"
                  class="btn btn-danger waves-effect waves-light">
                  <b-spinner v-if="loading" small />
                  Yes
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
  name: 'ModalRestoreChat',

  data() {
    return {
      loading: false
    }
  },
  props: {
    onYes: {
      type: Function,
      default: () => { },
    }
  },
  methods: {
    async affirmation() {
      try {
        this.loading = true;
        await this.onYes();

      }
      catch (err) {

      }
      this.loading = false;

    }
  },
}
</script>