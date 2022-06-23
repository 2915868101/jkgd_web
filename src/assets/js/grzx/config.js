import Vue from 'vue'
// 首页显示配置
Vue.prototype.grzxConfig = [{
    type: 'hz',
    jzjlList: [
      { tit: '挂号记录', icons: require('@/assets/img/grzx/ghjl_icon.png'), url: 'grzx_ghjl' },
      { tit: '报告查询', icons: require('@/assets/img/grzx/bgcx_icon.png'), url: 'bgcx_index' },
    ],
    gdfwList: [
      { tit: '电子健康卡', icons: require('@/assets/img/grzx/wdjzr_icon.png'), url: 'people_lists' },
      { tit: '关注的医生', icons: require('@/assets/img/grzx/gzdys_icon.png'), url: 'doctor_lists' },
      { tit: '健康百科', icons: require('@/assets/img/grzx/jkzc_icon.png'), url: 'jkbk_index' },
    ]
  },
  {
    type: 'zyzs',
    jzjlList: [
      { tit: '挂号记录', icons: require('@/assets/img/grzx/ghjl_icon.png'), url: 'grzx_ghjl' },
      { tit: '报告查询', icons: require('@/assets/img/grzx/bgcx_icon.png'), url: 'bgcx_index' },
    ],
    gdfwList: [
      { tit: '电子健康卡', icons: require('@/assets/img/grzx/wdjzr_icon.png'), url: 'people_lists' },
      { tit: '关注的医生', icons: require('@/assets/img/grzx/gzdys_icon.png'), url: 'doctor_lists' },
      { tit: '健康百科', icons: require('@/assets/img/grzx/jkzc_icon.png'), url: 'jkbk_index' },
    ]
  },
  {
    type: 'cz',
    jzjlList: [
      { tit: '挂号记录', icons: require('@/assets/img/grzx/ghjl_icon.png'), url: 'grzx_ghjl' },
      { tit: '报告查询', icons: require('@/assets/img/grzx/bgcx_icon.png'), url: 'bgcx_index' },
      { tit: '咨询记录', icons: require('@/assets/img/grzx/xswzjl_icon.png'), url: 'zxzx_index' },
    ],
    gdfwList: [
      { tit: '电子健康卡', icons: require('@/assets/img/grzx/wdjzr_icon.png'), url: 'people_lists' },
      { tit: '关注的医生', icons: require('@/assets/img/grzx/gzdys_icon.png'), url: 'doctor_lists' },
      { tit: '我的申请', icons: require('@/assets/img/grzx/wdsq_icon.png'), url: 'jtys_qy_jd' },
      { tit: '健康自测', icons: require('@/assets/img/grzx/jkzc_icon.png'), url: 'jkzc_index' },
    ]
  },
  {
    type: 'gd',
    jzjlList: [
      { tit: '挂号记录', icons: require('@/assets/img/grzx/ghjl_icon.png'), url: 'grzx_ghjl' },
      { tit: '报告查询', icons: require('@/assets/img/grzx/bgcx_icon.png'), url: 'bgcx_index' },
      { tit: '咨询记录', icons: require('@/assets/img/grzx/xswzjl_icon.png'), url: 'zxzx_index' },
    ],
    gdfwList: [
      { tit: '电子健康卡', icons: require('@/assets/img/grzx/wdjzr_icon.png'), url: 'people_lists' },
      { tit: '关注的医生', icons: require('@/assets/img/grzx/gzdys_icon.png'), url: 'doctor_lists' },
      { tit: '我的申请', icons: require('@/assets/img/grzx/wdsq_icon.png'), url: 'jtys_qy_jd' },
      { tit: '健康自测', icons: require('@/assets/img/grzx/jkzc_icon.png'), url: 'jkzc_index' },
    ]
  },
]