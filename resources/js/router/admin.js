import { createRouter, createWebHistory } from "vue-router";
import store from '@/js/stores'

import NotFound from '@/js/components_admin/NotFound';
import Dashboard from '@/js/components_admin/Dashboard';
import Report from '@/js/components_admin/Report/Report';
//masterdata
import Kategori from '@/js/components_admin/Masterdata/Kategori/Index';
import KategoriAdd from '@/js/components_admin/Masterdata/Kategori/Add';
import KategoriEdit from '@/js/components_admin/Masterdata/Kategori/Edit';
import KategoriTable from '@/js/components_admin/Masterdata/Kategori/Table';

import Rekening from '@/js/components_admin/Masterdata/Rekening/Index';
import RekeningAdd from '@/js/components_admin/Masterdata/Rekening/Add';
import RekeningEdit from '@/js/components_admin/Masterdata/Rekening/Edit';
import RekeningTable from '@/js/components_admin/Masterdata/Rekening/Table';

import Admin from '@/js/components_admin/Masterdata/Admin/Index';
import AdminAdd from '@/js/components_admin/Masterdata/Admin/Add';
import AdminEdit from '@/js/components_admin/Masterdata/Admin/Edit';
import AdminTable from '@/js/components_admin/Masterdata/Admin/Table';

import tuk from '@/js/components_admin/Masterdata/Tuk/Index';
import tukAdd from '@/js/components_admin/Masterdata/Tuk/Add';
import tukEdit from '@/js/components_admin/Masterdata/Tuk/Edit';
import tukTable from '@/js/components_admin/Masterdata/Tuk/Table';

import Pelatihan from '@/js/components_admin/Masterdata/Pelatihan/Index';
import PelatihanAdd from '@/js/components_admin/Masterdata/Pelatihan/Add';
import PelatihanEdit from '@/js/components_admin/Masterdata/Pelatihan/Edit';
import PelatihanTable from '@/js/components_admin/Masterdata/Pelatihan/Table';
import PelatihanPeserta from '@/js/components_admin/Masterdata/Pelatihan/Peserta';

import Ujian from '@/js/components_admin/Masterdata/Ujian/Index';
import UjianAdd from '@/js/components_admin/Masterdata/Ujian/Add';
import UjianEdit from '@/js/components_admin/Masterdata/Ujian/Edit';
import UjianTable from '@/js/components_admin/Masterdata/Ujian/Table';
import UjianPeserta from '@/js/components_admin/Masterdata/Ujian/Peserta';

import Kuis from '@/js/components_admin/Masterdata/Kuis/Index';
import KuisAdd from '@/js/components_admin/Masterdata/Kuis/Add';
import KuisEdit from '@/js/components_admin/Masterdata/Kuis/Edit';
import KuisTable from '@/js/components_admin/Masterdata/Kuis/Table';
//main
import Pendaftaran from '@/js/components_admin/Pendaftaran/Index';
import PendaftaranAdd from '@/js/components_admin/Pendaftaran/Add';
import PendaftaranEdit from '@/js/components_admin/Pendaftaran/Edit';
import PendaftaranTable from '@/js/components_admin/Pendaftaran/Table';
import PendaftaranUjian from '@/js/components_admin/Pendaftaran/Ujian';

import Pengaduan from '@/js/components_admin/Pengaduan/Index';
import PengaduanTable from '@/js/components_admin/Pengaduan/Table';

import User from '@/js/components_admin/User/Index';
import UserAdd from '@/js/components_admin/User/Add';
import UserEdit from '@/js/components_admin/User/Edit';
import UserTable from '@/js/components_admin/User/Table';
//website
import WebsiteInformasi from '@/js/components_admin/Website/Informasi';
import WebsiteBeranda from '@/js/components_admin/Website/Beranda';
import WebsiteTentang from '@/js/components_admin/Website/Tentang';
import WebsiteBantuan from '@/js/components_admin/Website/Bantuan';
import WebsiteKontak from '@/js/components_admin/Website/Kontak';
import WebsiteNarasi from '@/js/components_admin/Website/Narasi';

import Partner from '@/js/components_admin/Masterdata/Partner/Index';
import PartnerAdd from '@/js/components_admin/Masterdata/Partner/Add';
import PartnerEdit from '@/js/components_admin/Masterdata/Partner/Edit';
import PartnerTable from '@/js/components_admin/Masterdata/Partner/Table';

import Galeri from '@/js/components_admin/Masterdata/Galeri/Index';
import GaleriAdd from '@/js/components_admin/Masterdata/Galeri/Add';
import GaleriEdit from '@/js/components_admin/Masterdata/Galeri/Edit';
import GaleriTable from '@/js/components_admin/Masterdata/Galeri/Table';

import Blog from '@/js/components_admin/Masterdata/Blog/Index';
import BlogAdd from '@/js/components_admin/Masterdata/Blog/Add';
import BlogEdit from '@/js/components_admin/Masterdata/Blog/Edit';
import BlogTable from '@/js/components_admin/Masterdata/Blog/Table';

import Akun from '@/js/components_admin/Akun';

import Schedule from '@/js/components_admin/Masterdata/Schedule/Index';
import ScheduleAdd from '@/js/components_admin/Masterdata/Schedule/Add';
import ScheduleEdit from '@/js/components_admin/Masterdata/Schedule/Edit';
import ScheduleTable from '@/js/components_admin/Masterdata/Schedule/Table';

import Competency from '@/js/components_admin/Masterdata/Competency/Index';
import CompetencyAdd from '@/js/components_admin/Masterdata/Competency/Add';
import CompetencyEdit from '@/js/components_admin/Masterdata/Competency/Edit';
import CompetencyTable from '@/js/components_admin/Masterdata/Competency/Table';

import CompetencyElementCriteria from '@/js/components_admin/Masterdata/CompetencyElementCriteria/Index';
import CompetencyElementCriteriaAdd from '@/js/components_admin/Masterdata/CompetencyElementCriteria/Add';
import CompetencyElementCriteriaEdit from '@/js/components_admin/Masterdata/CompetencyElementCriteria/Edit';
import CompetencyElementCriteriaTable from '@/js/components_admin/Masterdata/CompetencyElementCriteria/Table';

import CompetencyElement from '@/js/components_admin/Masterdata/CompetencyElement/Index';
import CompetencyElementAdd from '@/js/components_admin/Masterdata/CompetencyElement/Add';
import CompetencyElementEdit from '@/js/components_admin/Masterdata/CompetencyElement/Edit';
import CompetencyElementTable from '@/js/components_admin/Masterdata/CompetencyElement/Table';

//main
import SertifikasiPenguji from '@/js/components_admin/SertifikasiPenguji/Index';
import SertifikasiPengujiTable from '@/js/components_admin/SertifikasiPenguji/Table';
import SertifikasiAdmin from '@/js/components_admin/Pendaftaran/Sertifikasi';

import PelaporanKinerja from '@/js/components_admin/Pendaftaran/Pelaporan';
import Kompetensi from '@/js/components_admin/Pendaftaran/Kompetensi';
import Evaluasi from '@/js/components_admin/Pendaftaran/Evaluasi';

const adminrouter = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes: [
        {
          path: '/app/dashboard',
          component: Dashboard
        },
        {
          path: '/app/account',
          component: Akun
        },
        {
          path: '/app/masterdata/area',
          component: () => import('@/js/pages/admin/masterdata/area/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/masterdata/area/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Area',
                  index:'/app/masterdata/area'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/masterdata/area/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Area',
                  index:'/app/masterdata/area'
                },
              },
              {
                path: 'edit/:id',
                name: 'editarea',
                component: () => import('@/js/pages/admin/masterdata/area/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Area',
                  index:'/app/masterdata/area'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/subject',
          component: () => import('@/js/pages/admin/masterdata/subject/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/masterdata/subject/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Subject',
                  index:'/app/masterdata/subject'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/masterdata/subject/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Subject',
                  index:'/app/masterdata/subject'
                },
              },
              {
                path: 'edit/:id',
                name: 'editsubject',
                component: () => import('@/js/pages/admin/masterdata/subject/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Subject',
                  index:'/app/masterdata/subject'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/level',
          component: () => import('@/js/pages/admin/masterdata/level/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/masterdata/level/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Level',
                  index:'/app/masterdata/level'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/masterdata/level/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Level',
                  index:'/app/masterdata/level'
                },
              },
              {
                path: 'edit/:id',
                name: 'editlevel',
                component: () => import('@/js/pages/admin/masterdata/level/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Level',
                  index:'/app/masterdata/level'
                },
              }
          ]
        },
        {
          path: '/app/parent',
          component: () => import('@/js/pages/admin/parent/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/parent/ParentTable.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Parent',
                  index:'/app/parent'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/parent/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Parent',
                  index:'/app/parent'
                },
              },
              {
                path: 'edit/:id',
                name: 'editparent',
                component: () => import('@/js/pages/admin/parent/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Parent',
                  index:'/app/parent'
                },
              }
          ]
        },

        {
          path: '/app/tutor',
          component: () => import('@/js/pages/admin/tutor/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/tutor/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Tutor',
                  index:'/app/tutor'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/tutor/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Tutor',
                  index:'/app/tutor'
                },
              },
              {
                path: 'edit/:id',
                name: 'edittutor',
                component: () => import('@/js/pages/admin/tutor/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Tutor',
                  index:'/app/tutor'
                },
              },
              {
                path: 'detail/:id',
                name: 'detailtutor',
                component: () => import('@/js/pages/admin/tutor/detail/Index.vue'),
                redirect:{name:'detailtutor.info'},
                props:{
                  section: 'Masterdata',
                  title:'Tutor',
                  index:'/app/tutor'
                },
                children:[
                  {
                    path:'info',
                    name:'detailtutor.info',
                    component: () => import('@/js/pages/admin/tutor/detail/Info.vue')
                  },
                  {
                    path:'rate',
                    name:'detailtutor.rate',
                    component: () => import('@/js/pages/admin/tutor/detail/Rate.vue')
                  },
                  {
                    path:'experience',
                    name:'detailtutor.experience',
                    component: () => import('@/js/pages/admin/tutor/detail/Experience.vue')
                  },
                  {
                    path:'qualification',
                    name:'detailtutor.qualification',
                    component: () => import('@/js/pages/admin/tutor/detail/Qualification.vue')
                  },
                  {
                    path:'subject',
                    name:'detailtutor.subject',
                    component: () => import('@/js/pages/admin/tutor/detail/Subject.vue')
                  },
                  {
                    path:'area',
                    name:'detailtutor.area',
                    component: () => import('@/js/pages/admin/tutor/detail/Area.vue')
                  }
                ]
              }
          ]
        },

        {
          path: '/app/consultant',
          component: () => import('@/js/pages/admin/consultant/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/consultant/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Consultant',
                  index:'/app/consultant'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/consultant/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Consultant',
                  index:'/app/consultant'
                },
              },
              {
                path: 'edit/:id',
                name: 'editconsultant',
                component: () => import('@/js/pages/admin/consultant/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Consultant',
                  index:'/app/consultant'
                },
              }
          ]
        },

        {
          path: '/app/payment',
          component: () => import('@/js/pages/admin/payment/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/payment/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Payment',
                  index:'/app/payment'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/payment/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Payment',
                  index:'/app/payment'
                },
              },
              {
                path: 'edit/:id',
                name: 'editpayment',
                component: () => import('@/js/pages/admin/payment/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Payment',
                  index:'/app/payment'
                },
              }
          ]
        },

        {
          path: '/app/request-tutor',
          component: () => import('@/js/pages/admin/request-tutor/Index.vue'),
          children: [
              {
                path: '',
                component: () => import('@/js/pages/admin/request-tutor/Table.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Request Tutor',
                  index:'/app/request-tutor'
                },
              },
              {
                path: 'add',
                component: () => import('@/js/pages/admin/request-tutor/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Request Tutor',
                  index:'/app/request-tutor'
                },
              },
              {
                path: 'edit/:id',
                name: 'editrequesttutor',
                component: () => import('@/js/pages/admin/request-tutor/Form.vue'),
                props:{
                  section: 'Masterdata',
                  title:'Request Tutor',
                  index:'/app/request-tutor'
                },
              }
          ]
        },

        {
          path: '/app/website/informasi',
          component: WebsiteInformasi,
          props:{
            section: 'Website',
            title:'Informasi',
            index:'informasi'
          },
        },
        {
          path: '/app/website/beranda',
          component: WebsiteBeranda,
          props:{
            section: 'Website',
            title:'Beranda',
            index:'beranda'
          },
        },
        {
          path: '/app/website/tentang',
          component: WebsiteTentang,
          props:{
            section: 'Website',
            title:'Tentang',
            index:'tentang'
          },
        },
        {
          path: '/app/website/bantuan',
          component: WebsiteBantuan,
          props:{
            section: 'Website',
            title:'Informasi',
            index:'Informasi'
          },
        },
        {
          path: '/app/website/kontak',
          component: WebsiteKontak,
          props:{
            section: 'Website',
            title:'Kontak',
            index:'Kontak'
          },
        },
        {
          path: '/app/website/narasi',
          component: WebsiteNarasi,
          props:{
            section: 'Website',
            title:'Narasi',
            index:'Narasi'
          },
        },
        {
          path: '/app/masterdata/kategori',
          component: Kategori,
          children: [
              {
                path: '',
                component: KategoriTable,
                props:{
                  section: 'Masterdata',
                  title:'Kategori',
                  index:'/app/masterdata/kategori'
                },
              },
              {
                path: 'add',
                component: KategoriAdd,
                props:{
                  section: 'Masterdata',
                  title:'Kategori',
                  index:'/app/masterdata/kategori'
                },
              },
              {
                path: 'edit/:id',
                name: 'editkategori',
                component: KategoriEdit,
                props:{
                  section: 'Masterdata',
                  title:'Kategori',
                  index:'/app/masterdata/kategori'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/partner',
          component: Partner,
          children: [
              {
                path: '',
                component: PartnerTable,
                props:{
                  section: 'Masterdata',
                  title:'Partner',
                  index:'/app/masterdata/partner'
                },
              },
              {
                path: 'add',
                component: PartnerAdd,
                props:{
                  section: 'Masterdata',
                  title:'Partner',
                  index:'/app/masterdata/partner'
                },
              },
              {
                path: 'edit',
                name: 'editpartner',
                component: PartnerEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/masterdata/galeri',
          component: Galeri,
          children: [
              {
                path: '',
                component: GaleriTable,
                props:{
                  section: 'Masterdata',
                  title:'Galeri',
                  index:'/app/masterdata/galeri'
                },
              },
              {
                path: 'add',
                component: GaleriAdd,
                props:{
                  section: 'Masterdata',
                  title:'Galeri',
                  index:'/app/masterdata/galeri'
                },
              },
              {
                path: 'edit',
                name: 'editgaleri',
                component: GaleriEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/masterdata/artikel',
          component: Blog,
          children: [
              {
                path: '',
                component: BlogTable,
                props:{
                  section: 'Masterdata',
                  title:'Artikel',
                  index:'/app/masterdata/artikel'
                },
              },
              {
                path: 'add',
                component: BlogAdd,
                props:{
                  section: 'Masterdata',
                  title:'Artikel',
                  index:'/app/masterdata/artikel'
                },
              },
              {
                path: 'edit/:id',
                name: 'editblog',
                component: BlogEdit,
                props:{
                  section: 'Masterdata',
                  title:'Artikel',
                  index:'/app/masterdata/artikel'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/rekening',
          component: Rekening,
          children: [
              {
                path: '',
                component: RekeningTable,
                props:{
                  section: 'Masterdata',
                  title:'Rekening',
                  index:'/app/masterdata/rekening'
                },
              },
              {
                path: 'add',
                component: RekeningAdd,
                props:{
                  section: 'Masterdata',
                  title:'Rekening',
                  index:'/app/masterdata/rekening'
                },
              },
              {
                path: 'edit',
                name: 'editrekening',
                component: RekeningEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/masterdata/admin',
          component: Admin,
          children: [
              {
                path: '',
                component: AdminTable,
                props:{
                  section: 'Masterdata',
                  title:'Penguji',
                  index:'/app/masterdata/admin'
                },
              },
              {
                path: 'add',
                component: AdminAdd,
                props:{
                  section: 'Masterdata',
                  title:'Penguji',
                  index:'/app/masterdata/admin'
                },
              },
              {
                path: 'edit/:id',
                name: 'editadmin',
                component: AdminEdit,
                props:{
                  section: 'Masterdata',
                  title:'Penguji',
                  index:'/app/masterdata/admin'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/tuk',
          component: tuk,
          children: [
              {
                path: '',
                component: tukTable,
                props:{
                  section: 'Masterdata',
                  title:'TUK',
                  index:'/app/masterdata/tuk'
                },
              },
              {
                path: 'add',
                component: tukAdd,
                props:{
                  section: 'Masterdata',
                  title:'TUK',
                  index:'/app/masterdata/tuk'
                },
              },
              {
                path: 'edit/:id',
                name: 'edittuk',
                component: tukEdit,
                props:{
                  section: 'Masterdata',
                  title:'TUK',
                  index:'/app/masterdata/tuk'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/pelatihan',
          component: Pelatihan,
          children: [
              {
                path: '',
                component: PelatihanTable,
                props:{
                  section: 'Masterdata',
                  title:'Skema',
                  index:'/app/masterdata/pelatihan'
                },
              },
              {
                path: 'add',
                component: PelatihanAdd,
                props:{
                  section: 'Masterdata',
                  title:'Skema',
                  index:'/app/masterdata/pelatihan'
                },
              },
              {
                path: 'edit/:id',
                name: 'editpelatihan',
                component: PelatihanEdit,
                props:{
                  section: 'Masterdata',
                  title:'Skema',
                  index:'/app/masterdata/pelatihan'
                },
              },
              {
                path: 'peserta',
                name: 'pesertapelatihan',
                component: PelatihanPeserta,
                props:true
              }
          ]
        },
        {
          path: '/app/masterdata/ujian',
          component: Ujian,
          children: [
              {
                path: '',
                component: UjianTable,
                props:{
                  section: 'Masterdata',
                  title:'Kompetensi',
                  index:'/app/masterdata/ujian'
                },
              },
              {
                path: 'add',
                component: UjianAdd,
                props:{
                  section: 'Masterdata',
                  title:'Kompetensi',
                  index:'/app/masterdata/ujian'
                },
              },
              {
                path: 'edit/:id',
                name: 'editujian',
                component: UjianEdit,
                props:{
                  section: 'Masterdata',
                  title:'Kompetensi',
                  index:'/app/masterdata/ujian'
                },
              },
              {
                path: 'peserta/:id',
                name: 'pesertaujian',
                component: UjianPeserta,
                props:{
                  section: 'Masterdata',
                  title:'Kompetensi',
                  index:'/app/masterdata/ujian'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/kuis',
          component: Kuis,
          children: [
              {
                path: '',
                component: KuisTable,
                props:{
                  section: 'Masterdata',
                  title:'Kuis',
                  index:'/app/masterdata/kuis'
                },
              },
              {
                path: 'add',
                component: KuisAdd,
                props:{
                  section: 'Masterdata',
                  title:'Kuis',
                  index:'/app/masterdata/kuis'
                },
              },
              {
                path: 'edit',
                name: 'editkuis',
                component: KuisEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/pendaftaran',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: PendaftaranTable,
                props:{
                  section: 'Main',
                  title:'Pendaftaran Skema',
                  index:'/app/pendaftaran'
                },
              },
          ]
        },

        {
          path: '/app/pendaftarankompetensi',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: PendaftaranUjian,
                props:{
                  section: 'Main',
                  title:'Pendaftaran Kompetensi',
                  index:'/app/pendaftarankompetensi'
                },
              },
          ]
        },

        {
          path: '/app/kompetensi',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: Kompetensi,
                props:{
                  section: 'Main',
                  title:'Pemeliharaan Kompetensi',
                  index:'/app/kompetensi'
                },
              },
          ]
        },

        {
          path: '/app/evaluasi',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: Evaluasi,
                props:{
                  section: 'Main',
                  title:'Evaluasi',
                  index:'/app/evaluasi'
                },
              },
          ]
        },

        {
          path: '/app/sertifikasi',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: SertifikasiAdmin,
                props:{
                  section: 'Main',
                  title:'Sertifikasi',
                  index:'/app/sertifikasi'
                },
              },
          ]
        },

        {
          path: '/app/pelaporan',
          component: Pendaftaran,
          children: [
              {
                path: '',
                component: PelaporanKinerja,
                props:{
                  section: 'Main',
                  title:'Pelaporan Kinerja',
                  index:'/app/pelaporan'
                },
              },
          ]
        },
        {
          path: '/app/pengaduan',
          component: Pengaduan,
          children: [
              {
                path: '',
                component: PengaduanTable,
                props:{
                  section: 'Main',
                  title:'Pengaduan',
                  index:'/app/pengaduan'
                },
              },
          ]
        },
        {
          path: '/app/user',
          component: User,
          children: [
              {
                path: '',
                component: UserTable,
                props:{
                  section: 'Main',
                  title:'User',
                  index:'/app/user'
                },
              },
              {
                path: 'add',
                component: UserAdd,
                props:{
                  section: 'Main',
                  title:'User',
                  index:'/app/user'
                },
              },
              {
                path: 'edit/:id',
                name: 'edituser',
                component: UserEdit,
                props:{
                  section: 'Main',
                  title:'User',
                  index:'/app/user'
                },
              }
          ]
        },
        {
          path: '/app/masterdata/jadwal',
          component: Schedule,
          children: [
              {
                path: '',
                component: ScheduleTable,
                props:{
                  section: 'Masterdata',
                  title:'Jadwal',
                  index:'/app/masterdata/jadwal'
                },
              },
              {
                path: 'add',
                component: ScheduleAdd,
                props:{
                  section: 'Masterdata',
                  title:'Jadwal',
                  index:'/app/masterdata/jadwal'
                },
              },
              {
                path: 'edit',
                name: 'editjadwal',
                component: ScheduleEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/kompetensi/kompetensi',
          component: Competency,
          children: [
              {
                path: '',
                component: CompetencyTable,
                props:{
                  section: 'Kompetensi',
                  title:'Kompetensi',
                  index:'/app/kompetensi/kompetensi'
                },
              },
              {
                path: 'add',
                component: CompetencyAdd,
                props:{
                  section: 'kompetensi',
                  title:'Kompetensi',
                  index:'/app/kompetensi/kompetensi'
                },
              },
              {
                path: 'edit',
                name: 'editkompetensi',
                component: CompetencyEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/kompetensi/element',
          component: CompetencyElement,
          children: [
              {
                path: '',
                component: CompetencyElementTable,
                props:{
                  section: 'Kompetensi',
                  title:'Elemen Kompetensi',
                  index:'/app/kompetensi/element'
                },
              },
              {
                path: 'add',
                component: CompetencyElementAdd,
                props:{
                  section: 'kompetensi',
                  title:'Elemen Kompetensi',
                  index:'/app/kompetensi/element'
                },
              },
              {
                path: 'edit',
                name: 'editelement',
                component: CompetencyElementEdit,
                props:true
              }
          ]
        },

        {
          path: '/app/kompetensi/kriteria',
          component: CompetencyElementCriteria,
          children: [
              {
                path: '',
                component: CompetencyElementCriteriaTable,
                props:{
                  section: 'Kompetensi',
                  title:'Kriteria Kompetensi',
                  index:'/app/kompetensi/kriteria'
                },
              },
              {
                path: 'add',
                component: CompetencyElementCriteriaAdd,
                props:{
                  section: 'kompetensi',
                  title:'Kriteria Kompetensi',
                  index:'/app/kompetensi/kriteria'
                },
              },
              {
                path: 'edit',
                name: 'editkriteria',
                component: CompetencyElementCriteriaEdit,
                props:true
              }
          ]
        },
        {
          path: '/app/*',
          component: NotFound
        },
    ],
});

export default adminrouter;
