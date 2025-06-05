<template>
  <div class="card">
    <div v-if="title" class="card-header" :style="{
      background : backgroundHeader
    }"
    >
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <button class="btn btn-white me-3" type="button" @click="exitForm">
          <i class="fe fe-chevron-left" />
        </button>
        {{ title }}
      </h4>
      <slot name="header-right"/>
    </div>
    </div>
    <div v-if="multiNav.length != 0" class="card-header">
      <ul role="tablist" class="nav nav-tabs card-header-tabs">
        <li v-for="(nav,index) in multiNav" :key="index" role="presentation" class="nav-item">
          <router-link class="nav-link" :to="{name: nav.url}">
            {{ nav.name }}
          </router-link>
        </li>
      </ul>
    </div>

    <div class="card-body" :style="{
      background : backgroundBody
    }"
    >
      <slot />
    </div>
  </div>
   <div class="modal fade" id="change-alert-modal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(1px);">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <h3>Are you sure to exit?</h3>
              <p>Your changes will be not saved.</p>
              <div class="text-end">
                <button type="button" class="btn btn-white me-2" data-bs-dismiss="modal">Close</button>
                <button @click="exit" class="btn btn-danger" type="button">Exit</button>
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
  name: 'CardForm',

  props: {
    title: {
      type: String,
      default: null
    },
    backgroundHeader: {
      type: String,
      default: '#f9fbfd'
    },
    backgroundBody: {
      type: String,
      default: '#ffffff'
    },
    multiNav: {
      type: Array,
      default () {
        return []
      }
    },
    isChange: {
      type: Boolean,
      default: false
    }
  },

  methods:{
    exitForm(){
      if(this.isChange){
        $('#change-alert-modal').modal('show')
      }else{
        this.exit()
      }
    },
    exit(){
      $('.modal').modal('hide')
        this.$router.go(-1)
    }
  }
}
</script>
