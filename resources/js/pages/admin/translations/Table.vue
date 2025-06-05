<template>
  <div class="row">
    <div class="col-12">
      <div class="row d-flex align-items-center mt-2 mb-4">
        <div class="col-12 col-lg-6">
           <h2 class="mb-0 ">
            <i class="fe fe-globe bg-primary text-white p-3 me-2" style="border-radius:12px"></i> Translations
          </h2>
        </div>
        <div class="col-12 col-lg-6 mt-lg-0 mt-3">
          <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
            <template v-if="user.permission.includes('content.create') || [1].includes(user.role_id)">
              <button class="btn btn-rounded btn-primary" @click="storeKey" :disabled="loadingSubmit || data.length == 0">
                Save
              </button>
            </template>
          </div>
        </div>
      </div>
      <card-page>
        <template #custom-header>
          <div class="card-header" style="background-color:rgb(249, 251, 253)">
            <ul role="tablist" class="nav nav-tabs card-header-tabs">
              <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.group == 'home' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'home'">
                  Home
                </a>
              </li>
              <li role="presentation" :class="`nav-item`" v-if="user.role_id != 4">
                <a href="#" :class="`${filter.group == 'category' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'category'">
                  Category
                </a>
              </li>
              <li role="presentation" :class="`nav-item`" v-if="user.role_id != 4">
                <a href="#" :class="`${filter.group == 'brands' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'brands'">
                  Brands
                </a>
              </li>
              <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.group == 'catalogue' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'catalogue'">
                  Catalogue
                </a>
              </li>
               <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.group == 'about_us' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'about_us'">
                  About Us
                </a>
              </li>
              <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.group == 'media' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'media'">
                  Media
                </a>
              </li>
               <li role="presentation" :class="`nav-item`">
                <a href="#" :class="`${filter.group == 'contact-us' ? 'active router-link-exact-active': ''} nav-link`" @click="filter.group = 'contact-us'">
                  Contact Us
                </a>
              </li>
            </ul>
          </div>
        </template>
        <div class="row">
          <div class="col-2">
            <h4 class="mb-0">Key</h4>
          </div>
          <div class="col-5">
            <h4 class="mb-0">ID</h4>
          </div>
          <div class="col-5">
            <h4 class="mb-0">EN</h4>
          </div>
          <div class="col-12">
            <hr>
          </div>
        </div>
        <div class="row" v-if="loading">
          <div class="col-12 text-center">
            <b-spinner  variant="primary"/>
          </div>
        </div>
        <template v-else>
          <div class="row mb-4" v-for="(dat,idx) in data" :key="idx">
            <div class="col-2">
              <input class="form-control" v-model="dat.key">
            </div>
            <div class="col-5">
              <textarea class="form-control" v-model="dat.translations.id"></textarea>
            </div>
            <div class="col-5">
              <div class="d-flex align-items-start gap-2">
                <textarea class="form-control"  v-model="dat.translations.en"></textarea>
                <button class="btn btn-sm text-danger" @click="deleteKey(idx)">
                  <i class="fe fe-x"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="row" v-if="data.length == 0">
            <div class="col-12 text-center">
              <h2>No Key Available</h2>
            </div>
          </div>
          <template v-if="user.permission.includes('content.create') || [1].includes(user.role_id)">
            <div class="row">
              <div class="col-12 text-end">
                <button class="btn btn-primary" @click="addKey">
                  Add Key
                </button>
              </div>
            </div>
          </template>
        </template>
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
    'filter.group':function(){
      this.getData()
    },
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
      loading:false,
      loadingSubmit:false,
      data:[],
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
        group:'home',
        data_status:'active',
        role_id:0
      }
    }
  },

  mounted(){
    this.getData()
  },

  methods: {
    getData (){
      this.data = []
      this.loading = true
      axios.get(`/api/v1/translations/data`,{params:{...this.filter}}).then(res => {
        this.loading = false 
        this.data = res.data.data
        console.log(res)
      })
    },
    addKey () {
      this.data.push({
        key:'key',
        translations:{
          id: 'id',
          en: 'en'
        }
      })
    },
    deleteKey (idx) {
      this.data.splice(idx,1)
    },
    storeKey() {
      this.loadingSubmit = true;
      
      axios.post(`/api/v1/translations/store`, {
        data: this.data,
        group: this.filter.group
      })
      .then((response) => {
        console.log(response.data.message)
        this.$root.toast(response.data.message,'success')
      })
      .catch((error) => {
        console.error("Error:", error);
        if (error.response) {
          this.$root.toast(error.response.data.message, 'error')
        } else {
          this.$root.toast('Terjadi kesalahan jaringan.', 'error')
        }
      })
      .finally(() => {
        this.loadingSubmit = false;
      });
    },
    edit (data) {
      this.$router.push({ name: 'admin.brands.edit', params: { id: data.action.item.id } })
    },
    deleteData (data) {
      this.$store.dispatch('set_delete_data',{
        url:`/api/v1/brands/delete/${data.action.item.id}`
      })
      $('#delete-modal').modal('show');
    },
    restoreData (data) {
      this.$store.dispatch('set_restore_data',{
        url:`/api/v1/brands/restore/${data.action.item.id}`
      })
      $('#restore-modal').modal('show');
    },
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
