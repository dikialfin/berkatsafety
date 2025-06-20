<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Add Blog'">
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
          <div class="row align-items-center mt-4">
            <div class="col-lg-2 col-12">
              <label>Category <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-10 col-12">
              <select-2
                :api="'/api/v1/categories/select-parent-children'"
                :valuemultiple="params.category_id"
                :required="true"
                :multiple="true"
                :onchange="(val) => params.category_id = val"
                :settings="{
                  multiple: true
                }"
              />
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
      <div class="row align-items-center">
          <div class="col-12">
            <card-page>
              <div class="row">
                <div class="col-12 col-lg-12">
                  <label>Content ID <span class="text-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-12">
                  <CKEditor4
                    v-model="params.description_id"
                    :config="editorConfig"
                  />
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12 col-lg-12">
                  <label>Content EN <span class="text-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-12">
                  <CKEditor4
                    v-model="params.description_en"
                    :config="editorConfig"
                  />
                </div>
              </div>
            </card-page>
          </div>    
      </div>
      <div class="row align-items-center">
          <div class="col-12">
            <card-page>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header d-flex">
                      <h5>Blog Images <span class="text-danger">*</span></h5>
                      <button type="button" class="btn btn-sm btn-primary d-inline-block" @click="openFileManager">Open Media</button>
                    </div>
                    <div class="card-body">
                      <div v-if="params.previewImages.length">
                        <div class="row">
                          <template v-for="(item, index) in params.previewImages" :key="index">
                            <div class="col-sm-2 col-12 border rounded position-relative me-3" style="height: 150px;">
                              <template v-if="item.url && item.thumb_url">
                                <img :src="item.thumb_url" alt="Preview" class="w-100" style="width: 100%;height: 100%;object-fit: cover;"/>
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
                <button class="btn btn-primary" :disabled="loading || !params.previewImages">
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
import CKEditor4 from '../../../components/CKEditor4.vue'
import CardPage from '@/js/components/CardPage.vue'
import Vue3TagsInput from 'vue3-tags-input';

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
        filebrowserBrowseUrl: "/media?type=files"
      },
      params: {
        name: '',
        slug: '',
        category_id: [],
        description_id: '',
        description_en: '',
        previewImages: [],
        blogUrl: [],
        blogTypeFile: [],
        keyword_id: '',
        keyword_en: '',
        meta_title_id: '',
        meta_title_en: '',
        meta_description_id: '',
        meta_description_en: '',
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
      const fileManagerUrl = '/media?type=image';
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

              self.params.blogTypeFile.push(res.is_image ? 'image' : 'file');
              self.params.blogUrl.push(res.url);
          }
      });
  };
    },
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    async getData () {
      await axios.get(`/api/v1/blogs/edit/${this.$route.params.id}`).then(res => {
        let data = res.data.data

        console.log("==============================")
        console.log(data)
        console.log("==============================")

        this.params = {
          name: data.name,
          slug: data.slug,
          category_id: data.categories.map(cate => cate.id),
          description_id: data.description_id,
          description_en: data.description_en,
          previewImages: [],
          keyword_id: data.keyword_id,
          keyword_en: data.keyword_en,
          meta_title_id: data.meta_title_id,
          meta_title_en: data.meta_title_en,
          meta_description_id: data.meta_description_id,
          meta_description_en: data.meta_description_en,
          id: data.id
        }

        data.blog_media.map(res => {
            this.params.previewImages.push({
                url: res.value,
                thumb_url: res.value,
            });
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
      console.log("============VARDUMP PARAMS=================")
      console.log(this.params)
      console.log("================================")
      // this.loading = true
      //   await axios.post('/api/v1/blogs/add', param).then(res => {
      //     this.$router.go(-1)
      //     this.loading = false
      //     this.$root.toast('Data created!','success')
      //   }).catch(err => {
      //     console.log(err);
      //     this.loading = false
      //     this.$root.toast(err.response.data.message,'error')
      //   })
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