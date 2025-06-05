import { createStore } from "vuex";
import auth from "@/js/stores/auth.module";
import deletestate from "@/js/stores/delete.module";
import restorestate from "@/js/stores/restore.module";
import statusstate from "@/js/stores/status.module";

const store = createStore({
  modules: {
    auth,
    deletestate,
    restorestate,
    statusstate
  }
});

export default store;
