/**
 * 该插件可根据菜单配置自动生成 ANTD menu组件
 * menuOptions示例：
 * [
 *  {
 *    name: '菜单名称',
 *    path: '菜单路由',
 *    visible: 'boolean, 是否可见, 默认 true',
 *    meta: {
 *      icon: '菜单图标'
 *    },
 *    children: [子菜单配置]
 *  },
 *  {
 *    name: '菜单名称',
 *    path: '菜单路由',
 *    meta: {
 *      icon: '菜单图标'
 *    },
 *    children: [子菜单配置]
 *  }
 * ]
 *
 **/
import Menu from 'ant-design-vue/es/menu'
import Icon from 'ant-design-vue/es/icon'
import fastEqual from 'fast-deep-equal'

const { Item, SubMenu } = Menu

export default {
  name: 'IMenu',
  props: {
    options: {
      type: Array,
      required: true
    },
    theme: {
      type: String,
      required: false,
      default: 'dark'
    },
    mode: {
      type: String,
      required: false,
      default: 'inline'
    },
    collapsed: {
      type: Boolean,
      required: false,
      default: false
    },
    openKeys: Array
  },
  data() {
    return {
      selectedKeys: [],
      sOpenKeys: [],
      cachedOpenKeys: []
    }
  },
  computed: {
    menuTheme() {
      return this.theme == 'light' ? this.theme : 'dark'
    }
  },
  created() {
    this.updateMenu()
    if (this.options.length > 0 && !this.options[0].fullPath) {
      this.formatOptions(this.options, '')
    }
  },
  watch: {
    options(val) {
      if (val.length > 0 && !val[0].fullPath) {
        this.formatOptions(this.options, '')
      }
    },
    collapsed(val) {
      if (val) {
        this.cachedOpenKeys = this.sOpenKeys
        this.sOpenKeys = []
      } else {
        this.sOpenKeys = this.cachedOpenKeys
      }
    },
    '$route': function() {
      this.updateMenu()
    },
    sOpenKeys(val) {
      this.$emit('openChange', val)
      this.$emit('update:openKeys', val)
    }
  },
  methods: {
    renderIcon: function(h, icon, key) {
      if (this.$scopedSlots.icon && icon && icon !== 'none') {
        const vnodes = this.$scopedSlots.icon({ icon, key })
        vnodes.forEach(vnode => {
          vnode.data.class = vnode.data.class ? vnode.data.class : []
          vnode.data.class.push('anticon')
        })
        return vnodes
      }
      return !icon || icon == 'none' ? null : h(Icon, { props: { type: icon } })
    },
    renderMenuItem: function(h, menu) {
      let tag = 'router-link'
      let config = { props: { to: menu.fullPath }, attrs: { style: 'overflow:hidden;white-space:normal;text-overflow:clip;' } }
      if (menu.meta && menu.meta.link) {
        tag = 'a'
        config = { attrs: { style: 'overflow:hidden;white-space:normal;text-overflow:clip;', href: menu.meta.link, target: '_blank' } }
      }
      return h(
        Item, { key: menu.key },
        [
          h(tag, config,
            [
              this.renderIcon(h, menu.meta ? menu.meta.icon : 'none', menu.fullPath),
              menu.name
            ]
          )
        ]
      )
    },
    renderSubMenu: function(h, menu) {
      let this_ = this
      let subItem = [h('span', { slot: 'title', attrs: { style: 'overflow:hidden;white-space:normal;text-overflow:clip;' } },
        [
          this.renderIcon(h, menu.meta ? menu.meta.icon : 'none', menu.fullPath),
          menu.name
        ]
      )]
      let itemArr = []
      menu.children.forEach(function(item) {
        if (item.visible !== false)
          itemArr.push(this_.renderItem(h, item))
      })
      return h(SubMenu, { key: menu.key },
        subItem.concat(itemArr)
      )
    },
    renderItem: function(h, menu) {
      if (menu.visible !== false) {
        let renderChildren = false
        const children = menu.children
        if (children != undefined) {
          for (let i = 0; i < children.length; i++) {
            if (children[i].visible !== false) {
              renderChildren = true
              break
            }
          }
        }
        return (menu.children && renderChildren) ? this.renderSubMenu(h, menu) : this.renderMenuItem(h, menu)
      }
    },
    renderMenu: function(h, menuTree) {
      let this_ = this
      let menuArr = []
      menuTree.forEach(function(menu, i) {
        if (menu.visible !== false)
          menuArr.push(this_.renderItem(h, menu, '0', i))
      })
      return menuArr
    },
    formatOptions(options, parentPath) {
      options.forEach(route => {
        if (route.path) {
          let isFullPath = route.path.substring(0, 1) == '/'
          route.fullPath = isFullPath ? route.path : parentPath + '/' + route.path
        }
        if (route.children) {
          this.formatOptions(route.children, route.fullPath)
        }
      })
    },
    updateMenu() {
      const matchedRoutes = this.$route.matched.filter(item => item.path !== '')
      let selectNodes = this.getSelectedNodes(this.$route);
      let selectedKeys = selectNodes.map((t) => t.key);
      if (selectNodes[0] && selectNodes[0].visible === false) {
        for (let i = selectNodes[1].children.indexOf(selectNodes[0]); i >= 0; i--)
          if (selectNodes[1].children[i].visible !== false) {
            selectedKeys[0] = selectNodes[1].children[i].key;
            break;
          }
      }
      if (selectedKeys[0])
        this.selectedKeys = [selectedKeys[0]];
      else
        this.selectedKeys = [];
      let openKeys = selectedKeys;
      if (!fastEqual(openKeys, this.sOpenKeys)) {
        this.collapsed || this.mode === 'horizontal' ? this.cachedOpenKeys = openKeys : this.sOpenKeys = openKeys
      }
    },
    getSelectedNodes(route) {
      let menuData = JSON.parse(localStorage.antOAMenuData);
      let nodes = null;
      for (let i = 0; i < menuData.length; i++) {
        nodes = this.getNodePathInTree(menuData[i], (node) => {
          return node.path == route.path || node.path == route.fullPath;
        });
        if (nodes)
          break;
      }
      return nodes ? nodes : [];
      //return route.matched.map(item => item.path)
    },
    getNodePathInTree(root, testfunc) {
      if (testfunc(root))
        return [root];
      if (root.children)
        for (let i = 0; i < root.children.length; i++) {
          let ret = this.getNodePathInTree(root.children[i], testfunc);
          if (ret)
            return ret.concat([root]);
        }
      return null;
    }
  },
  render(h) {
    return h(
      Menu, {
        props: {
          theme: this.menuTheme,
          mode: this.$props.mode,
          selectedKeys: this.selectedKeys,
          openKeys: this.openKeys ? this.openKeys : this.sOpenKeys
        },
        on: {
          'update:openKeys': (val) => {
            this.sOpenKeys = val
          },
          click: (obj) => {
            obj.selectedKeys = [obj.key]
            this.$emit('select', obj)
          }
        }
      }, this.renderMenu(h, this.options)
    )
  }
}