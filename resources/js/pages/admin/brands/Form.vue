<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Brands'">
          <div class="row align-items-center">
            <div class="col-lg-2 col-12">
                <div v-if="params.previewImages.length > 0">
                  <template v-for="(item, index) in params.previewImages" :key="index">
                    <img class="avatar-img" v-if="index == 0" :src="item" style="width:100%;height:auto"/>
                  </template>
                </div>
                <div v-else class="text-center">
                    <img class="avatar-img " :src="previewImgLogo" style="width:100%;height:auto"/>
                </div>
            </div>
            <div class="col-lg-4 col-12">
              <button type="button" class="btn btn-sm btn-primary d-inline-block" @click="openFileManagerImage">Upload Brand Logo</button>
            </div>
            <div class="col-lg-2 col-12">
                <div v-if="params.previewImagesBanner.length > 0">
                  <template v-for="(item, index) in params.previewImagesBanner" :key="index">
                    <img class="avatar-img" v-if="index == 0" :src="item" style="width:100%;height:auto"/>
                  </template>
                </div>
                <div v-else class="text-center">
                    <img class="avatar-img " :src="previewImgLogo" style="width:100%;height:auto"/>
                </div>
            </div>
            <div class="col-lg-4 col-12">
              <button type="button" class="btn btn-sm btn-primary d-inline-block" @click="openFileManagerImageBanner">Upload Brand Banner</button>
            </div>
          </div>
          <div class="row align-items-center mt-4">
            <div class="col-lg-2 col-12">
              <label>Name <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="params.name" required>
            </div>
            <div class="col-lg-2 col-12">
              <label>Slug <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="slug" disabled>
            </div>
          </div>
          <div class="row align-items-start mt-4">
            <div class="col-lg-2 col-12">
              <label>Description ID <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <textarea class="form-control" v-model="params.description_id" required />
            </div>
            <div class="col-lg-2 col-12">
              <label>Description EN <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <textarea class="form-control" v-model="params.description_en" required />
            </div>
          </div>
          <div class="row align-items-start mt-4">
            <div class="col-lg-2 col-12">
              <label>Order Number </label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="params.order_number" type="number" />
            </div>
          </div>
        </card-form>
      </div>
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
                  <input class="form-control" v-model="params.meta_title_id">
                </div>
              </div>
              <div class="row mt-4 align-items-start">
                <div class="col-12 col-lg-4">
                  <label>Meta Description</label>
                </div>
                <div class="col-12 col-lg-8">
                  <textarea class="form-control" v-model="params.meta_description_id" rows="5"></textarea>
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
                  <input class="form-control" v-model="params.meta_title_en">
                </div>
              </div>
              <div class="row mt-4 align-items-start">
                <div class="col-12 col-lg-4">
                  <label>Meta Description</label>
                </div>
                <div class="col-12 col-lg-8">
                  <textarea class="form-control" rows="5" v-model="params.meta_description_en"></textarea>
                </div>
              </div>
            </div>
          
          </div>
        </card-page>
      </div>
      <div class="col-12">
        <div class="row">
            <div class="col-12">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" :disabled="loading || params.previewImages.length == 0 || params.previewImagesBanner.length == 0 ">
                  <b-spinner
                    v-if="loading"
                    small class="me-3" variant="white"
                  />
                  Submit
                </button>
              </div>
            </div>
          </div>
      </div>
    </div>
  </form>
</template>

<script>
import axios from 'axios'
import CardForm from '../../../components/CardForm.vue'
import { mapGetters } from 'vuex'
import Select2 from '../../../components/Select2.vue'
import CardPage from '@/js/components/CardPage.vue'
import Vue3TagsInput from 'vue3-tags-input';

export default {
  components: {
    CardForm,
    Select2,
    CardPage,
    Vue3TagsInput
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {

    return {
      params: {
        name: '',
        previewImages: [],
        previewImagesBanner: [],
      },
      loading:false,
      image: null,
      previewImgLogo:'/images/default-img.png',
      previewImgBanner:'/images/default-img.png'
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
    slug () {
      return this.params.name
        .toLowerCase()                      
        .replace(/[^a-z0-9]+/g, '-')        
        .replace(/^-+|-+$/g, '');  
    },
    hasID () {
      return !isNaN(this.$route.params.id)
    },
    title () {
      if (!isNaN(this.$route.params.id)) {
        return 'Edit'
      } else {
        return 'Create'
      }
    },
  },

  async created () {
    if (!isNaN(this.$route.params.id)) {
      this.getData()
    }
  },

  methods: {
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    async getData () {
      await axios.get(`/api/v1/brands/edit/${this.$route.params.id}`).then(res => {
        this.params = res.data.data
        if(res.data.data.logo){
          this.params.previewImages = [res.data.data.logo]
        }
        if(res.data.data.banner){
          this.params.previewImagesBanner = [res.data.data.banner]
        }
        
        this.params.keyword_en = this.params.keyword_en ? this.params.keyword_en.split(',') : []
        this.params.keyword_id = this.params.keyword_id ? this.params.keyword_id.split(',') : []

        console.log('params:',this.params)
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      const param = new FormData()
      for ( var key in this.params ) {
        if(this.params[key] != null) param.append(key, this.params[key]);
      }
      this.loading = true
      if (!isNaN(this.$route.params.id)) {
        await axios.post(`/api/v1/brands/update`, param).then(res => {
          this.$router.go(-1)
          this.loading = false
          this.$root.toast('Data updated!','success')
        }).catch(err => {
          this.loading = false
          this.$root.toast(err.response.data.message,'error')
        })
      } else {
        await axios.post('/api/v1/brands/add', param).then(res => {
          this.$router.go(-1)
          this.loading = false
          this.$root.toast('Data created!','success')
        }).catch(err => {
          this.loading = false
         this.$root.toast(err.response.data.message,'error')
        })
      }
    },

    changePreviewLogo(e){
      const [file] = e.target.files
      if (file) {
        if(file.size > 500000){ //500kb
          this.$root.toast('File cannot more than 500kb!','error')
          $('#fileinput').val(null)
          this.previewImgLogo = '/images/default-user.png'
          this.params.logo = null
          return false
        }
        this.params.logo = file
        this.previewImgLogo = URL.createObjectURL(file)
      }else{
        this.params.logo = null
        this.previewImgLogo = '/images/default-user.png'
      }
    },

    changePreviewBanner(e){
      const [file] = e.target.files
      if (file) {
        if(file.size > 500000){ //500kb
          this.$root.toast('File cannot more than 500kb!','error')
          $('#fileinput').val(null)
          this.previewImgBanner = '/images/default-user.png'
          this.params.banner = null
          return false
        }
        this.params.banner = file
        this.previewImgBanner = URL.createObjectURL(file)
      }else{
        this.params.banner = null
        this.previewImgBanner = '/images/default-user.png'
      }
    },

    setParam (field, value) {
      this.params[field] = value
    },

    getParam (field) {
      return this.params[field] ? this.params[field] : ''
    },
    openFileManagerImage() {
      const fileManagerUrl = '/media?type=image';
      const fileManagerWindow = window.open(
        fileManagerUrl,
        'FileManager',
        'width=900,height=600'
      );

      const self = this;
      self.params.previewImages = []
      window.SetUrl = function(url) {
        url.forEach((res) => {
            if (!self.params.previewImages.some(image => image.url === res.url)) {
                self.params.previewImages.push(res.url)
            }
        });  
      };
    },
    openFileManagerImageBanner() {
      const fileManagerUrl = '/media?type=image';
      const fileManagerWindow = window.open(
        fileManagerUrl,
        'FileManager',
        'width=900,height=600'
      );

      const self = this;
      self.params.previewImagesBanner = []
      window.SetUrl = function(url) {
        url.forEach((res) => {
            if (!self.params.previewImagesBanner.some(image => image.url === res.url)) {
                self.params.previewImagesBanner.push(res.url)
            }
        });  
      };
    },
  }
}
</script>
