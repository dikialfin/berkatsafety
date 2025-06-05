<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
          <h2 class="mb-0">
            <i class="fe fe-list bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Role
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end">
            <router-link :to="{name:'admin.role.add'}">
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
              :url="'/api/v1/role/data'"
              :param="{
                ...filter,
                department_id: department
              }"
              :refresh="refresh"
              :filterable="true"
            >
             <template #role_status="action">
                <div class="form-switch">
                    <input type="checkbox" class="form-check-input" @click="(e) => changeStatus(e,action)" :checked="action.action.item.status" :id="`switch${action.action.item.id}`"/>
                    <label class="custom-control-label" :for="`switch${action.action.item.id}`"></label>
                </div>
              </template>

              <template #action="action">
                <template v-if="action.action.item.deleted_at == null">
                  <b-button class="btn btn-white me-3" @click="edit(action)" v-if="user.permission.includes('role.update')"><i class="fe fe-edit"></i></b-button>
                  <b-button class="btn btn-danger" v-if="action.action.item.is_default == 0 && user.permission.includes('role.delete') "  @click="deleteData(action)" ><i class="fe fe-trash"></i></b-button>
                </template>
                <template v-else>
                  <b-button class="btn btn-warning"  @click="restoreData(action)" v-if="user.permission.includes('role.delete')"><i class="fe fe-refresh-cw"></i></b-button>
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
          label: 'Name',
          key: 'name',
          sortable: true,
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
      this.$router.push({ name: 'admin.role.edit', params: { id: data.action.item.id } })
    },
    deleteData (data) {
      this.$store.dispatch('set_delete_data',{
        url:`/api/v1/role/delete/${data.action.item.id}`
      })
      $('#delete-modal').modal('show');
    },
    restoreData (data) {
      this.$store.dispatch('set_restore_data',{
        url:`/api/v1/role/restore/${data.action.item.id}`
      })
      $('#restore-modal').modal('show');
    },
    changeStatus (e,data){
      e.preventDefault()
      this.$store.dispatch('set_status_data',{
        url:`/api/v1/role/${data.action.item.status == 0?'active':'deactive'}/${data.action.item.id}`
      })
      $('#status-modal').modal('show');
    } 
  }
}
</script>
