<template>
  <Select2
    :placeholder="placeholder"
    :options="dataoption"
    :required="required"
    :value="multiple?valuemultiple:value"
    :disabled="disabled"
    @select="select"
    @change="onchange"
    :settings="settings"
    :class="classes"
  />
</template>

<script>
import Select2 from 'v-select2-component'
import axios from 'axios'

export default {
  name: 'VSelect',
  components: {
    Select2,
  },
  props: {
    required: {
      type: Boolean,
      default: true,
    },
    options: {
      type: Array,
      default: () => [],
    },
    settings: {
      type: Object,
      default: () => {},
    },
    select: {
      type: Function,
      default: ({ id, text }) => {},
    },
    onchange: {
      type: Function,
      default: ({id}) => {}
    },
    api: {
      type: String,
      default: null,
    },
    value: {
      type: [String, Number, Array],
      default: '',
    },
    valuemultiple: {
      type: Array,
      default: () => [],
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    classes: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    placeholder:{
      type: String,
      default: ''
    }
  },

  data() {
    return {
      dataoption: [],
    }
  },

  watch: {
    options: {
      deep: true,
      handler: function (val) {
        this.dataoption = val
      },
    },
     api:function(){
      this.getData()
    }
  },

  mounted() {
    if (this.api != null) {
      this.getData()
    } else {
      this.dataoption = this.options
    }
  },

  methods: {
    getData() {
      axios.get(this.api).then((res) => {
        this.dataoption = res.data
      })
    },
  },
}
</script>

<style>
span.select2-container--default .select2-selection--multiple{
    padding: 0px !important;
    overflow-y: auto;
    cursor: pointer;
}
</style>
