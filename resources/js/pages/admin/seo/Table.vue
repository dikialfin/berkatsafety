<template>
  <form @submit.prevent="save">
    <div class="row">
      <div class="col-12">
        <div class="row d-flex align-items-center mt-2 mb-4">
          <div class="col-12 col-lg-6">
            <h2 class="mb-0 ">
              <i class="fe fe-globe bg-primary text-white p-3 me-2" style="border-radius:12px"></i> SEO Static Page
            </h2>
          </div>
          <div class="col-12 col-lg-6 mt-lg-0 mt-3">
            <div class="d-flex gap-2 d-md-flex justify-content-md-end" >
              <button class="btn btn-rounded btn-primary" :disabled="loadingSubmit">
                Save
              </button>
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
            <div class="col-12 col-lg-6">
              <card-page>
                <div class="row">
                  <div class="col-12">
                    <h3>SEO ID</h3>
                    <hr>
                  </div>
                  <div class="col-12">
                    <div class="row align-items-center">
                      <div class="col-12 col-lg-4">
                        <label>Keywords</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <vue3-tags-input
                          class="form-control form-control-sm"
                          style="border:1px solid #D2DDEC!important;border-radius: 12px;"
                          :tags="params.keyword_id"
                          placeholder=""
                          @on-tags-changed="(tag) => updateKeyword(tag,'keyword_id')"/>
                      </div>
                    </div>
                    <div class="row mt-4 align-items-center">
                      <div class="col-12 col-lg-4">
                        <label>Meta Title</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <input class="form-control" v-model="params.meta_title_id" required>
                      </div>
                    </div>
                    <div class="row mt-4 align-items-start">
                      <div class="col-12 col-lg-4">
                        <label>Meta Description</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <textarea class="form-control" v-model="params.meta_description_id" rows="5" required></textarea>
                      </div>
                    </div>
                  </div>
                
                </div>
              </card-page>
            </div>
            <div class="col-12 col-lg-6">
              <card-page>
                <div class="row">
                  <div class="col-12">
                    <h3>SEO EN</h3>
                    <hr>
                  </div>
                  <div class="col-12">
                    <div class="row align-items-center">
                      <div class="col-12 col-lg-4">
                        <label>Keywords</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <vue3-tags-input
                          class="form-control form-control-sm"
                          style="border:1px solid #D2DDEC!important;border-radius: 12px;"
                          :tags="params.keyword_en"
                          placeholder=""
                          @on-tags-changed="(tag) => updateKeyword(tag,'keyword_en')"/>
                      </div>
                    </div>
                    <div class="row mt-4 align-items-center">
                      <div class="col-12 col-lg-4">
                        <label>Meta Title</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <input class="form-control" v-model="params.meta_title_en" required>
                      </div>
                    </div>
                    <div class="row mt-4 align-items-start">
                      <div class="col-12 col-lg-4">
                        <label>Meta Description</label>
                      </div>
                      <div class="col-12 col-lg-8">
                        <textarea class="form-control" rows="5" v-model="params.meta_description_en" required></textarea>
                      </div>
                    </div>
                  </div>
                
                </div>
              </card-page>
            </div>
          </div>
        </card-page>
      </div>
    </div>
  </form>
</template>

<script>
import CardPage from '@/js/components/CardPage.vue'
import DataTable from '@/js/components/DataTable.vue'
import Select2 from '@/js/components/Select2.vue'
import Vue3TagsInput from 'vue3-tags-input';
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  metaInfo () {
    return { title: 'user' }
  },

  components: {
    CardPage,
    DataTable,
    Select2,
    Vue3TagsInput
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
      },
      params: {
        name:'',
        keyword_en : '',
        keyword_id : '',
        meta_title_id : '',
        meta_title_en : '',
        meta_description_en : '',
        meta_description_id : ''
      }
    }
  },

  mounted(){
    this.getData()
  },

  methods: {
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    getData (){
      this.params.keyword_en = ''
      this.params.keyword_id = ''
      this.params.meta_title_id = ''
      this.params.meta_title_en = ''
      this.params.meta_description_en = ''
      this.params.meta_description_id = ''
      this.loading = true
      axios.get(`/api/v1/seo-page-setting/data`,{params:{...this.filter}}).then(res => {
        this.loading = false 
        let data = res.data.data
        if (data) {
          let seoSetting = data.seo_setting
          this.params.keyword_en = seoSetting.keyword_en
          this.params.keyword_id = seoSetting.keyword_id
          this.params.meta_title_id = seoSetting.meta_title_id
          this.params.meta_title_en = seoSetting.meta_title_en
          this.params.meta_description_en = seoSetting.meta_description_en
          this.params.meta_description_id = seoSetting.meta_description_id
        }
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
    save() {
      this.loadingSubmit = true;
      
      axios.post(`/api/v1/seo-page-setting/store`, {
        data: this.params,
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
