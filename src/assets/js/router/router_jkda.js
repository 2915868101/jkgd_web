const jkda_index = () => import('@/components/jkda/jkda_index')
const jkda_grzl = () => import('@/components/jkda/jkda_grzl')
const jkda_jkzb = () => import('@/components/jkda/jkda_jkzb')
const jkda_jks = () => import('@/components/jkda/jkda_jks')
const jkda_ylda = () => import('@/components/jkda/jkda_ylda')
const jkda_ylda_qxsz = () => import('@/components/jkda/jkda_ylda_qxsz')//医疗档案权限设置
const jkda_tjbg = () => import('@/components/jkda/jkda_tjbg')
const EMR_OUTPATIENT_REGISTER = () => import('@/components/jkda/ehr/EMR_OUTPATIENT_REGISTER')//门诊
const EMR_ADMISSION_REGISTER = () => import('@/components/jkda/ehr/EMR_ADMISSION_REGISTER')//入院
const a06 = () => import('@/components/jkda/ehr/a05')//入院
const PHS_2T5VBP = () => import('@/components/jkda/ehr/PHS_2T5VBP')//孕产复检记录表
const PHS_CHILDBIRTH_INF = () => import('@/components/jkda/ehr/PHS_CHILDBIRTH_INF')//分娩情况表
const PHS_MATERNAL_MANUAL = () => import('@/components/jkda/ehr/PHS_MATERNAL_MANUAL')//孕产妇保健手册和初检记录
const PHS_CHILD_PHYSICAL_EXAM = () => import('@/components/jkda/ehr/PHS_CHILD_PHYSICAL_EXAM')//儿童健康体检
const PHS_BIRTH_CERT = () => import('@/components/jkda/ehr/PHS_BIRTH_CERT')//出生證明
const PHS_CHILD_MANUAL = () => import('@/components/jkda/ehr/PHS_CHILD_MANUAL')//儿童保健手册
 
const EMR_PHYSICAL_EXAM_REPORT = () => import('@/components/jkda/ehr/EMR_PHYSICAL_EXAM_REPORT')//体检报告
const PHS_PHYSICAL_EXAM = () => import('@/components/jkda/ehr/PHS_PHYSICAL_EXAM')//健康体检
const EMR_ICH = () => import('@/components/jkda/ehr/EMR_ICH')//住院病案首页
const PHS_HBP_CARD = () => import('@/components/jkda/ehr/PHS_HBP_CARD')//高血压患者管理登记表 
const PHS_HCV = () => import('@/components/jkda/ehr/PHS_HCV')//高血压患者随访表 
const PHS_DM_CARD = () => import('@/components/jkda/ehr/PHS_DM_CARD')//糖尿病患者管理登记表 
const PHS_DCV = () => import('@/components/jkda/ehr/PHS_DCV')//糖尿病患者随访表 
const PHS_CHD_CARD = () => import('@/components/jkda/ehr/PHS_CHD_CARD')//冠心病患者管理登记表 
const PHS_CHD_VISIT = () => import('@/components/jkda/ehr/PHS_CHD_VISIT')//冠心病患者随访表 
const PHS_TB_CARD = () => import('@/components/jkda/ehr/PHS_TB_CARD')//结核病患者管理登记表 
const PHS_TB_VISIT = () => import('@/components/jkda/ehr/PHS_TB_VISIT')//结核病患者随访表 
const PHS_STROKE_CARD = () => import('@/components/jkda/ehr/PHS_STROKE_CARD')//脑卒中患者管理登记表 
const PHS_STROKE_VISIT = () => import('@/components/jkda/ehr/PHS_STROKE_VISIT')//脑卒中患者随访表 
const PHS_TUMOR_CARD = () => import('@/components/jkda/ehr/PHS_TUMOR_CARD')//肿瘤患者管理登记表 
const PHS_TUMOR_VISIT = () => import('@/components/jkda/ehr/PHS_TUMOR_VISIT')//肿瘤患者随访表 
const PHS_PARTURITION_VISIT = () => import('@/components/jkda/ehr/PHS_PARTURITION_VISIT')//产后访问记录表 
const PHS_MATERNAL_DEATH = () => import('@/components/jkda/ehr/PHS_MATERNAL_DEATH')//孕妇死亡记录表  
const PHS_FOLIC_ACID_REG = () => import('@/components/jkda/ehr/PHS_FOLIC_ACID_REG')//发放叶酸记录表  
const PHS_AGED_CARD = () => import('@/components/jkda/ehr/PHS_AGED_CARD')//老年人专项健康档案  
const PHS_AGED_VISIT = () => import('@/components/jkda/ehr/PHS_AGED_VISIT')//老年人随访 
const PHS_AGED_TCM_RECORD = () => import('@/components/jkda/ehr/PHS_AGED_TCM_RECORD')//老年人中医药保健 
const PHS_AGED_SELFCARE_EVALUATE = () => import('@/components/jkda/ehr/PHS_AGED_SELFCARE_EVALUATE')//老年人自理评估资料  
const PHS_FRAIL_CHILD_CARD = () => import('@/components/jkda/ehr/PHS_FRAIL_CHILD_CARD')//体弱儿专项卡  
const PHS_MENTAL_VISIT = () => import('@/components/jkda/ehr/PHS_MENTAL_VISIT')//重性精神病随访记录  
const PHS_MRC = () => import('@/components/jkda/ehr/PHS_MRC')//重性精神病个人信息  
const PHS_NABV = () => import('@/components/jkda/ehr/PHS_NABV')//产后新生儿访视  
const PHS_NEONATAL_DISEASE_SCREENING = () => import('@/components/jkda/ehr/PHS_NEONATAL_DISEASE_SCREENING')//新生儿疾病筛查 
const PHS_TWO_CANCER_REG = () => import('@/components/jkda/ehr/PHS_TWO_CANCER_REG')//两癌筛查登记表 
const PHS_WOMEN_CENSUS_REG = () => import('@/components/jkda/ehr/PHS_WOMEN_CENSUS_REG')//妇女病普查登记表 
const PHS_P42E = () => import('@/components/jkda/ehr/PHS_P42E')//产后42天 
const mbyyjl = () => import('@/components/jkda/ehr/mbyyjl')//有三种慢性病用用药记录，PHS_HCV_PHARMACY(高血压) ，PHS_DCV_PHARMACY（糖尿病），PHS_TUMOR_VISIT_PHARMACY（肿瘤）
var routes = [{
  path: '/a06',
  name: 'a06',
  component: a06
},{
  path: '/jkda_index',
  name: 'jkda_index',
  component: jkda_index
},{
  path: '/jkda_grzl',
  name: 'jkda_grzl',
  component: jkda_grzl
},{
  path: '/jkda_jkzb',
  name: 'jkda_jkzb',
  component: jkda_jkzb
},{
  path: '/jkda_jks',
  name: 'jkda_jks',
  component: jkda_jks
},{
  path: '/jkda_ylda',
  name: 'jkda_ylda',
  component: jkda_ylda
},{
  path: '/jkda_ylda_qxsz',
  name: 'jkda_ylda_qxsz',
  component: jkda_ylda_qxsz
},{
  path: '/jkda_tjbg',
  name: 'jkda_tjbg',
  component: jkda_tjbg
},{
  path: '/EMR_OUTPATIENT_REGISTER',
  name: 'EMR_OUTPATIENT_REGISTER',
  component: EMR_OUTPATIENT_REGISTER
},{
  path: '/EMR_ADMISSION_REGISTER',
  name: 'EMR_ADMISSION_REGISTER',
  component: EMR_ADMISSION_REGISTER
},{
  path: '/PHS_2T5VBP',
  name: 'PHS_2T5VBP',
  component: PHS_2T5VBP
},{
  path: '/PHS_CHILDBIRTH_INF',
  name: 'PHS_CHILDBIRTH_INF',
  component: PHS_CHILDBIRTH_INF
},{
  path: '/PHS_MATERNAL_MANUAL',
  name: 'PHS_MATERNAL_MANUAL',
  component: PHS_MATERNAL_MANUAL
},{
  path: '/PHS_CHILD_PHYSICAL_EXAM',
  name: 'PHS_CHILD_PHYSICAL_EXAM',
  component: PHS_CHILD_PHYSICAL_EXAM
},{
  path: '/PHS_BIRTH_CERT',
  name: 'PHS_BIRTH_CERT',
  component: PHS_BIRTH_CERT
},{
  path: '/PHS_CHILD_MANUAL',
  name: 'PHS_CHILD_MANUAL',
  component: PHS_CHILD_MANUAL
},{
  path: '/EMR_ICH',
  name: 'EMR_ICH',
  component: EMR_ICH
},{
  path: '/EMR_PHYSICAL_EXAM_REPORT',
  name: 'EMR_PHYSICAL_EXAM_REPORT',
  component: EMR_PHYSICAL_EXAM_REPORT
},{
  path: '/PHS_PHYSICAL_EXAM',
  name: 'PHS_PHYSICAL_EXAM',
  component: PHS_PHYSICAL_EXAM
},{
  path: '/PHS_HBP_CARD',
  name: 'PHS_HBP_CARD',
  component: PHS_HBP_CARD
},{
  path: '/PHS_HCV',
  name: 'PHS_HCV',
  component: PHS_HCV
},{
  path: '/PHS_DM_CARD',
  name: 'PHS_DM_CARD',
  component: PHS_DM_CARD
},{
  path: '/PHS_DCV',
  name: 'PHS_DCV',
  component: PHS_DCV
},{
  path: '/PHS_CHD_CARD',
  name: 'PHS_CHD_CARD',
  component: PHS_CHD_CARD
},{
  path: '/PHS_CHD_VISIT',
  name: 'PHS_CHD_VISIT',
  component: PHS_CHD_VISIT
},{
  path: '/PHS_TB_CARD',
  name: 'PHS_TB_CARD',
  component: PHS_TB_CARD
},{
  path: '/PHS_TB_VISIT',
  name: 'PHS_TB_VISIT',
  component: PHS_TB_VISIT
},{
  path: '/PHS_STROKE_CARD',
  name: 'PHS_STROKE_CARD',
  component: PHS_STROKE_CARD
},{
  path: '/PHS_STROKE_VISIT',
  name: 'PHS_STROKE_VISIT',
  component: PHS_STROKE_VISIT
},{
  path: '/PHS_TUMOR_CARD',
  name: 'PHS_TUMOR_CARD',
  component: PHS_TUMOR_CARD
},{
  path: '/PHS_TUMOR_VISIT',
  name: 'PHS_TUMOR_VISIT',
  component: PHS_TUMOR_VISIT
},{
  path: '/PHS_PARTURITION_VISIT',
  name: 'PHS_PARTURITION_VISIT',
  component: PHS_PARTURITION_VISIT
},{
  path: '/PHS_MATERNAL_DEATH',
  name: 'PHS_MATERNAL_DEATH',
  component: PHS_MATERNAL_DEATH
},{
  path: '/PHS_FOLIC_ACID_REG',
  name: 'PHS_FOLIC_ACID_REG',
  component: PHS_FOLIC_ACID_REG
},{
  path: '/PHS_AGED_CARD',
  name: 'PHS_AGED_CARD',
  component: PHS_AGED_CARD
},{
  path: '/PHS_AGED_VISIT',
  name: 'PHS_AGED_VISIT',
  component: PHS_AGED_VISIT
},{
  path: '/PHS_AGED_TCM_RECORD',
  name: 'PHS_AGED_TCM_RECORD',
  component: PHS_AGED_TCM_RECORD
},{
  path: '/PHS_AGED_SELFCARE_EVALUATE',
  name: 'PHS_AGED_SELFCARE_EVALUATE',
  component: PHS_AGED_SELFCARE_EVALUATE
},{
  path: '/PHS_FRAIL_CHILD_CARD',
  name: 'PHS_FRAIL_CHILD_CARD',
  component: PHS_FRAIL_CHILD_CARD
},{
  path: '/PHS_MENTAL_VISIT',
  name: 'PHS_MENTAL_VISIT',
  component: PHS_MENTAL_VISIT
},{
  path: '/PHS_MRC',
  name: 'PHS_MRC',
  component: PHS_MRC
},{
  path: '/PHS_NABV',
  name: 'PHS_NABV',
  component: PHS_NABV
},{
  path: '/PHS_NEONATAL_DISEASE_SCREENING',
  name: 'PHS_NEONATAL_DISEASE_SCREENING',
  component: PHS_NEONATAL_DISEASE_SCREENING
},{
  path: '/PHS_TWO_CANCER_REG',
  name: 'PHS_TWO_CANCER_REG',
  component: PHS_TWO_CANCER_REG
},{
  path: '/PHS_WOMEN_CENSUS_REG',
  name: 'PHS_WOMEN_CENSUS_REG',
  component: PHS_WOMEN_CENSUS_REG
},{
  path: '/PHS_P42E',
  name: 'PHS_P42E',
  component: PHS_P42E
},{
  path: '/mbyyjl',
  name: 'mbyyjl',
  component: mbyyjl
}]

export default routes
