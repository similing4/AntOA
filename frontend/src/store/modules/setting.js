import config from '@/config'
import { ADMIN } from '@/config/default'
import { getLocalSetting } from '@/utils/themeUtil'

const localSetting = getLocalSetting(true)
const customTitlesStr = sessionStorage.getItem(process.env.VUE_APP_TBAS_TITLES_KEY)
const customTitles = (customTitlesStr && JSON.parse(customTitlesStr)) || []

export default {
  namespaced: true,
  state: {
    isMobile: false,
    animates: ADMIN.animates,
    palettes: ADMIN.palettes,
    pageMinHeight: 0,
    menuData: [],
    activatedFirst: undefined,
    customTitles,
    ...config,
    ...localSetting
  },
  getters: {
    menuData(state, getters, rootState) {
      if (!state.menuData || state.menuData.length == 0)
        state.menuData = JSON.parse(localStorage.antOAMenuData)
      return state.menuData
    },
    firstMenu(state) {
      const { menuData } = state
      return menuData.map(item => {
        const menuItem = { ...item }
        delete menuItem.children
        return menuItem
      })
    },
    subMenu(state) {
      const { menuData, activatedFirst } = state
      const current = menuData.find(menu => menu.fullPath === activatedFirst)
      return current && current.children || []
    }
  },
  mutations: {
    setDevice(state, isMobile) {
      state.isMobile = isMobile
    },
    setTheme(state, theme) {
      state.theme = theme
    },
    setLayout(state, layout) {
      state.layout = layout
    },
    setMultiPage(state, multiPage) {
      state.multiPage = multiPage
    },
    setAnimate(state, animate) {
      state.animate = animate
    },
    setWeekMode(state, weekMode) {
      state.weekMode = weekMode
    },
    setFixedHeader(state, fixedHeader) {
      state.fixedHeader = fixedHeader
    },
    setFixedSideBar(state, fixedSideBar) {
      state.fixedSideBar = fixedSideBar
    },
    setLang(state, lang) {
      state.lang = lang
    },
    setHideSetting(state, hideSetting) {
      state.hideSetting = hideSetting
    },
    correctPageMinHeight(state, minHeight) {
      state.pageMinHeight += minHeight
    },
    setMenuData(state, menuData) {
      let index = 0;
      let dfs = (dom) => {
        dom.key = index++;
        dom.key = dom.key + "";
        if (dom.children)
          for (let i = 0; i < dom.children.length; i++)
            dfs(dom.children[i]);
      };
      for (let j = 0; j < menuData.length; j++)
        dfs(menuData[j]);
      localStorage.antOAMenuData = JSON.stringify(menuData)
      state.menuData = menuData
    },
    setAsyncRoutes(state, asyncRoutes) {
      state.asyncRoutes = asyncRoutes
    },
    setPageWidth(state, pageWidth) {
      state.pageWidth = pageWidth
    },
    setActivatedFirst(state, activatedFirst) {
      state.activatedFirst = activatedFirst
    },
    setFixedTabs(state, fixedTabs) {
      state.fixedTabs = fixedTabs
    },
    setCustomTitleList(state, arr) {
      arr.map((row) => {
        let { path, name } = row;
        let title = name;
        if (title) {
          const obj = state.customTitles.find(item => item.path === path)
          if (obj) {
            obj.title = title
          } else {
            state.customTitles.push({ path, title })
          }
        }
      });
      sessionStorage.setItem(process.env.VUE_APP_TBAS_TITLES_KEY, JSON.stringify(state.customTitles))
    }
  }
}