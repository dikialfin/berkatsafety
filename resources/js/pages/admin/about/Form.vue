<template>
  <form @submit.prevent="save">
    <div class="row pb-4">
      <div class="col-12">
        <card-form :title="'Update About Us'">
          <div class="row align-items-center mt-4">
            <div class="col-lg-2 col-12">
              <label>Name <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4  col-12">
              <input class="form-control" v-model="params.name" required readonly>
            </div>
            <div class="col-lg-2 col-12">
              <label>Slug <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-4 col-12">
              <input class="form-control" v-model="slug" disabled>
            </div>
          </div>
        </card-form>
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
                <button class="btn btn-primary" :disabled="loading">
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
        // removePlugins: "elementspath",
        resize_enabled: true,
        contentsCss: [
          window.location.protocol == 'https:' ? window.location.origin+'/css/bootstrap.min.css' : "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        ]
      },
      params: {
        name: '',
        slug: '',
        description_id: '',
        description_en: '',
        keyword_id: '',
        keyword_en: '',
        meta_title_id: '',
        meta_title_en: '',
        meta_description_id: '',
        meta_description_en: '',
        id: null
      },
      loading:false,
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
    slug () {
      return this.params.name
        .toLowerCase()                       // Convert to lowercase
        .replace(/[^a-z0-9]+/g, '-')         // Replace non-alphanumeric characters with hyphens
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
    updateKeyword(keyword, field){
      this.params[field] = keyword
    },
    async getData () {
      await axios.get(`/api/v1/about/edit/${this.$route.params.id}`).then(res => {
        let data = res.data.data
        this.params = {
          name: data.name,
          description_id: data.description_id,
          description_en: data.description_en,
          keyword_id: data.keyword_id,
          keyword_en: data.keyword_en,
          meta_title_id: data.meta_title_id,
          meta_title_en: data.meta_title_en,
          meta_description_id: data.meta_description_id,
          meta_description_en: data.meta_description_en,
          id: data.id
        }
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
        await axios.post('/api/v1/about/add', param).then(res => {
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
    }
  }
}
</script>

<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice, .v3ti .v3ti-tag{background-color: #052c7a !important;
    border: 1px solid #052c7a !important; color: #fff !important; font-size: 12px;}
  .cke_notifications_area{display: none !important;}
</style>