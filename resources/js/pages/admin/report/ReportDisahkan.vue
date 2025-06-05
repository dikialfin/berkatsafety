<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
          <div class="d-flex align-items-center">
            <h2 class="mb-0 ">
              Laporan
            </h2>
            
          </div>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
           <div class="btn-group">
              <router-link :to="{name:'report'}"  class="btn btn-lg btn-white text-white bg-primary" aria-current="page">Notaris</router-link>
              <router-link :to="{name:'report'}" class="btn btn-lg btn-white">PPAT</router-link>
            </div>
          </div>
        </div>
      </div>
      <card-page>
        <template #custom-header>
          <div class="card-header" style="background-color:rgb(249, 251, 253)">
            <ul role="tablist" class="nav nav-tabs card-header-tabs">
              <li role="presentation" :class="`nav-item`">
                 <router-link :to="{name:'report.notaris.daftar_akta'}"  class="nav-link" >Daftar Akta</router-link>
              </li>
              <li role="presentation" :class="`nav-item`">
                <router-link :to="{name:'report.notaris.dibukukan'}"  class="nav-link" >Dibukukan</router-link>
              </li>
               <li role="presentation" :class="`nav-item`">
                <router-link :to="{name:'report.notaris.disahkan'}"  class="nav-link" >Disahkan</router-link>
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
                  <option :value="1">Januari</option>
                  <option :value="2">Februari</option>
                  <option :value="3">Maret</option>
                  <option :value="4">April</option>
                  <option :value="5">Mei</option>
                  <option :value="6">Juni</option>
                  <option :value="7">Juli</option>
                  <option :value="8">Agustus</option>
                  <option :value="9">September</option>
                  <option :value="10">Oktober</option>
                  <option :value="11">November</option>
                  <option :value="12">Desember</option>
                </select>
              </div>
              <div class="col-12 col-lg-8 text-end">
                 <download-excel
                  :title="`Download Laporan`"
                  :btnClass="'btn btn-primary'"
                  :url="`/api/v1/notaris/export`"
                  :param="{
                    ...filter,
                     type:'disahkan',
                  }"
                />
              </div>
            </div>
          </div>
          <div class="col-12 mt-3">
            <data-table
              :fields="fields"
              :url="'/api/v1/notaris/data'"
              :param="{
                ...filter,
                type:'disahkan',
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
import moment from 'moment'
import DownloadExcel from '@/js/components/DownloadExcel.vue'

export default {
  metaInfo () {
    return { title: 'role' }
  },

  components: {
    CardPage,
    DataTable,
    Select2,
    DownloadExcel
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
          label: 'Tanggal Surat',
          key: 'evidence_date',
          sortable: true,
          tdAttr:{
            style:'width:10%'
          }
        },
        {
          label: 'Sifat Surat',
          key: 'name',
          sortable: true,
          tdAttr:{
            style:'width:25%'
          }
        },
         {
          label: 'Nama yang Menandatangani dan/atau yang Diwakili/Kuasa',
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
    this.filter.month = moment().format('M')
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

