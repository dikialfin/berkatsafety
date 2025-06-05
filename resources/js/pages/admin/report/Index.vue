<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
          <div class="d-flex align-items-center">
            <div class="btn-group">
              <router-link :to="{name:'report'}"  class="btn btn-lg btn-white text-white bg-primary" aria-current="page">Notaris</router-link>
              <router-link :to="{name:'report'}" class="btn btn-lg btn-white">PPAT</router-link>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
            <router-link :to="{name:'admin.notaris.add',params:{type:'daftar_akta'}}">
              <button class="btn btn-rounded btn-primary">
                Create
              </button>
            </router-link>
          </div>
        </div>
      </div>
      <card-page>
        <template #custom-header>
          <div class="card-header" style="background-color:rgb(249, 251, 253)">
            <ul role="tablist" class="nav nav-tabs card-header-tabs">
              <li role="presentation" :class="`nav-item`">
                 <router-link :to="{name:'report'}"  class="nav-link" >Daftar Akta</router-link>
              </li>
              <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.status == 'have_ritase_smu' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.status = 'have_ritase_smu'">
                  Complete
                </a>
              </li>
               <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.status == 'all' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.status = 'all'">
                  All Shipment
                </a>
              </li>
            </ul>
          </div>
        </template>
        <div class="row">
          <div class="col-12">
            <div class="row align-items-center mt-2">
              <div class="col-lg-4 d-flex gap-3 col-12 align-items-center">
                <label style="width:100px">Bulan</label>
                <select class="form-select" v-model="filter.month">
                  <option value="daftar_akta">Daftar Akta</option>
                  <option value="dibukukan">Dibukukan</option>
                  <option value="disahkan">Disahkan</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-12 mt-3">
            <data-table
              :fields="fields"
              :url="'/api/v1/notaris/data'"
              :param="{
                ...filter,
                type:'daftar_akta',
                department_id: department
              }"
              :refresh="refresh"
              :filterable="false"
              :searchable="false"
            >
             <template #role_status="action">
                <div class="form-switch">
                    <input type="checkbox" class="form-check-input" @click="(e) => changeStatus(e,action)" :checked="action.action.item.status" :id="`switch${action.action.item.id}`"/>
                    <label class="custom-control-label" :for="`switch${action.action.item.id}`"></label>
                </div>
              </template>

              <template #action="action">
                <template v-if="action.action.item.deleted_at == null">
                  <b-button class="btn btn-white me-3" @click="edit(action)"><i class="fe fe-edit"></i></b-button>
                  <b-button class="btn btn-danger"  @click="deleteData(action)" ><i class="fe fe-trash"></i></b-button>
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
    return { title: 'role' }
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

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
  },

  watch: {
    filter: {
      handler(val){
        this.refresh += 1
      },
      deep: true
    }
  },

  data () {
    return {
      fields: [
         {
          label: 'Nomor Urut',
          key: 'queue_no',
          sortable: true,
          tdAttr:{
            style:'width:10%'
          }
        },
         {
          label: 'Nomor Bulanan/Akta',
          key: 'evidence_no',
          sortable: true,
          tdAttr:{
            style:'width:10%'
          }
        },
        {
          label: 'Tanggal Akta',
          key: 'evidence_date',
          sortable: true,
          tdAttr:{
            style:'width:10%'
          }
        },
        {
          label: 'Sifat Akta',
          key: 'name',
          sortable: true,
          tdAttr:{
            style:'width:25%'
          }
        },
         {
          label: 'Nama Penghadap dan/atau yang diwakili/kuasa',
          key: 'customer',
          sortable: true,
          formatter:function(val){
            let customer = ''
            val.map((cust,idx) => {
              customer += `${idx+1}. ${cust.name} - ${cust.job} <br>`
            })
            return customer
          },
          tdAttr:{
            style:'width:25%'
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
      this.$router.push({ name: 'admin.notaris.edit', params: { id: data.action.item.id } })
    },
    deleteData (data) {
      this.$store.dispatch('set_delete_data',{
        url:`/api/v1/notaris/delete/${data.action.item.id}`
      })
      $('#delete-modal').modal('show');
    },
    restoreData (data) {
      this.$store.dispatch('set_restore_data',{
        url:`/api/v1/notaris/restore/${data.action.item.id}`
      })
      $('#restore-modal').modal('show');
    },
    changeStatus (e,data){
      e.preventDefault()
      this.$store.dispatch('set_status_data',{
        url:`/api/v1/notaris/${data.action.item.status == 0?'active':'deactive'}/${data.action.item.id}`
      })
      $('#status-modal').modal('show');
    } 
  }
}
</script>
<style scoped>
.card-header-tabs .nav-link.active{
  border-bottom-color: transparent;
}
.nav-tabs .nav-link{
  padding: 1rem;
}

.nav-tabs .nav-item{
  margin-left: 0;
  margin-right: 0;
}
</style>

