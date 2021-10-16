import { devHttp } from '@/http'

export const getMenuConfig = () => devHttp.get('menu_config')
