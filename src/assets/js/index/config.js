import Vue from 'vue'
// 首页显示配置
Vue.prototype.indexConfig = [{
    type: 'hz',
    title: '惠州健康通',
    indexBg: require('@/assets/img/index/hz/index-bg.png'),
    menuList: [
      { tit: '去挂号', txt: '一键线上挂号', icons: require('@/assets/img/index/hz/list-icon1.png'), url: 'yygh_index' },
      { tit: '智能分诊', txt: '精准找科室', icons: require('@/assets/img/index/hz/list-icon2.png'), url: 'guidance_index' }
    ],
    menuWrap: [
      { tit: '报告查询', icons: require('@/assets/img/index/hz/menu-icon1.png'), url: 'bgcx_index' },
      { tit: '健康档案', icons: require('@/assets/img/index/hz/menu-icon2.png'), url: 'jkda_index' },
      { tit: '慢病管理', icons: require('@/assets/img/index/hz/menu-icon3.png'), url: 'mbgl_index' },
      { tit: '医疗信息', icons: require('@/assets/img/index/hz/menu-icon5.png'), url: 'http://wjj.huizhou.gov.cn/gkmlpt/mindex', islink: '1' },
      { tit: '接种免疫', icons: require('@/assets/img/index/hz/menu-icon6.png'), url: 'http://w.hzzfy.com/hospital/public/index.php/wechat/Diagnose/vaccine', islink: '1' },
      { tit: '医保凭证', icons: require('@/assets/img/index/hz/menu-icon9.png'), url: 'https://mp.weixin.qq.com/insurance/card/creditbind?cityid=441300&uin=&key=&devicetype=Windows+10+x64&version=62090529&lang=zh_CN&ascene=1&winzoom=1#wechat_redirect', islink: '1' },
      { tit: '生育登记', icons: require('@/assets/img/index/hz/menu-icon8.png'), url: 'syywsm' },
      { tit: '生育药具服务', icons: require('@/assets/img/index/hz/menu-icon7.png'), url: 'http://guangdong.yaoju.org.cn/weixin/open/new/toindex', islink: '1' },
    ]
  },
  {
    type: 'zyzs',
    title: '',
    menuList: [
      { tit: '去挂号', txt: '一键线上挂号', icons: require('@/assets/img/index/hz/list-icon1.png'), url: 'yygh_index' },
      { tit: '智能分诊', txt: '精准找科室', icons: require('@/assets/img/index/hz/list-icon2.png'), url: 'guidance_index' }
    ],
    menuWrap: [
      { tit: '报告查询', icons: require('@/assets/img/index/hz/menu-icon1.png'), url: 'bgcx_index' },
      { tit: '健康档案', icons: require('@/assets/img/index/hz/menu-icon2.png'), url: 'jkda_index' },
      { tit: '慢病管理', icons: require('@/assets/img/index/hz/menu-icon3.png'), url: 'mbgl_index' },
      { tit: '医疗信息', icons: require('@/assets/img/index/hz/menu-icon5.png'), url: 'http://wjj.huizhou.gov.cn/gkmlpt/mindex', islink: '1' },
      { tit: '接种免疫', icons: require('@/assets/img/index/hz/menu-icon6.png'), url: 'http://w.hzzfy.com/hospital/public/index.php/wechat/Diagnose/vaccine', islink: '1' },
      { tit: '计划生育服务', icons: require('@/assets/img/index/hz/menu-icon7.png'), url: 'http://guangdong.yaoju.org.cn/weixin/open/new/toindex', islink: '1' },
      { tit: '生育登记', icons: require('@/assets/img/index/hz/menu-icon8.png'), url: 'syywsm' },
      { tit: '医保凭证', icons: require('@/assets/img/index/hz/menu-icon9.png'), url: 'https://mp.weixin.qq.com/insurance/card/creditbind?cityid=441300&uin=&key=&devicetype=Windows+10+x64&version=62090529&lang=zh_CN&ascene=1&winzoom=1#wechat_redirect', islink: '1' },
    ]
  },
  {
    type: 'cz',
    title: '健康池州',
    indexBg: require('@/assets/img/index/cz/index-bg.png'),
    menuList: [
      { tit: '去挂号', txt: '一键线上挂号', icons: require('@/assets/img/index/cz/list-icon1.png'), url: 'yygh_index' },
      { tit: '报告查询', txt: '随时查看报告', icons: require('@/assets/img/index/cz/list-icon2.png'), url: 'bgcx_index' }
    ],
    menuWrap: [
      { tit: '智能分诊', icons: require('@/assets/img/index/cz/menu-icon1.png'), url: 'guidance_index' },
      { tit: '健康档案', icons: require('@/assets/img/index/cz/menu-icon2.png'), url: 'jkda_index' },
      { tit: '家庭医生', icons: require('@/assets/img/index/cz/menu-icon4.png'), url: 'jtys_index' },
      { tit: '慢病管理', icons: require('@/assets/img/index/cz/menu-icon5.png'), url: 'mbgl_index' },
      { tit: '科普视频', icons: require('@/assets/img/index/cz/menu-icon6.png'), url: 'https://m.pule.com/coop/hnwisdom_chizhou/', islink: '1' },
      { tit: '满意度评价', icons: require('@/assets/img/index/cz/menu-icon3.png'), url: 'fwpj_list' },
      { tit: '电子社保', icons: require('@/assets/img/index/cz/menu-icon7.png'), url: 'https://mp.weixin.qq.com/insurance/card/jump?is_depart=1&cityid=340500&from=_iyYoLo3xU6YBB8cbjNmjA.%3D#wechat_redirect', islink: '1' },
      { tit: '电子健康卡', icons: require('@/assets/img/index/cz/menu-icon9.png'), url: 'people_lists' },
    ],
  },
  {
    type: 'gd',
    title: '健康广德',
    menuList: [
      { tit: '去挂号', txt: '一键线上挂号', icons: require('@/assets/img/index/cz/list-icon1.png'), url: 'yygh_index' },
      { tit: '报告查询', txt: '随时查看报告', icons: require('@/assets/img/index/cz/list-icon2.png'), url: 'bgcx_index' }
    ],
    menuWrap: [
      { tit: '核酸检测', icons: require('@/assets/img/index/cz/menu-icon6.png'), url: 'hsyy_qr' },
      { tit: '健康档案', icons: require('@/assets/img/index/cz/menu-icon2.png'), url: 'jkda_index' },
      { tit: '智能分诊', icons: require('@/assets/img/index/cz/menu-icon1.png'), url: 'guidance_index' },
      { tit: '慢病管理', icons: require('@/assets/img/index/cz/menu-icon5.png'), url: 'mbgl_index' },
      { tit: '家庭医生', icons: require('@/assets/img/index/cz/menu-icon4.png'), url: 'jtys_index' },
      { tit: '满意度评价', icons: require('@/assets/img/index/cz/menu-icon3.png'), url: 'fwpj_list' },
      { tit: '电子社保', icons: require('@/assets/img/index/cz/menu-icon7.png'), url: 'https://mp.weixin.qq.com/insurance/card/jump?is_depart=1&cityid=340500&from=_iyYoLo3xU6YBB8cbjNmjA.%3D#wechat_redirect', islink: '1' },
      { tit: '电子健康卡', icons: require('@/assets/img/index/cz/menu-icon9.png'), url: 'people_lists' },
    ],
  },
]