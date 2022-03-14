import Vue from 'vue';
import Router from 'vue-router';
import routes from "./routes/common.js";
import pages from "@/pages/page.js";
import routerGuards from "./guards.js";

const router = new Router({
  routes: pages.concat(routes)
});
routerGuards(router);
export default router;
