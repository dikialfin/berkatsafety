<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-list bg-primary text-white p-3 me-2" style="border-radius:12px"></i> About Us
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
                :url="'/api/v1/about/data'"
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
                  <template v-if="user.permission.includes('content.update') || [1].includes(user.role_id)">
                    <b-button class="btn btn-white me-3" @click="edit(action)" ><i class="fe fe-edit"></i></b-button>
                  </template>
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
            return item.name
          }
        },
        {
          label: 'Slug',
          key: 'slug',
          tdAttr: {
            style: 'width: 25%'
          }
        },
        {
          label: 'Meta Description ID',
          key: 'meta_description_id',
          tdAttr: {
            style: 'width: 25%'
          }
        },
        {
          label: 'Meta Description EN',
          key: 'meta_description_en',
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
    console.log(this.user.permission, 'asi');
  },

  methods: {
    edit (data) {
      this.$router.push({ name: 'admin.about.edit', params: { id: data.action.item.id } })
    }
  }
}
</script>
