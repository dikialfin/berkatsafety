<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Slider Images'">
          <div class="row align-items-center mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                      <button type="button" class="btn btn-sm btn-primary d-inline-block" @click="openFileManager">Open Media</button>
                </div>
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
        </card-form>
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
                  Save
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
        imageUrl: [],
        typeFile: [],
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
    this.getData()
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

              self.params.typeFile.push(res.is_image ? 'image' : 'file');
              self.params.imageUrl.push(res.url);
          }
      });
  };
    },
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    async getData () {
      await axios.get(`/api/v1/image_slider/edit`).then(res => {
        let data = res.data.data

        console.log(data);

        this.params = {
          
          previewImages: [],
          imageUrl: [],
          typeFile: [],
        }

        if (data.length > 0) {
          data.map(res => {
            this.params.previewImages.push({
              url: res.value,
              thumb_url: res.value,
            });
            this.params.imageUrl.push(res.value)
            this.params.typeFile.push(res.type)
          })
        } 
        else {
          this.params.previewImages.push({
            url: data.image,
            thumb_url: data.image
          })
          this.params.imageUrl.push(data.image)
          this.params.typeFile.push("image")
        }     
        
        console.log("=========== PARAMS VARDUMP ===========") 
        console.log(this.params)
        console.log("======================================") 

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
        await axios.post('/api/v1/image_slider/add', param).then(res => {
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
      this.params.typeFile.splice(index, 1);
      this.params.imageUrl.splice(index, 1);
    }
  }
}
</script>

<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice, .v3ti .v3ti-tag{background-color: #052c7a !important;
    border: 1px solid #052c7a !important; color: #fff !important; font-size: 12px;}
  .cke_notifications_area{display: none !important;}
</style>