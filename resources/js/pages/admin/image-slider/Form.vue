<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
     
      <div class="row align-items-center">
          <div class="col-12">
            <card-page>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header d-flex">
                      <h5>Image Slider <span class="text-danger">*</span></h5>
                      <button type="button" class="btn btn-sm btn-primary d-inline-block" @click="openFileManager">Open Media</button>
                    </div>
                    <div class="card-body">
                      <div v-if="params.previewImages.length">
                        <div class="row">
                          <template v-for="(item, index) in params.previewImages" :key="index">
                            <div class="col-sm-2 col-12 border rounded position-relative me-3" style="height: 150px;">
                              <template v-if="item.url && item.thumb_url">
                                <img :src="item.thumb_url" alt="Preview" class="w-100" />
                              </template>
                              
                              <template v-else>
                                <a :href="item.url" target="_blank" class="text-info">
                                  <div style="margin-top: 4rem;">
                                    <span>{{ item.name }}</span>
                                  </div>
                                </a>
                              </template>

                              <button 
                                class="btn btn-danger btn-sm position-absolute" 
                                style="top: 5px; right: 5px;" 
                                @click="deletePreview(index)">
                                <i class="fe fe-trash"></i>
                              </button>
                            </div>
                          </template>

                        </div>
                      </div>
                      <div v-else class="text-center">
                        <img class="avatar-img mx-auto" :src="previewImgLogo" style="width:200px;height:auto"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </card-page>
          </div>    
      </div>
      <div class="col-12">
        <div class="row">
            <div class="col-12">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" :disabled="loading || params.previewImages.length == 0">
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
import CKEditor4 from '../../../components/CKEditor4.vue'

export default {
  components: {
    CardForm,
    Select2,
    CardPage,
    Vue3TagsInput,
    CKEditor4
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {

    return {
      editorConfig: {
        toolbar: [
          ["Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript"],
          ["NumberedList", "BulletedList", "-", "Outdent", "Indent"],
          ["Link", "Unlink", "Anchor"],
          ["Image", "Table", "HorizontalRule", "SpecialChar"],
          ["Source"]
        ],
        height: 300,
        filebrowserImageBrowseUrl: "/media?type=image",
        filebrowserBrowseUrl: "/media?type=files",
        allowedContent: true,
        extraAllowedContent: "*[*]; iframe[*]; a[*];",
        resize_enabled: true,
      },
      params: {
        name: '',
        code: '',
        slug: '',
        category_id: [],
        brand_id: [],
        description_id: '',
        description_en: '',
        previewImages: [],
        productUrl: [],
        productTypeFile: [],
        keyword_id: '',
        keyword_en: '',
        meta_title_id: '',
        meta_title_en: '',
        meta_description_id: '',
        meta_description_en: '',
        specification_id: '',
        specification_en: '',
        id: null
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
  watch: {
    slug(newSlug) {
      this.params.slug = newSlug;
    },
  },

  async created () {
    if (!isNaN(this.$route.params.id)) {
      this.getData()
    }
  },

  methods: {
    openFileManager() {
      const fileManagerUrl = '/media?type=image,files';
      const fileManagerWindow = window.open(
        fileManagerUrl,
        'FileManager',
        'width=900,height=600'
      );

      const self = this;
      window.SetUrl = function(url) {
      url.forEach((res) => {
          if (!self.params.previewImages.some(image => image.url === res.url)) {
              self.params.previewImages.push({
                  url: res.url,
                  thumb_url: res.thumb_url,
                  name: res.name           
              });

              self.params.productTypeFile.push(res.is_image ? 'image' : 'file');
              self.params.productUrl.push(res.url);
          }
      });
  };
    },
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    async getData () {
      await axios.get(`/api/v1/products/edit/${this.$route.params.id}`).then(res => {
        let data = res.data.data
        
        this.params =  {
            name: data.name,
            code: data.code,
            slug: data.slug,
            category_id: data.categories.map(cate => cate.id),
            brand_id: data.brands.map(brand => brand.id),
            previewImages: [],
            productUrl: [],
            productTypeFile: [],
            keyword_id: data.keyword_id,
            keyword_en: data.keyword_en,
            meta_title_id: data.meta_title_id,
            meta_title_en: data.meta_title_en,
            meta_description_id: data.meta_description_id,
            meta_description_en: data.meta_description_en,
            description_id: data.description_id,
            description_en: data.description_en,
            specification_id: data.specification_id,
            specification_en: data.specification_en,
            id: data.id
          }

          data.product_media.map(res => {
            this.params.previewImages.push({
                url: res.url,
                thumb_url: res.thumb_url,
                name: res.name           
            });

            this.params.productTypeFile.push(res.type ? 'image' : 'file');
            this.params.productUrl.push(res.url);
          })
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      const param = new FormData()
      for (var key in this.params) {
        if (this.params[key] != null) {
          if (Array.isArray(this.params[key])) {
            this.params[key].forEach((value, index) => {
              param.append(`${key}[${index}]`, value);
            });
          } else {
            param.append(key, this.params[key]);
          }
        }
      }
      this.loading = true
        await axios.post('/api/v1/products/add', param).then(res => {
          this.$router.go(-1)
          this.loading = false
          this.$root.toast('Data created!','success')
        }).catch(err => {
          console.log(err);
          this.loading = false
          this.$root.toast(err.response.data.message,'error')
        })
    },
    setParam (field, value) {
      this.params[field] = value
    },

    getParam (field) {
      return this.params[field] ? this.params[field] : ''
    },
    deletePreview(index) {
      this.params.previewImages.splice(index, 1);
      this.params.productTypeFile.splice(index, 1);
      this.params.productUrl.splice(index, 1);
    }
  }
}
</script>

<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice, .v3ti .v3ti-tag{background-color: #052c7a !important;
    border: 1px solid #052c7a !important; color: #fff !important; font-size: 12px;}
  .cke_notifications_area{display: none !important;}
</style>