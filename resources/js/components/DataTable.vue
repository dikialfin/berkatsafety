<template>
  <div class="row">
    <div class="col-12" v-if="searchable">
      <div class="row">
        <div class="col-lg-4 col-12" v-if="withSearchBox">
          <div class="input-group input-group-merge input-group-reverse mb-3" v-if="!isSearchMultiple">
            <input
              :value="search"
              type="text" class="form-control"
              :placeholder="placeholder"
              aria-label="Username"
              aria-describedby="basic-addon1"
              @input="(value) => search=value.target.value"
            >

            <span id="basic-addon1"
              class="input-group-text">
              <i class="fe fe-search"/>
            </span>

          </div>
          <div class="input-group input-group-merge input-group-reverse mb-3" v-else>

            <vue3-tags-input
              class="form-control form-control-sm"
              style="border:1px solid #D2DDEC!important;"
              :tags="searchMultiple"
              placeholder="Search..."
              @on-tags-changed="(tag) => updateTag(tag)"/>
            <span id="basic-addon1"
              class="input-group-text">
              <i class="fe fe-search"/>
            </span>
          </div>
        </div>
        <div class="col-lg col-12 text-end" >
          <template v-if="isBulkDelete">
            <button
              class="btn btn-outline-danger me-3"
              data-bs-toggle="modal"
              data-bs-target="#bulk-delete-modal"
            >
              <i class="fe fe-trash me-3"></i>
              {{ 'Bulk Delete' }}
              <span class="badge ms-2 rounded-pill bg-light">{{bulkDeleteId.length}}</span>
            </button>
            <modal-bulk-delete
              :delete_url="bulkDeleteUrl"
              :param="{
                id:bulkDeleteId
              }"
            />
          </template>
          <template v-if="filterable">
            <button
              class="btn btn-white"
              @click="openfilter = !openfilter"
              data-bs-toggle="dropdown"
              data-bs-auto-close="outside"
            >
              <i class="fe fe-filter me-3"></i>
              {{ 'Filter' }}
            </button>
            <div class="dropdown-menu dropdown-menu-end dropdownfilter">
              <slot name="filter"></slot>
            </div>
          </template>
          <template v-if="selectAllButton"> 
            <button
              class="btn btn-white"
              @click="selectAll('check',items)"
            >
              <i class="fe fe-check me-3"></i>
              {{ 'Check All' }}
            </button>
            <button
              class="btn btn-white"
              @click="selectAll('uncheck',items)"
            >
              <i class="fe fe-x me-3"></i>
              {{ 'Uncheck All' }}
            </button>
          </template>
        </div>
      </div>
    </div>
    <div class="col-12 mt-3">
      <div class="table-responsive mb-0">
        <b-table
          small hover
          show-empty
          :items="items"
          :busy="loading"
          :fields="fields"
          label-sort-asc=""
          label-sort-desc=""
          label-sort-clear=""
          @sort-changed="onSortChanged"
           style="white-space: normal"
        >
          <template #cell()="data">
           <span v-html="data.value"></span>
          </template>
          <template #table-busy>
            <div class="text-center text-primary my-2">
              <b-spinner class="align-middle" />
              <strong>Loading...</strong>
            </div>
          </template>
          <template #cell(selected)="row">
            <slot name="selected" :action="row" />
          </template>
          <template #cell(index)="data">
            {{ data.index + 1 }}
          </template>
          <template #cell(user_status)="row">
            <slot name="user_status" :action="row" />
          </template>
          <template #cell(openai_status)="row">
            <slot name="openai_status" :action="row" />
          </template>
          <template #cell(action)="row">
            <slot name="action" :action="row" />
          </template>
          <template #empty>
            <div class="row justify-content-center">
              <div class="col-5 text-center">
                <img src="/images/no-data.png" class="img-fluid my-5" style="width:40%">
                <h2 class="mt-3"><b>No Data Available</b></h2>
              </div>
            </div>
          </template>
        </b-table>
        <div class="mt-3">
          <div v-if="pagination.total !== 0">
            <b-pagination
              :total-rows="pagination.total"
              :per-page="pagination.per_page"
              align="center"
              :value="currentPage"
              @input="(page) => {currentPage = page}"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Select2 from './Select2.vue'
import Vue3TagsInput from 'vue3-tags-input';
import ModalBulkDelete from './Modal/ModalBulkDelete.vue';
import debounce from 'lodash.debounce'

export default {
  components: { Select2,Vue3TagsInput, ModalBulkDelete },
  name: 'DataTable',

  props: {
    fields: {
      type: Array,
      default: () => []
    },
    url: {
      type: String,
      default: ''
    },
    param: {
      type: Object,
      default: () => {}
    },
    filterField: {
      type: Array,
      default: () => []
    },
    refresh: {
      type: Number,
      default: 0
    },
    searchable: {
      type: Boolean,
      default: true
    },
    placeholder:{
        type: String,
        default: 'Search...'
    },
    filterable: {
      type: Boolean,
      default: false,
    },
    isSearchMultiple:{
      type: Boolean,
      default: false
    },
    isBulkDelete:{
      type: Boolean,
      default:false
    },
    bulkDeleteId:{
      type: Array,
      default: []
    },
    bulkDeleteUrl:{
      type: String,
      default: ''
    },
    selectAllButton:{
      type: Boolean,
      default: false
    },
    selectAll:{
      type: Function,
      default: () => {}
    },
    withSearchBox:{
      type: Boolean,
      default: true
    }
  },

  data () {
    return {
      loading: true,
      pagination: {
        total: 0,
        per_page: 10
      },
      openfilter: false,
      items:[]
    }
  },

  computed: {
    currentPage: {
      get: function () {
        return this.$route.query.page
      },
      set: async function (page) {
        await this.$router.replace({
          query: {
            ...this.$route.query,
            page: page?page:1
          }
        })
      }
    },

    search: {
      get: function () {
        return this.$route.query.search
      },
      set: async function (search) {
        await this.$router.replace({
          query: {
            ...this.$route.query,
            search: search
          }
        })
      }
    },

    searchMultiple: {
      get: function () {
        let isArr = Array.isArray(this.$route.query.searchMultiple);
        let multiple = []
        if(!isArr && this.$route.query.searchMultiple){
          multiple.push(this.$route.query.searchMultiple)
        }else{
          multiple = this.$route.query.searchMultiple
        }
        return multiple
      },
      set: async function (searchMultiple) {
        await this.$router.replace({
          query: {
            ...this.$route.query,
            searchMultiple: searchMultiple
          }
        })
      }
    }
  },

  watch: {
    '$route.query.page' (value) {
      this.getData()
    },
    '$route.query.search': debounce(function(){
      this.getData()
    },1000),
    '$route.query.searchMultiple' () {
      this.getData()
    },
    '$route.query.sort' () {
      this.getData()
    },
     '$route.query.asc' () {
      this.getData()
    },
    refresh: function () {
      this.getData()
    }
  },

  async mounted () {
    if (this.$route.query.page == undefined) {
      await this.$router.replace({
        query: {
          ...this.$route.query,
          page: 1
        }
      }).catch(() => {})
    }

    if(this.$route.query.searchMultiple){
      let isArr = Array.isArray(this.$route.query.searchMultiple);
      console.log('is_array',isArr)
      if(!isArr){
        await this.$router.replace({
          query: {
            ...this.$route.query,
            searchMultiple:''
          }
        }).catch(() => {})
      }
    }

    this.getData()
  },

  methods: {
    getData: async function (query) {
      this.loading = true
      const { data } = await axios.get(this.url, {
        params: {
          ...this.param,
          search: this.search,
          searchMultiple:this.searchMultiple,
          page: this.$route.query.page,
          sort: this.$route.query.sort,
          sort_asc: this.$route.query.asc
        }
      })

      this.loading = false
      if (data.pagination) {
        this.pagination.total = data.data.total
        this.pagination.per_page = data.data.per_page
        this.pagination.current_page = data.data.current_page
      }

      if (this.filterField.length > 0) {
        this.items = data.data.data.filter(item => this.filter(item))
      } else {
        if (data.pagination) {
          this.items = data.data.data
        }else{
          this.items = data.data
        }
      }
    },

    onSortChanged: async function (event) {
      await this.$router.replace({
        query: {
          ...this.$route.query,
          sort: event.sortBy,
          asc: event.sortDesc ? 0 : 1
        }
      })
    },

    filter: function (item) {
      let flag = false

      for (let i = 0; i < this.filterField.length; i++) {
        if (item[this.filterField[i].key] === this.filterField[i].value) {
          flag = true
          break
        }
      }

      return flag
    },
    updateTag(tag){
      let tags = []
      tag.map(val => {
        console.log(val)
        let newTag = val.split(' ');
        console.log(newTag)
        tags = tags.concat(newTag)
      })
      console.log(tags)
      this.searchMultiple = tags
    }
  }
}
</script>

<style >
table{
  border-collapse: inherit !important;
  border-radius: 8px!important;
  border: 1px solid #CCD6E0;
  border-spacing: 0;
  white-space: nowrap;
}
table > thead > tr > th:last-child{
  border-top-right-radius: 8px;
}
table > thead > tr > th:first-child{
  border-top-left-radius: 8px;
}

.tbody {
  cursor: pointer;
  color: #312675;
}
.page-link {
  border: 1px solid transparent;
  color: grey;
}

.page-item.disabled .page-link, .page-item.disabled .page {
  border-color: transparent;
}

.page-item.active .page-link, .page-item.active .page {
  color: #2C7BE5;
  background-color: transparent;
  border-color: transparent;
}

.dropdownfilter{
  padding: 25px;
  width: 350px;
  border-radius: 15px;
  margin-top: 5px!important;
  box-shadow: -1px 0.5rem 1.5rem rgb(0 0 0 / 6%);
  border: 2px solid whitesmoke;
}

.v3ti--focus{
  box-shadow: none;
}
</style>
