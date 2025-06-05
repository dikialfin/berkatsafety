<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-list bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Contact Us
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
          </div>
        </div>
      </div>
      <card-page>
        <div class="row">
          <div class="col-12">
            <template v-if="user.permission.includes('content.view') || [1].includes(user.role_id)">
              <data-table
                :fields="fields"
                :url="'/api/v1/contact/data'"
                :refresh="refresh"
                :filterable="false"
              >
              </data-table>
            </template>
            <template v-else>
              <h1 class="mb-0 text-center text-muted">No Permission</h1>
            </template>
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
          label: 'Full Name',
          key: 'fullname',
          sortable: true,
          tdAttr: {
            
          }
        },
        {
          label: 'Company',
          key: 'company',
          tdAttr: {
            
          }
        },
        {
          label: 'Job Title',
          key: 'job_title',
          tdAttr: {
            
          }
        },
        {
          label: 'Email',
          key: 'email',
          tdAttr: {
            
          }
        },
        {
          label: 'Message',
          key: 'message',
          tdAttr: {
            
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
    
  },

  methods: {
    
  }
}
</script>
