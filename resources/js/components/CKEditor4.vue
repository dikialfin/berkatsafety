<template>
  <div>
    <textarea :id="editorId"></textarea>
  </div>
</template>

<script>
export default {
  name: "CKEditor4",
  props: {
    value: {
      type: String,
      required: false,
      default: ""
    },
    config: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      editorInstance: null,
      editorId: `editor-${Math.random().toString(36).substr(2, 9)}` // ID unik untuk editor
    };
  },
  watch: {
    value(newValue) {
      if (this.editorInstance && this.editorInstance.getData() !== newValue) {
        this.editorInstance.setData(newValue);
      }
    }
  },
  mounted() {
    this.initEditor();
  },
  beforeDestroy() {
    if (this.editorInstance) {
      this.editorInstance.destroy();
      this.editorInstance = null;
    }
  },
  methods: {
    initEditor() {
      const ckeditorScript = document.createElement("script");
      ckeditorScript.src = "https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js";
      ckeditorScript.onload = () => {
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.env.isCompatible = true;
        
        this.editorInstance = CKEDITOR.replace(this.editorId, this.config);
        this.editorInstance.setData(this.value);
        this.editorInstance.on("change", () => {
          const data = this.editorInstance.getData();
          this.$emit("input", data);
        });
      };
      document.body.appendChild(ckeditorScript);
    }

  }
};
</script>