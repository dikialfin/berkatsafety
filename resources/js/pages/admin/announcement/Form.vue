<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Add Announcement'">
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
          <div class="row mt-4">
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
        </card-form>
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
      <div v-if="params.previewImages.length > 0">
        <div class="row">
          <template v-for="(item, index) in params.previewImages" :key="index">
            <div class="col-sm-7 mx-sm-auto col-12 border rounded " style="height: 300px;" v-if="index == 0">
              <img :src="item" alt="Preview" class="w-100" style="width: 100%; height: 100%; object-fit: contain; object-position: center;" />
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
        description_id: '',
        description_en: '',
        previewImages: [],
        announcementImageUrl: [],
        announcementTypeFile: [],
        id: null,
        slug: '',
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
    slug () {
      return this.params.name
        .toLowerCase()                     
        .replace(/[^a-z0-9]+/g, '-')       
        .replace(/^-+|-+$/g, '');  
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
    }
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
      self.params.previewImages = []
      window.SetUrl = function(url) {
        url.forEach((res) => {
            if (!self.params.previewImages.some(image => image.url === res.url)) {
                self.params.previewImages.push(res.url)
            }
        });
        
    };
    },
    async getData () {
      await axios.get(`/api/v1/announcement/edit/${this.$route.params.id}`).then(res => {
        let data = res.data.data

        console.log("========== DATA VARDUMP ==========")
        console.log(data)
        console.log("==================================")

        this.params = {
          name: data.name,
          description_id: data.description_id,
          description_en: data.description_en,
          slug: data.slug,
          previewImages: [],
          announcementImageUrl: [],
          announcementTypeFile: [],
          id: data.id
        }

        if (data.image !== null || data.image.length > 0) {
          this.params.previewImages.push(data.image)
        }

        // if (data.blog_media.length > 0) {
        //   data.blog_media.map(res => {
        //     this.params.previewImages.push({
        //       url: res.value,
        //       thumb_url: res.value,
        //     });
        //     this.params.announcementImageUrl.push(res.value)
        //     this.params.announcementTypeFile.push(res.type)
        //   })
        // } else {
        //   this.params.previewImages.push({
        //     url: data.image,
        //     thumb_url: data.image
        //   })
        //   this.params.announcementImageUrl.push(data.image)
        //   this.params.announcementTypeFile.push("image")
        // }        

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



      console.log("=== Isi FormData yang Akan Dikirim ===");
    for (let pair of param.entries()) {
        const [key, value] = pair; // Destructuring array [key, value]
        if (value instanceof File) {
            // Jika nilainya adalah File object
            console.log(`${key}: [File Object] Name: ${value.name}, Type: ${value.type}, Size: ${value.size} bytes`);
        } else {
            // Untuk nilai lainnya (string, number, dll.)
            console.log(`${key}: ${value}`);
        }
    }
    console.log("======================================");




      this.loading = true
        await axios.post('/api/v1/announcement/add', param).then(res => {
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