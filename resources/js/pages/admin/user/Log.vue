<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
          <h2 class="mb-0 fw-bold">
            User Activity Log
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
        </div>
      </div>
      <card-page>
        <div class="row">
          <div class="col-12">
            <data-table
              :fields="fields"
              :url="'/api/v1/user/log'"
              :param="{
                ...filter,
                department_id:user.role_id == 1?null:department
              }"
              :refresh="refresh"
              :filterable="false"
            >
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
import moment from 'moment'
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
    
    if(this.user.role_id == 1){
      this.fields = [
         {
          label: 'Date',
          key: 'created_at',
          tdAttr: {
            style: 'width: 15%'
          },
          formatter:function(val,row,item){
            return moment(val).locale('id').format('DD MMMM YYYY HH:mm')
          }
        },
        {
          label: 'User',
          key: 'user.name',
          tdAttr: {
            style: 'width: 15%'
          },
        },
        {
          label: 'Log',
          key: 'log',
        },
        {
          label: 'ip',
          key: 'ip'
        }
      ]
    }else{
      this.fields = [
       {
          label: 'Date',
          key: 'created_at',
          tdAttr: {
            style: 'width: 15%'
          },
          formatter:function(val,row,item){
            return moment(val).locale('id').format('DD MMMM YYYY HH:mm')
          }
        },
        {
          label: 'User',
          key: 'user.name',
          tdAttr: {
            style: 'width: 15%'
          },
        },
        {
          label: 'Log',
          key: 'log',
        },
    ]
    }
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
