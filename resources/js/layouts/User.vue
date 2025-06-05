<template>
<div class="main-layout">
  <sidebar/>
  <topbar/>
  <div class="main-content " style="background:rgb(241 245 249);border-top-left-radius:16px;min-height:calc(100vh - 75px)">
      <div class="container-fluid" style="padding-top:30px">
        <router-view></router-view>
      </div>
      <modal-logout/>
      <modal-delete/>
      <modal-restore/>
      <modal-status/>
  </div>
</div>
</template>

<script>
import Topbar from '../components/Topbar.vue'
import Sidebar from '../components/Sidebar/Sidebar.vue'
import ModalLogout from '../components/Modal/ModalLogout.vue'
import ModalDelete from '../components/Modal/ModalDelete.vue'
import ModalRestore from '../components/Modal/ModalRestore.vue'
import ModalStatus from '../components/Modal/ModalStatus.vue'
export default {
  components: { Topbar, Sidebar, ModalLogout, ModalDelete, ModalRestore, ModalStatus },
}
</script>
