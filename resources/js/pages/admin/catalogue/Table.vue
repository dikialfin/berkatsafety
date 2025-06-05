<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-list bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Catalogue
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
            <template v-if="user.permission.includes('content.create') || [1].includes(user.role_id)">
              <router-link :to="{name:'admin.catalogue.add'}">
                <button class="btn btn-rounded btn-primary">
                  Create
                </button>
              </router-link>
            </template>
          </div>
        </div>
      </div>
      <card-page>
        <div class="row">
          <div class="col-12">
            <template v-if="user.permission.includes('content.view') || [1].includes(user.role_id)">
              <data-table
                :fields="fields"
                :url="'/api/v1/catalogue/data'"
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
                </template>

                <template #action="action">
                  <template v-if="action.action.item.deleted_at == null">
                    <template v-if="user.permission.includes('content.update') || [1].includes(user.role_id)">
                      <b-button class="btn btn-white me-3" @click="edit(action)" ><i class="fe fe-edit"></i></b-button>
                    </template>
                    <template v-if="user.permission.includes('content.delete') || [1].includes(user.role_id)">
                      <b-button class="btn btn-danger" @click="deleteData(action)" ><i class="fe fe-trash"></i></b-button>
                    </template>
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
            </template>
            <template v-else>
              <h1 class="mb-0 text-center text-muted">No Permission</h1>
            </template>
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
          tdAttr: {
            style: 'width: 25%'
          },
          formatter:function(val,row,item){
            return `
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <img src="${item.image?item.image : '/images/default-img.png'}" class="avatar-img mr-1" style="width:100px;height:auto">
                </div>
                <div>
                  <p class="mb-0 fw-bold"> ${item.title}</p>
                </div>
              </div>
            `
          }
        },
        {
          label: 'Description ID',
          key: 'description_id',
          tdAttr: {
            style: 'width: 25%'
          }
        },
        {
          label: 'Description EN',
          key: 'description_en',
          tdAttr: {
            style: 'width: 25%'
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
      this.$router.push({ name: 'admin.catalogue.edit', params: { id: data.action.item.id } })
    },
    deleteData (data) {
      this.$store.dispatch('set_delete_data',{
        url:`/api/v1/catalogue/delete/${data.action.item.id}`
      })
      $('#delete-modal').modal('show');
    },
    restoreData (data) {
      this.$store.dispatch('set_restore_data',{
        url:`/api/v1/catalogue/restore/${data.action.item.id}`
      })
      $('#restore-modal').modal('show');
    },
  }
}
</script>
