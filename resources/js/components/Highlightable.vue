<template>
  <div>
    <div
      v-show="showMenu"
      class="menu"
      :style="{
        left: `${x}px`,
        top: `${y}px`
      }"
      @mousedown.prevent=""
    >
      <template v-if="!result">
        <b-spinner
          v-if="loading"
          variant="black"
          small
        />
        <button v-else type="button" class="btn btn-sm btn-trans" @click="translate()">Translate</button>
      </template> 
      <p class="mb-0" v-else>{{result}}</p>
      <!-- You can add more buttons here -->
    </div>

    <!-- The insterted text should be displayed here -->
    <slot/>
  </div>
</template>
<script>
import axios from 'axios'
export default {
  data () {
    return {
      x: 0,
      y: 0,
      showMenu: false,
      selectedText: '',
      loading:false,
      result:null
    }
  },
  computed: {
    highlightableEl () {
      return this.$slots.default()[0]
    }
  },
  mounted () {
    window.addEventListener('mouseup', this.onMouseup)
  },

  beforeDestroy () {
    window.removeEventListener('mouseup', this.onMouseup)
  },
  methods: {
    async translate(){
      const language = this.highlightableEl.ctx.data.params.language
      this.loading = true
      await axios.post(`/api/v1/translation/translate`, {language:language,answer:this.selectedText}).then(res => {
        this.loading = false
        this.result = res.data.result.result
      }).catch(err => {
        this.loading = false
        this.$root.toast(err.response.data.message,'error')
      })
    },
    handleAction (action) {
    this.$emit(action, this.selectedText)
  },
  onMouseup () {
    this.result = null 
    this.loading = false
    const selection = window.getSelection()
    const selectionRange = selection.getRangeAt(0)

    // startNode is the element that the selection starts in
    const startNode = selectionRange.startContainer.parentNode
    // endNode is the element that the selection ends in
    const endNode = selectionRange.endContainer.parentNode

    // if the selected text is not part of the highlightableEl (i.e. <p>)
    // OR
    // if startNode !== endNode (i.e. the user selected multiple paragraphs)
    // Then
    // Don't show the menu (this selection is invalid)
    if (!startNode.isSameNode(endNode)) {
      this.showMenu = false
      return
    }

    // Get the x, y, and width of the selection
    const { x, y, width } = selectionRange.getBoundingClientRect()
    // If width === 0 (i.e. no selection)
    // Then, hide the menu
    if (parseInt(width) == 0) {
      this.showMenu = false
      return
    }

    // Finally, if the selection is valid,
    // set the position of the menu element,
    // set selectedText to content of the selection
    // then, show the menu
    this.x = x + (width / 2)
    this.y = y + window.scrollY - 10
    this.selectedText = selection.toString()
    this.showMenu = true
  }
}
}
</script>

<style scoped>
.menu {
  height: auto;
  padding: 5px 10px;
  background: #fff;
  border: 2px solid whitesmoke;
  box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
  border-radius: 3px;
  position: fixed;
  top: 0;
  left: 0;
  transform: translate(-50%, -100%);
  transition: 0.2s all;
  display: flex;
  justify-content: center;
  align-items: center;
}

.menu:after {
  content: '';
  position: fixed;
  left: 50%;
  bottom: -5px;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid whitesmoke;
}

.item {
  color: #FFF;
  cursor: pointer;
}

.item:hover {
  color: #1199ff;
}

.item + .item {
  margin-left: 10px;
}
</style>