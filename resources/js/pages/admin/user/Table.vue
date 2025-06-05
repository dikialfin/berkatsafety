<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-users bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Users
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
            <router-link :to="{name:'admin.user.add'}">
              <button class="btn btn-rounded btn-primary">
                Create
              </button>
            </router-link>
          </div>
        </div>
      </div>
      <card-page>
        <div class="row">
          <div class="col-12">
            <data-table
              :fields="fields"
              :url="'/api/v1/user/data'"
              :param="{
                ...filter,
              }"
              :refresh="refresh"
              :filterable="true"
            >
             <template #user_status="action">
                <template v-if="action.action.item.status == 0">
                  <p class="text-warning mb-0 fw-bold">Unverified</p>
                  <button class="btn btn-sm btn-white" @click="openResend(action.action.item.id)">Resend Email</button>
                </template>
                 <template v-else>
                  <p class="text-success label-success mb-0">Verified</p>
                </template>
                <!-- <div class="form-switch">
                    <input type="checkbox" class="form-check-input" @click="(e) => changeStatus(e,action)" :checked="action.action.item.status" :id="`switch${action.action.item.id}`"/>
                    <label class="custom-control-label" :for="`switch${action.action.item.id}`"></label>
                </div> -->
              </template>

              <template #action="action">
                <template v-if="action.action.item.deleted_at == null">
                  <b-button class="btn btn-white me-3" @click="edit(action)" ><i class="fe fe-edit"></i></b-button>
                  <b-button class="btn btn-danger" v-if="action.action.item.id != 1"  @click="deleteData(action)" ><i class="fe fe-trash"></i></b-button>
                </template>
                <template v-else>
                  <b-button class="btn btn-warning"  @click="restoreData(action)" ><i class="fe fe-refresh-cw"></i></b-button>
                </template>
              </template>

               <template #filter>
                <div class="row">
                  <div class="col-12">
                    <label>Status</label>
                    <select class="form-control" v-model="filter.data_status">
                        <option :value="'active'">Active</option>
                        <option :value="'archived'">Archive</option>
                    </select>
                  </div>
                </div>
              </template>
            </data-table>
          </div>
        </div>
      </card-page>
    </div>
    <div class="modal fade" id="resend-modal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(1px);">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-card card" >
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col text-center" style="margin-top:30px;margin-bottom:30px">
                    <h2 class="card-header-title" id="exampleModalCenterTitle">
                      Are you sure to resend the email verification?
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
                    <button type="button" :disabled="loadingResend" @click="resend" class="btn btn-primary waves-effect waves-light">
                      <b-spinner
                        v-if="loadingResend"
                        small
                      />
                      Resend
                    </button>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CardPage from '@/js/components/CardPage.vue'
import DataTable from '@/js/components/DataTable.vue'
import Select2 from '@/js/components/Select2.vue'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  metaInfo () {
    return { title: 'user' }
  },

  components: {
    CardPage,
    DataTable,
    Select2
  },

  props: {
    multiNav: {
      type: Array,
      default: () => []
    }
  },

  watch: {
    filter: {
      handler(val){
        this.refresh += 1
      },
      deep: true
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
  },

  data () {
    return {
      fields: [
        {
          label: 'Name',
          key: 'name',
          sortable: true,
          formatter:function(val,row,item){
            return `
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <img src="${item.photo?item.photo : '/images/default-user.png'}" class="avatar-img rounded-circle profile-photo mr-1">
                </div>
                <div>
                  <p class="mb-0 fw-bold"> ${item.name}</p>
                  <p class="mb-0 "> ${item.email}</p>
                </div>
              </div>
            `
          }
        },
        {
          label: 'Role',
          key: 'role.name',
          tdAttr: {
            style: 'width: 15%'
          }
        },
        {
          key: 'action',
          tdAttr: {
            style: 'width: 15%'
          }
        }
      ],
      refresh: 0,
      loadingExport: false,
      loadingResend: false,
      selectedResend: 0,
      dataexport:{
        triwulan1:[],
        triwulan2:[],
        triwulan3:[],
        triwulan4:[]
      },
      filter:{
        data_status:'active',
        role_id:0
      }
    }
  },

  mounted(){
    const em = this
    $('#delete-modal').on('hidden.bs.modal',function(){
      em.refresh += 1
    })
    $('#restore-modal').on('hidden.bs.modal',function(){
      em.refresh += 1
    })
    $('#status-modal').on('hidden.bs.modal',function(){
      em.refresh += 1
    })
  },

  methods: {
    edit (data) {
      this.$router.push({ name: 'admin.user.edit', params: { id: data.action.item.id } })
    },
    deleteData (data) {
      this.$store.dispatch('set_delete_data',{
        url:`/api/v1/user/delete/${data.action.item.id}`
      })
      $('#delete-modal').modal('show');
    },
    restoreData (data) {
      this.$store.dispatch('set_restore_data',{
        url:`/api/v1/user/restore/${data.action.item.id}`
      })
      $('#restore-modal').modal('show');
    },
    changeStatus (e,data){
      e.preventDefault()
      this.$store.dispatch('set_status_data',{
        url:`/api/v1/user/${data.action.item.status == 0?'active':'deactive'}/${data.action.item.id}`
      })
      $('#status-modal').modal('show');
    },
    openResend(id){
      this.selectedResend = id
      $('#resend-modal').modal('show')
    },
    async resend(){
      this.loadingResend = true
      try {
        const response = await axios.get(`/api/v1/user/resend-verification/${this.selectedResend}`)
        this.$root.toast('Resend success!','success')
        this.loadingResend = false
        $('#resend-modal').modal('hide')
      } catch (error) {
        this.$root.toast('Resend failed!','error')
        this.loadingResend = false
      }
    }
  }
}
</script>
