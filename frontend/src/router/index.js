import Vue from 'vue';
import Router from 'vue-router';
import routes from "./routes/common.js";
import pages from "@/pages/page.js";
import routerGuards from "./guards.js";

export default function(pluginPages){
    pages[0].children = pages[0].children.concat(pluginPages);
    const router = new Router({
        routes: pages.concat(routes)
    });
    routerGuards(router);
    return router;
}