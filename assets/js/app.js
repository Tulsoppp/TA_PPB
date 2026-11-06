// ============================================================================
// SISTEM INFORMASI AKADEMIK (SIAKAD) KARTU RENCANA STUDI (KRS) TERINTEGRASI
// PROGRAM STUDI S1 TEKNIK INFORMATIKA - UNIVERSITAS TRUNODJOYO MADURA (UTM)
// ============================================================================

const STORAGE_KEY = 'SIAKAD_UTM_PRODI_FINAL_V1';

const REGISTERED_USERS = [
  {
    email: '240411100157@student.trunojoyo.ac.id',
    nim: '240411100157',
    name: 'Ahmad Maulana Ishaq',
    initials: 'AM',
    role: 'mahasiswa',
    roleLabel: 'Mahasiswa S1 Teknik Informatika',
    kelas: 'IF 7B',
    semester: 5,
    ips: '3.82',
    ipk: '3.78'
  },
  {
    email: '240411100119@student.trunojoyo.ac.id',
    nim: '240411100119',
    name: 'Muhammad Ali Murtadho',
    initials: 'MA',
    role: 'mahasiswa',
    roleLabel: 'Mahasiswa S1 Teknik Informatika',
    kelas: 'IF 7B',
    semester: 5,
    ips: '3.76',
    ipk: '3.72'
  },
  {
    email: '240411100131@student.trunojoyo.ac.id',
    nim: '240411100131',
    name: 'Mochammad Zaenal Abidin',
    initials: 'MZ',
    role: 'mahasiswa',
    roleLabel: 'Mahasiswa S1 Teknik Informatika',
    kelas: 'IF 7B',
    semester: 5,
    ips: '3.79',
    ipk: '3.75'
  },
  {
    email: '240411100137@student.trunojoyo.ac.id',
    nim: '240411100137',
    name: 'Deo Candra Saputra',
    initials: 'DC',
    role: 'mahasiswa',
    roleLabel: 'Mahasiswa S1 Teknik Informatika',
    kelas: 'IF 7B',
    semester: 5,
    ips: '3.70',
    ipk: '3.68'
  },
  {
    email: '240411100209@student.trunojoyo.ac.id',
    nim: '240411100209',
    name: 'M. Fatihul Umam',
    initials: 'MF',
    role: 'mahasiswa',
    roleLabel: 'Mahasiswa S1 Teknik Informatika',
    kelas: 'IF 7B',
    semester: 5,
    ips: '3.84',
    ipk: '3.80'
  },
  {
    email: 'rika.yunitarini@trunojoyo.ac.id',
    nip: '198306072006042001',
    name: 'Rika Yunitarini ST., MT.',
    initials: 'RY',
    role: 'dosen_wali',
    roleLabel: 'Dosen Wali / Pembimbing Akademik'
  },
  {
    email: 'advokasi.himatif@trunojoyo.ac.id',
    id: 'HIMATIF-ADV',
    name: 'Divisi Advokasi Himatif UTM',
    initials: 'HM',
    role: 'himatif',
    roleLabel: 'Pengurus Organisasi Mahasiswa (Himatif)'
  },
  {
    email: 'admin.informatika@trunojoyo.ac.id',
    id: 'ADM-TIF-UTM',
    name: 'Admin Akademik Prodi Teknik Informatika',
    initials: 'TU',
    role: 'admin',
    roleLabel: 'Tata Usaha / Admin Program Studi'
  }
];

const INITIAL_COURSES = [
  {
    id: 'MK01',
    code: 'IF24501',
    name: 'Pemodelan Proses Bisnis',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Senin, 07.30 - 10.00 • R. Gedung F-201',
    lecturer: 'Rika Yunitarini ST., MT.',
    enrolled: 34,
    quota: 40,
    mandatory: true
  },
  {
    id: 'MK02',
    code: 'IF24502',
    name: 'Kecerdasan Buatan (Artificial Intelligence)',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Selasa, 09.30 - 12.00 • Lab Komputasi Cerdas',
    lecturer: 'Dr. Budi Dwi Satoto ST., M.Kom.',
    enrolled: 40,
    quota: 40,
    mandatory: true
  },
  {
    id: 'MK03',
    code: 'IF24503',
    name: 'Keamanan Informasi & Jaringan',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Rabu, 10.00 - 12.30 • Lab Jaringan Komputer',
    lecturer: 'Dr. Kautsar Sophan ST., MT.',
    enrolled: 40,
    quota: 40,
    mandatory: true
  },
  {
    id: 'MK04',
    code: 'IF24504',
    name: 'Rekayasa Perangkat Lunak Lanjut',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Kamis, 08.00 - 10.30 • R. Gedung F-204',
    lecturer: 'Fika Hastarita Rachman ST., M.Eng.',
    enrolled: 32,
    quota: 40,
    mandatory: true
  },
  {
    id: 'MK05',
    code: 'IF24505',
    name: 'Manajemen Proyek Teknologi Informasi',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Jumat, 08.00 - 10.30 • R. Gedung F-105',
    lecturer: 'Hermawan ST., M.Kom.',
    enrolled: 29,
    quota: 40,
    mandatory: true
  },
  {
    id: 'MK06',
    code: 'IF24506',
    name: 'Pemrograman Perangkat Bergerak (Mobile)',
    sks: 3,
    kelas: 'IF 7B',
    schedule: 'Kamis, 13.00 - 15.30 • Lab Rekayasa Perangkat Lunak',
    lecturer: 'Achmad Jauhari ST., M.Kom.',
    enrolled: 31,
    quota: 40,
    mandatory: false
  },
  {
    id: 'MK07',
    code: 'IF24507',
    name: 'Metodologi Penelitian Informatika',
    sks: 2,
    kelas: 'IF 7B',
    schedule: 'Senin, 13.00 - 14.40 • R. Gedung F-202',
    lecturer: 'Rika Yunitarini ST., MT.',
    enrolled: 33,
    quota: 40,
    mandatory: true
  }
];

const ROOM_LIST = [
  { id: 'RM1', name: 'Lab Komputasi Cerdas (Perluasan)', capacity: 55, slot: 'Selasa, 09.30 - 12.00 & Jumat, 13.00 - 15.30', status: 'Tersedia' },
  { id: 'RM2', name: 'Lab Jaringan Komputer (Perluasan)', capacity: 55, slot: 'Rabu, 10.00 - 12.30 & Jumat, 09.30 - 12.00', status: 'Tersedia' },
  { id: 'RM3', name: 'R. Gedung F-301 (Kelas Paralel IF 7C)', capacity: 45, slot: 'Jumat, 13.00 - 15.30 WIB', status: 'Tersedia' },
  { id: 'RM4', name: 'R. Gedung F-302 (Kelas Paralel IF 7C)', capacity: 45, slot: 'Sabtu, 08.00 - 10.30 WIB', status: 'Tersedia' }
];

const LECTURER_LIST = [
  { name: 'Dr. Budi Dwi Satoto ST., M.Kom.', field: 'Kecerdasan Buatan & Sistem Cerdas', load: '9 SKS', status: 'Siap Mengampu Kelas Tambahan' },
  { name: 'Dr. Kautsar Sophan ST., MT.', field: 'Keamanan Jaringan & Infrastruktur', load: '9 SKS', status: 'Siap Mengampu Kelas Tambahan' },
  { name: 'Rika Yunitarini ST., MT.', field: 'Pemodelan Proses Bisnis & Sistem Enterprise', load: '10 SKS', status: 'Siap Mengampu' },
  { name: 'Fika Hastarita Rachman ST., M.Eng.', field: 'Rekayasa Perangkat Lunak', load: '9 SKS', status: 'Siap Mengampu' }
];

let appState = loadAppState();
let currentUser = null;
let activeStudentTab = 'krs';
let activeAdvisorTab = 'queue';
let selectedStudentEmailForAdvisor = '240411100157@student.trunojoyo.ac.id';
let activeAdminTab = 'proposals';
let activeHimatifTab = 'incoming';

function getDefaultState() {
  const studentRecords = {};
  REGISTERED_USERS.filter(u => u.role === 'mahasiswa').forEach((mhs, idx) => {
    studentRecords[mhs.email] = {
      selectedCourses: idx === 1
        ? ['MK01', 'MK04', 'MK05', 'MK06', 'MK07']
        : ['MK01', 'MK04', 'MK05', 'MK07'],
      krsStatus: idx === 1 ? 'submitted' : 'draft',
      submittedAt: idx === 1 ? '21 Okt 2026, 08:45 WIB' : null,
      validatedAt: null,
      advisorNote: ''
    };
  });

  const initialReports = [
    {
      id: 'REP-2026-101',
      studentEmail: '240411100157@student.trunojoyo.ac.id',
      studentName: 'Ahmad Maulana Ishaq',
      studentNim: '240411100157',
      courseId: 'MK02',
      courseCode: 'IF24502',
      courseName: 'Kecerdasan Buatan (Artificial Intelligence)',
      urgency: 'Wajib Semester Ini (Prioritas Utama)',
      notes: 'Kuota kelas IF 7B penuh (40/40) saat hendak mengambil mata kuliah wajib semester 5.',
      status: 'Diusulkan ke Admin Prodi',
      verifiedByHimatif: true,
      createdAt: '21 Okt 2026, 08:15 WIB'
    },
    {
      id: 'REP-2026-102',
      studentEmail: '240411100131@student.trunojoyo.ac.id',
      studentName: 'Mochammad Zaenal Abidin',
      studentNim: '240411100131',
      courseId: 'MK02',
      courseCode: 'IF24502',
      courseName: 'Kecerdasan Buatan (Artificial Intelligence)',
      urgency: 'Wajib Semester Ini (Prioritas Utama)',
      notes: 'Mohon penambahan kapasitas kursi untuk kelas Kecerdasan Buatan.',
      status: 'Diusulkan ke Admin Prodi',
      verifiedByHimatif: true,
      createdAt: '21 Okt 2026, 08:22 WIB'
    }
  ];

  return {
    courses: JSON.parse(JSON.stringify(INITIAL_COURSES)),
    students: studentRecords,
    quotaReports: initialReports,
    broadcastMessage: null,
    adminReplyToHimatif: null,
    advisorLogs: [
      {
        time: '21 Okt 2026, 08:45 WIB',
        studentName: 'Muhammad Ali Murtadho',
        studentNim: '240411100119',
        action: 'Mengajukan Draf KRS (14 SKS) ke Dosen Wali',
        type: 'info'
      }
    ],
    adminLogs: [
      {
        time: '21 Okt 2026, 08:30 WIB',
        title: 'Dokumen Rekapitulasi Himatif Diterima',
        detail: 'Himatif menyerahkan usulan penambahan kapasitas untuk mata kuliah IF24502 - Kecerdasan Buatan.',
        type: 'proposal'
      }
    ],
    himatifLogs: [
      {
        time: '21 Okt 2026, 08:30 WIB',
        title: 'Penyerahan Rekapitulasi Tahap 1 ke Admin Prodi',
        detail: '2 data keluhan mahasiswa untuk mata kuliah IF24502 - Kecerdasan Buatan telah divalidasi dan diserahkan ke Admin Prodi.'
      }
    ]
  };
}

function loadAppState() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return getDefaultState();
    const parsed = JSON.parse(raw);
    if (!parsed.adminLogs) parsed.adminLogs = [];
    if (!parsed.advisorLogs) parsed.advisorLogs = [];
    if (!parsed.himatifLogs) parsed.himatifLogs = [];
    return parsed;
  } catch (e) {
    return getDefaultState();
  }
}

function saveAppState() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(appState));
}

function resetDemoData() {
  if (!confirm('Reset seluruh data simulasi kembali ke kondisi awal?')) return;
  appState = getDefaultState();
  saveAppState();
  if (currentUser) {
    loginAsUser(currentUser);
  }
  showToast('Data Direset', 'Seluruh data simulasi berhasil dikembalikan ke pengaturan awal.', '🔄');
}

// ============================================================================
// AUTENTIKASI LOGIN BERBASIS EMAIL RESMI
// ============================================================================
function handleLoginSubmit(e) {
  e.preventDefault();
  const emailRaw = document.getElementById('emailInput').value.trim().toLowerCase();
  const passRaw = document.getElementById('passwordInput').value.trim();

  const matchedUser = REGISTERED_USERS.find(u => u.email.toLowerCase() === emailRaw);
  const errBox = document.getElementById('loginErrorBox');

  if (!matchedUser) {
    errBox.classList.remove('hidden');
    document.getElementById('loginErrorTitle').textContent = 'Alamat Email Tidak Terdaftar';
    document.getElementById('loginErrorDesc').textContent =
      `Email "${emailRaw}" tidak ditemukan dalam Pangkalan Data Akademik S1 Teknik Informatika UTM. Pastikan Anda menggunakan email resmi @student.trunojoyo.ac.id atau @trunojoyo.ac.id.`;
    return;
  }

  if (passRaw.length < 3) {
    errBox.classList.remove('hidden');
    document.getElementById('loginErrorTitle').textContent = 'Kata Sandi Tidak Valid';
    document.getElementById('loginErrorDesc').textContent = 'Silakan masukkan kata sandi SIAKAD Anda dengan benar.';
    return;
  }

  errBox.classList.add('hidden');
  loginAsUser(matchedUser);
}

function loginAsUser(userObj) {
  currentUser = userObj;
  document.getElementById('loginScreen').classList.add('hidden');
  document.getElementById('appScreen').classList.remove('hidden');

  document.getElementById('navRoleBadge').textContent = userObj.roleLabel.toUpperCase();
  document.getElementById('navUserName').textContent = userObj.name;
  document.getElementById('navUserEmail').textContent = userObj.email;

  document.getElementById('studentPortalContainer').classList.add('hidden');
  document.getElementById('advisorPortalContainer').classList.add('hidden');
  document.getElementById('adminPortalContainer').classList.add('hidden');
  document.getElementById('himatifPortalContainer').classList.add('hidden');

  if (userObj.role === 'mahasiswa') {
    document.getElementById('studentPortalContainer').classList.remove('hidden');
    switchStudentTab('krs');
    renderStudentPortal();
  } else if (userObj.role === 'dosen_wali') {
    document.getElementById('advisorPortalContainer').classList.remove('hidden');
    switchAdvisorTab('queue');
    renderAdvisorPortal();
  } else if (userObj.role === 'admin') {
    document.getElementById('adminPortalContainer').classList.remove('hidden');
    switchAdminTab('proposals');
    renderAdminPortal();
  } else if (userObj.role === 'himatif') {
    document.getElementById('himatifPortalContainer').classList.remove('hidden');
    switchHimatifTab('incoming');
    renderHimatifPortal();
  }

  showToast('Login Berhasil', `Selamat datang, ${userObj.name}`);
}

function handleLogout() {
  currentUser = null;
  document.getElementById('loginForm').reset();
  document.getElementById('appScreen').classList.add('hidden');
  document.getElementById('loginScreen').classList.remove('hidden');
}

// ============================================================================
// 1. PORTAL MAHASISWA
// ============================================================================
function switchStudentTab(tabName) {
  activeStudentTab = tabName;
  ['krs', 'advokasi', 'cetak'].forEach(t => {
    const panel = document.getElementById(`studentTab_${t}`);
    const btn = document.getElementById(`tabBtn_${t}`);
    if (t === tabName) {
      panel.classList.remove('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-blue-900 text-white shadow';
    } else {
      panel.classList.add('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200';
    }
  });
  renderStudentPortal();
}

function getStudentRecord() {
  if (!currentUser || currentUser.role !== 'mahasiswa') return null;
  if (!appState.students[currentUser.email]) {
    appState.students[currentUser.email] = {
      selectedCourses: ['MK01', 'MK04'],
      krsStatus: 'draft',
      submittedAt: null,
      validatedAt: null,
      advisorNote: ''
    };
  }
  return appState.students[currentUser.email];
}

function renderStudentPortal() {
  if (!currentUser || currentUser.role !== 'mahasiswa') return;
  const rec = getStudentRecord();

  document.getElementById('mhsAvatar').textContent = currentUser.initials;
  document.getElementById('mhsFullName').textContent = currentUser.name;
  document.getElementById('mhsNim').textContent = currentUser.nim;
  document.getElementById('mhsClass').textContent = currentUser.kelas;
  document.getElementById('mhsIpk').textContent = `Sem ${currentUser.semester} / ${currentUser.ipk}`;

  const statusBadge = document.getElementById('mhsStatusBadge');
  if (rec.krsStatus === 'draft') {
    statusBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-300';
    statusBadge.textContent = 'Status KRS: Draf (Belum Diajukan)';
  } else if (rec.krsStatus === 'submitted') {
    statusBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300';
    statusBadge.textContent = 'Status KRS: Menunggu Validasi Dosen Wali';
  } else if (rec.krsStatus === 'approved') {
    statusBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300';
    statusBadge.textContent = 'Status KRS: Telah Disetujui (ACC Dosen Wali)';
  } else if (rec.krsStatus === 'revision') {
    statusBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-900 border border-rose-300';
    statusBadge.textContent = 'Status KRS: Dikembalikan untuk Revisi';
  }

  const noteBanner = document.getElementById('studentAdvisorNoteBanner');
  if (rec.advisorNote) {
    noteBanner.classList.remove('hidden');
    const isRev = rec.krsStatus === 'revision';
    noteBanner.className = `no-print rounded-2xl p-4 shadow-sm border flex flex-col sm:flex-row sm:items-center justify-between gap-4 ${
      isRev ? 'bg-rose-50 border-rose-200 text-rose-950' : 'bg-emerald-50 border-emerald-200 text-emerald-950'
    }`;
    noteBanner.innerHTML = `
      <div class="flex items-start gap-3">
        <span class="text-xl">${isRev ? '⚠️' : '✅'}</span>
        <div>
          <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded ${
            isRev ? 'bg-rose-200 text-rose-900' : 'bg-emerald-200 text-emerald-900'
          }">CATATAN DOSEN WALI (RIKA YUNITARINI ST., MT.)</span>
          <p class="text-xs font-semibold mt-1">"${rec.advisorNote}"</p>
        </div>
      </div>
    `;
  } else {
    noteBanner.classList.add('hidden');
  }

  const totalSks = rec.selectedCourses.reduce((acc, cid) => {
    const c = appState.courses.find(x => x.id === cid);
    return acc + (c ? c.sks : 0);
  }, 0);
  document.getElementById('mhsSelectedSks').textContent = `${totalSks} / 24 SKS`;
  document.getElementById('cartSksPill').textContent = `${totalSks} / 24 SKS`;

  const myReports = appState.quotaReports.filter(r => r.studentEmail === currentUser.email);
  document.getElementById('mhsReportCount').textContent = `${myReports.length} Laporan`;
  document.getElementById('badgeTabAdvokasi').textContent = myReports.length;

  const broadcastBanner = document.getElementById('studentBroadcastBanner');
  if (appState.broadcastMessage) {
    broadcastBanner.classList.remove('hidden');
    document.getElementById('studentBroadcastText').textContent = appState.broadcastMessage;
  } else {
    broadcastBanner.classList.add('hidden');
  }

  renderStudentCourses();
  renderStudentCart();
  renderStudentAdvokasiForm();
  renderStudentPrintKrs();
}

function renderStudentCourses() {
  const rec = getStudentRecord();
  const filterVal = document.getElementById('courseFilterSelect').value;
  const tbody = document.getElementById('studentCourseTableBody');

  const filtered = appState.courses.filter(c => {
    const isFull = c.enrolled >= c.quota;
    if (filterVal === 'available') return !isFull;
    if (filterVal === 'full') return isFull;
    return true;
  });

  tbody.innerHTML = filtered.map(c => {
    const isSelected = rec.selectedCourses.includes(c.id);
    const isFull = c.enrolled >= c.quota;
    const alreadyReported = appState.quotaReports.some(
      r => r.studentEmail === currentUser.email && r.courseCode === c.code
    );
    const lockedKrs = rec.krsStatus === 'submitted' || rec.krsStatus === 'approved';

    let quotaBadge = '';
    if (isFull) {
      quotaBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">PENUH (${c.enrolled}/${c.quota})</span>`;
    } else {
      quotaBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Tersedia (${c.enrolled}/${c.quota})</span>`;
    }

    let actionBtn = '';
    if (isSelected) {
      actionBtn = `
        <button ${lockedKrs ? 'disabled' : ''} onclick="removeCourseFromKrs('${c.id}')" class="px-3 py-1.5 rounded-xl bg-slate-200 hover:bg-rose-100 hover:text-rose-800 disabled:opacity-50 text-slate-800 font-bold text-xs transition">
          ✓ Terpilih (Hapus)
        </button>
      `;
    } else if (isFull) {
      actionBtn = `
        <button onclick="openAdvokasiForCourse('${c.id}')" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-sm transition">
          ${alreadyReported ? '📋 Lihat Status Laporan' : '⚠️ Lapor Kuota ke Himatif'}
        </button>
      `;
    } else {
      actionBtn = `
        <button ${lockedKrs ? 'disabled' : ''} onclick="addCourseToKrs('${c.id}')" class="px-3.5 py-1.5 rounded-xl bg-blue-900 hover:bg-blue-800 disabled:opacity-40 text-white font-bold text-xs shadow-sm transition">
          + Ambil Kelas
        </button>
      `;
    }

    return `
      <tr class="hover:bg-slate-50/90 transition ${isFull && !isSelected ? 'bg-rose-50/30' : ''}">
        <td class="py-3.5 px-4">
          <div class="flex items-center gap-2">
            <span class="font-mono font-bold text-blue-900 text-[11px] bg-blue-50 px-2 py-0.5 rounded border border-blue-200">${c.code}</span>
            ${c.mandatory ? '<span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">Wajib</span>' : '<span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-purple-50 text-purple-700">Pilihan</span>'}
          </div>
          <div class="font-extrabold text-slate-900 text-xs mt-1">${c.name}</div>
          <div class="text-[11px] text-slate-500">Dosen: ${c.lecturer}</div>
        </td>
        <td class="py-3.5 px-3 text-center font-extrabold text-slate-800">${c.sks} SKS</td>
        <td class="py-3.5 px-3">
          <span class="font-bold text-slate-900 block">Kelas ${c.kelas}</span>
          <span class="text-[11px] text-slate-500">${c.schedule}</span>
        </td>
        <td class="py-3.5 px-3 text-center">${quotaBadge}</td>
        <td class="py-3.5 px-4 text-right">${actionBtn}</td>
      </tr>
    `;
  }).join('');
}

function renderStudentCart() {
  const rec = getStudentRecord();
  const cartEl = document.getElementById('studentCartList');
  const warnBox = document.getElementById('studentFullWarningBox');
  const submitBtn = document.getElementById('btnSubmitKrs');
  const submitLabel = document.getElementById('btnSubmitKrsLabel');

  if (rec.selectedCourses.length === 0) {
    cartEl.innerHTML = `
      <div class="p-6 text-center rounded-xl border border-dashed border-slate-300 text-slate-400 text-xs">
        Belum ada mata kuliah yang dipilih. Silakan klik <strong>+ Ambil Kelas</strong> pada daftar mata kuliah.
      </div>
    `;
  } else {
    cartEl.innerHTML = rec.selectedCourses.map(cid => {
      const c = appState.courses.find(x => x.id === cid);
      if (!c) return '';
      const locked = rec.krsStatus === 'submitted' || rec.krsStatus === 'approved';
      return `
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-2">
          <div>
            <div class="flex items-center gap-1.5">
              <span class="font-mono text-[10px] font-bold text-blue-900">${c.code}</span>
              <span class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-blue-100 text-blue-900">${c.kelas}</span>
              <span class="text-[10px] font-extrabold text-emerald-700">${c.sks} SKS</span>
            </div>
            <div class="text-xs font-bold text-slate-900 mt-0.5">${c.name}</div>
          </div>
          ${!locked ? `<button onclick="removeCourseFromKrs('${c.id}')" class="text-rose-600 hover:text-rose-800 text-xs font-bold px-2 py-1">Hapus</button>` : ''}
        </div>
      `;
    }).join('');
  }

  const fullUnselectedMandatory = appState.courses.filter(c => {
    const hasSameCodeInCart = rec.selectedCourses.some(cid => {
      const picked = appState.courses.find(x => x.id === cid);
      return picked && picked.code === c.code;
    });
    return c.mandatory && (c.enrolled >= c.quota) && !hasSameCodeInCart;
  });

  if (fullUnselectedMandatory.length > 0 && rec.krsStatus === 'draft') {
    warnBox.classList.remove('hidden');
    warnBox.innerHTML = `
      <div class="font-extrabold text-amber-950 flex items-center gap-1.5">
        <span>⚠️</span>
        <span>Terdapat ${fullUnselectedMandatory.length} Mata Kuliah Wajib yang Kuotanya Penuh!</span>
      </div>
      <p class="text-[11px] leading-relaxed">
        Mata kuliah <strong>${fullUnselectedMandatory.map(x => x.name).join(', ')}</strong> saat ini telah penuh. Silakan laporkan melalui menu <strong>Lapor Kendala Kuota (Advokasi Himatif)</strong>.
      </p>
    `;
  } else {
    warnBox.classList.add('hidden');
  }

  if (rec.krsStatus === 'submitted') {
    submitBtn.disabled = true;
    submitLabel.textContent = 'Menunggu Validasi Dosen Wali...';
  } else if (rec.krsStatus === 'approved') {
    submitBtn.disabled = true;
    submitLabel.textContent = 'KRS Telah Disetujui (ACC Dosen Wali)';
  } else {
    submitBtn.disabled = rec.selectedCourses.length === 0;
    submitLabel.textContent = rec.krsStatus === 'revision'
      ? 'Ajukan Ulang Hasil Revisi KRS ke Dosen Wali'
      : 'Ajukan KRS ke Dosen Wali';
  }
}

function addCourseToKrs(courseId) {
  const rec = getStudentRecord();
  if (rec.krsStatus === 'submitted' || rec.krsStatus === 'approved') return;

  const course = appState.courses.find(c => c.id === courseId);
  if (!course) return;

  if (course.enrolled >= course.quota) {
    showToast('Kelas Penuh', 'Kuota kelas ini sudah penuh. Silakan lapor ke Himatif.', '⚠️');
    return;
  }

  const duplicateCode = rec.selectedCourses.some(cid => {
    const picked = appState.courses.find(x => x.id === cid);
    return picked && picked.code === course.code;
  });
  if (duplicateCode) {
    showToast('Mata Kuliah Sudah Ada', `Mata kuliah ${course.name} sudah ada di dalam draf KRS Anda.`, 'ℹ️');
    return;
  }

  const currentSks = rec.selectedCourses.reduce((acc, cid) => {
    const c = appState.courses.find(x => x.id === cid);
    return acc + (c ? c.sks : 0);
  }, 0);

  if (currentSks + course.sks > 24) {
    showToast('Melebihi Batas SKS', 'Total beban SKS tidak boleh melebihi 24 SKS.', '⚠️');
    return;
  }

  rec.selectedCourses.push(courseId);
  course.enrolled += 1;
  saveAppState();
  renderStudentPortal();
  showToast('Mata Kuliah Ditambahkan', `${course.name} (${course.kelas}) berhasil masuk ke draf KRS.`);
}

function removeCourseFromKrs(courseId) {
  const rec = getStudentRecord();
  if (rec.krsStatus === 'submitted' || rec.krsStatus === 'approved') return;

  const idx = rec.selectedCourses.indexOf(courseId);
  if (idx === -1) return;

  const course = appState.courses.find(c => c.id === courseId);
  if (course && course.enrolled > 0) course.enrolled -= 1;

  rec.selectedCourses.splice(idx, 1);
  saveAppState();
  renderStudentPortal();
  showToast('Mata Kuliah Dihapus', `${course ? course.name : 'Mata kuliah'} dihapus dari draf KRS.`, '🗑️');
}

function submitStudentKrs() {
  const rec = getStudentRecord();
  if (rec.selectedCourses.length === 0) return;

  rec.krsStatus = 'submitted';
  rec.submittedAt = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

  const totalSks = rec.selectedCourses.reduce((acc, cid) => {
    const c = appState.courses.find(x => x.id === cid);
    return acc + (c ? c.sks : 0);
  }, 0);

  appState.advisorLogs.unshift({
    time: rec.submittedAt,
    studentName: currentUser.name,
    studentNim: currentUser.nim,
    action: `Mengajukan KRS (${totalSks} SKS) untuk divalidasi Dosen Wali`,
    type: 'info'
  });

  saveAppState();
  renderStudentPortal();
  switchStudentTab('cetak');
  showToast('KRS Berhasil Diajukan', 'Draf KRS Anda telah dikirim ke Dosen Wali (Rika Yunitarini ST., MT.).', '📤');
}

function openAdvokasiForCourse(courseId) {
  switchStudentTab('advokasi');
  const selectEl = document.getElementById('formRepCourseId');
  if (selectEl) selectEl.value = courseId;
}

function renderStudentAdvokasiForm() {
  document.getElementById('formRepStudentInfo').value = `${currentUser.name} (${currentUser.nim}) — Kelas ${currentUser.kelas}`;
  const selectEl = document.getElementById('formRepCourseId');
  const fullOrAll = appState.courses.filter(c => c.enrolled >= c.quota || c.mandatory);

  selectEl.innerHTML = fullOrAll.map(c => {
    const isFull = c.enrolled >= c.quota;
    return `<option value="${c.id}">${c.code} — ${c.name} (Kelas ${c.kelas} • ${c.enrolled}/${c.quota} Kursi ${isFull ? '- PENUH' : ''})</option>`;
  }).join('');

  const listEl = document.getElementById('studentReportHistoryList');
  const myReports = appState.quotaReports.filter(r => r.studentEmail === currentUser.email);

  if (myReports.length === 0) {
    listEl.innerHTML = `
      <div class="p-8 text-center rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
        Anda belum mengirimkan laporan kendala kuota kelas ke Himatif pada semester ini.
      </div>
    `;
    return;
  }

  listEl.innerHTML = myReports.map(r => {
    let badgeClass = 'bg-amber-100 text-amber-900 border-amber-300';
    if (r.status === 'Diusulkan ke Admin Prodi') badgeClass = 'bg-blue-100 text-blue-900 border-blue-300';
    if (r.status === 'Kelas/Kuota Baru Telah Dibuka') badgeClass = 'bg-emerald-100 text-emerald-900 border-emerald-300';

    return `
      <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/70 space-y-2">
        <div class="flex flex-wrap items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <span class="font-mono text-xs font-extrabold text-blue-900">${r.courseCode}</span>
            <span class="text-xs font-extrabold text-slate-900">${r.courseName}</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border ${badgeClass}">
            ${r.status}
          </span>
        </div>
        <div class="text-[11px] text-slate-600">
          Urgensi: <strong>${r.urgency}</strong> • Waktu Lapor: <strong>${r.createdAt}</strong>
        </div>
        ${r.notes ? `<p class="text-xs text-slate-700 bg-white p-2.5 rounded-xl border border-slate-200">"${r.notes}"</p>` : ''}
      </div>
    `;
  }).join('');
}

function handleSubmitQuotaReport(e) {
  e.preventDefault();
  const courseId = document.getElementById('formRepCourseId').value;
  const urgency = document.getElementById('formRepUrgency').value;
  const notes = document.getElementById('formRepNotes').value.trim();

  const course = appState.courses.find(c => c.id === courseId);
  if (!course) return;

  const existing = appState.quotaReports.find(
    r => r.studentEmail === currentUser.email && r.courseCode === course.code
  );
  if (existing) {
    showToast('Laporan Sudah Terdaftar', `Anda sudah melaporkan mata kuliah ${course.name} ke Himatif.`, 'ℹ️');
    return;
  }

  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

  appState.quotaReports.unshift({
    id: 'REP-' + Date.now(),
    studentEmail: currentUser.email,
    studentName: currentUser.name,
    studentNim: currentUser.nim,
    courseId: course.id,
    courseCode: course.code,
    courseName: course.name,
    urgency,
    notes: notes || 'Kuota kelas penuh saat pengisian KRS berlangsung.',
    status: 'Menunggu Rekapitulasi Himatif',
    verifiedByHimatif: false,
    createdAt: nowStr
  });

  appState.himatifLogs.unshift({
    time: nowStr,
    title: `Laporan Baru Masuk (${course.code})`,
    detail: `${currentUser.name} (${currentUser.nim}) melaporkan kehabisan kuota kelas ${course.name}.`
  });

  document.getElementById('formRepNotes').value = '';
  saveAppState();
  renderStudentPortal();
  showToast('Laporan Terkirim ke Himatif', `Data keluhan kuota untuk ${course.name} telah masuk ke sistem Advokasi Himatif.`);
}

function renderStudentPrintKrs() {
  const rec = getStudentRecord();
  const banner = document.getElementById('validationStatusBanner');

  if (rec.krsStatus === 'draft') {
    banner.className = 'rounded-2xl p-5 border bg-slate-100 border-slate-300 text-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print';
    banner.innerHTML = `
      <div>
        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded bg-slate-300 text-slate-900">STATUS: DRAF KRS</span>
        <h4 class="text-base font-extrabold mt-1">KRS Anda Belum Diajukan ke Dosen Wali</h4>
        <p class="text-xs text-slate-600 mt-0.5">Silakan lengkapi pemilihan mata kuliah lalu klik tombol "Ajukan KRS ke Dosen Wali".</p>
      </div>
      <button onclick="switchStudentTab('krs')" class="px-4 py-2.5 rounded-xl bg-blue-900 text-white font-bold text-xs">Kembali ke Pengisian KRS</button>
    `;
  } else if (rec.krsStatus === 'submitted') {
    banner.className = 'rounded-2xl p-5 border bg-amber-50 border-amber-300 text-amber-950 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print';
    banner.innerHTML = `
      <div>
        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded bg-amber-200 text-amber-950">MENUNGGU VALIDASI AKADEMIK</span>
        <h4 class="text-base font-extrabold mt-1">KRS Sedang Ditinjau oleh Dosen Wali (Rika Yunitarini ST., MT.)</h4>
        <p class="text-xs text-amber-800 mt-0.5">Diajukan pada pukul ${rec.submittedAt || '-'}. Silakan tunggu pengesahan dari Dosen Pembimbing Akademik.</p>
      </div>
    `;
  } else if (rec.krsStatus === 'revision') {
    banner.className = 'rounded-2xl p-5 border bg-rose-50 border-rose-300 text-rose-950 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print';
    banner.innerHTML = `
      <div>
        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded bg-rose-200 text-rose-950">PERLU REVISI KRS</span>
        <h4 class="text-base font-extrabold mt-1">Dosen Wali Mengembalikan KRS Anda untuk Direvisi</h4>
        <p class="text-xs text-rose-800 mt-0.5">Catatan Dosen Wali: "${rec.advisorNote || 'Silakan sesuaikan mata kuliah wajib semester ini.'}"</p>
      </div>
      <button onclick="switchStudentTab('krs')" class="px-4 py-2.5 rounded-xl bg-rose-700 hover:bg-rose-600 text-white font-bold text-xs">Perbaiki KRS Sekarang →</button>
    `;
  } else if (rec.krsStatus === 'approved') {
    banner.className = 'rounded-2xl p-5 border bg-emerald-50 border-emerald-300 text-emerald-950 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print';
    banner.innerHTML = `
      <div>
        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded bg-emerald-200 text-emerald-950">KRS SAH & TERVALIDASI</span>
        <h4 class="text-base font-extrabold mt-1">Kartu Rencana Studi Telah Disetujui (ACC) oleh Dosen Wali</h4>
        <p class="text-xs text-emerald-800 mt-0.5">Disahkan pada pukul ${rec.validatedAt || '-'}. Anda dapat mencetak lembar KRS resmi di bawah ini.</p>
      </div>
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs shadow flex items-center gap-2">
        <span>🖨️</span>
        <span>Cetak Lembar KRS Resmi (PDF)</span>
      </button>
    `;
  }

  document.getElementById('printMhsName').textContent = currentUser.name;
  document.getElementById('printMhsNim').textContent = currentUser.nim;
  document.getElementById('printSignMhsName').textContent = currentUser.name;
  document.getElementById('printSignMhsNim').textContent = `NIM. ${currentUser.nim}`;

  const statusTextMap = {
    draft: 'BELUM DIAJUKAN (DRAF)',
    submitted: 'MENUNGGU VALIDASI DOSEN WALI',
    revision: 'DIKEMBALIKAN UNTUK REVISI',
    approved: 'DISETUJUI / SAH (ACC DOSEN WALI)'
  };
  document.getElementById('printMhsStatus').textContent = statusTextMap[rec.krsStatus] || '-';

  const tbody = document.getElementById('printKrsTableBody');
  let totalSks = 0;

  if (rec.selectedCourses.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="border border-slate-300 py-6 text-center text-slate-400">Belum ada mata kuliah yang dipilih</td></tr>`;
  } else {
    tbody.innerHTML = rec.selectedCourses.map((cid, idx) => {
      const c = appState.courses.find(x => x.id === cid);
      if (!c) return '';
      totalSks += c.sks;
      return `
        <tr>
          <td class="border border-slate-300 py-2 px-3 text-center">${idx + 1}</td>
          <td class="border border-slate-300 py-2 px-3 font-mono font-semibold">${c.code}</td>
          <td class="border border-slate-300 py-2 px-3 font-bold">${c.name}</td>
          <td class="border border-slate-300 py-2 px-3 text-center">${c.kelas}</td>
          <td class="border border-slate-300 py-2 px-3">${c.schedule}</td>
          <td class="border border-slate-300 py-2 px-3 text-center font-bold">${c.sks}</td>
        </tr>
      `;
    }).join('');
  }

  document.getElementById('printTotalSks').textContent = `${totalSks} SKS`;

  const stampEl = document.getElementById('printDigitalStamp');
  if (rec.krsStatus === 'approved') {
    stampEl.innerHTML = `
      <div class="inline-block px-3 py-1 rounded border-2 border-emerald-700 text-emerald-800 font-extrabold text-[10px] uppercase tracking-widest">
        ✓ TERVALIDASI DIGITAL SIAKAD UTM (${rec.validatedAt || '2026'})
      </div>
    `;
  } else {
    stampEl.innerHTML = `<span class="text-[11px] text-slate-400 italic">Menunggu pengesahan Dosen Wali</span>`;
  }
}

// ============================================================================
// 2. PORTAL DOSEN WALI (rika.yunitarini@trunojoyo.ac.id)
// ============================================================================
function switchAdvisorTab(tabName) {
  activeAdvisorTab = tabName;
  ['queue', 'review', 'monitoring'].forEach(t => {
    const panel = document.getElementById(`advisorTab_${t}`);
    const btn = document.getElementById(`dsnTabBtn_${t}`);
    if (t === tabName) {
      panel.classList.remove('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-blue-900 text-white shadow';
    } else {
      panel.classList.add('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200';
    }
  });
  renderAdvisorPortal();
}

function renderAdvisorPortal() {
  const mhsUsers = REGISTERED_USERS.filter(u => u.role === 'mahasiswa');
  let pendingCount = 0;
  let approvedCount = 0;
  let draftRevCount = 0;

  mhsUsers.forEach(u => {
    const st = appState.students[u.email]?.krsStatus || 'draft';
    if (st === 'submitted') pendingCount++;
    else if (st === 'approved') approvedCount++;
    else draftRevCount++;
  });

  document.getElementById('dsnStatTotalMhs').textContent = `${mhsUsers.length} Mahasiswa`;
  document.getElementById('dsnStatPending').textContent = `${pendingCount} Pengajuan`;
  document.getElementById('dsnStatApproved').textContent = `${approvedCount} Mahasiswa`;
  document.getElementById('dsnStatDraftRevision').textContent = `${draftRevCount} Mahasiswa`;
  document.getElementById('dsnBadgeQueue').textContent = pendingCount;

  renderAdvisorStudentTable();
  renderAdvisorReviewPanel();
  renderAdvisorMonitoringTab();
}

function renderAdvisorStudentTable() {
  const tbody = document.getElementById('advisorStudentTableBody');
  if (!tbody) return;

  const searchVal = (document.getElementById('dsnSearchInput')?.value || '').trim().toLowerCase();
  const statusFilter = document.getElementById('dsnFilterStatus')?.value || 'all';
  const mhsUsers = REGISTERED_USERS.filter(u => u.role === 'mahasiswa');

  const filtered = mhsUsers.filter(u => {
    const rec = appState.students[u.email] || { selectedCourses: [], krsStatus: 'draft' };
    const matchSearch = u.name.toLowerCase().includes(searchVal) || u.nim.toLowerCase().includes(searchVal);
    const matchStatus = statusFilter === 'all' ? true : rec.krsStatus === statusFilter;
    return matchSearch && matchStatus;
  });

  tbody.innerHTML = filtered.map(u => {
    const rec = appState.students[u.email] || { selectedCourses: [], krsStatus: 'draft' };
    const totalSks = rec.selectedCourses.reduce((acc, cid) => {
      const c = appState.courses.find(x => x.id === cid);
      return acc + (c ? c.sks : 0);
    }, 0);

    let badgeHtml = '';
    if (rec.krsStatus === 'submitted') {
      badgeHtml = `<span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">Menunggu Validasi</span>`;
    } else if (rec.krsStatus === 'approved') {
      badgeHtml = `<span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">Disetujui (ACC)</span>`;
    } else if (rec.krsStatus === 'revision') {
      badgeHtml = `<span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-900 border border-rose-300">Perlu Revisi</span>`;
    } else {
      badgeHtml = `<span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Mengajukan (Draf)</span>`;
    }

    return `
      <tr class="hover:bg-slate-50/90 transition">
        <td class="py-3.5 px-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-900 text-amber-300 font-extrabold text-xs flex items-center justify-center shrink-0">
              ${u.initials}
            </div>
            <div>
              <div class="font-extrabold text-slate-900 text-xs">${u.name}</div>
              <div class="text-[11px] text-slate-500 font-mono">NIM: ${u.nim} • Kelas ${u.kelas}</div>
            </div>
          </div>
        </td>
        <td class="py-3.5 px-3 text-center">
          <span class="font-bold text-slate-800">${u.ips}</span> / <span class="font-extrabold text-blue-900">${u.ipk}</span>
        </td>
        <td class="py-3.5 px-3 text-center">
          <span class="font-extrabold text-slate-900">${rec.selectedCourses.length} MK</span>
          <span class="block text-[11px] text-emerald-700 font-bold">${totalSks} / 24 SKS</span>
        </td>
        <td class="py-3.5 px-3 text-slate-600">${rec.submittedAt || '<span class="text-slate-400 italic">Belum diajukan</span>'}</td>
        <td class="py-3.5 px-3 text-center">${badgeHtml}</td>
        <td class="py-3.5 px-4 text-right">
          <div class="flex items-center justify-end gap-1.5">
            <button onclick="openStudentKrsReview('${u.email}')" class="px-3 py-1.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow-sm transition">
              🔍 Periksa KRS
            </button>
            ${rec.krsStatus === 'submitted' ? `
              <button onclick="quickApproveStudentKrs('${u.email}')" class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-sm transition">
                ✓ ACC Cepat
              </button>
            ` : ''}
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

function openStudentKrsReview(studentEmail) {
  selectedStudentEmailForAdvisor = studentEmail;
  switchAdvisorTab('review');
}

function renderAdvisorReviewPanel() {
  const quickListEl = document.getElementById('advisorQuickStudentList');
  const detailBox = document.getElementById('advisorReviewDetailBox');
  if (!quickListEl || !detailBox) return;

  const mhsUsers = REGISTERED_USERS.filter(u => u.role === 'mahasiswa');

  quickListEl.innerHTML = mhsUsers.map(u => {
    const rec = appState.students[u.email] || { selectedCourses: [], krsStatus: 'draft' };
    const isActive = u.email === selectedStudentEmailForAdvisor;

    let dotColor = 'bg-slate-400';
    let statusLabel = 'Draf';
    if (rec.krsStatus === 'submitted') { dotColor = 'bg-amber-500'; statusLabel = 'Menunggu Validasi'; }
    else if (rec.krsStatus === 'approved') { dotColor = 'bg-emerald-500'; statusLabel = 'Disetujui (ACC)'; }
    else if (rec.krsStatus === 'revision') { dotColor = 'bg-rose-500'; statusLabel = 'Perlu Revisi'; }

    return `
      <button
        onclick="openStudentKrsReview('${u.email}')"
        class="w-full text-left p-3 rounded-xl border transition flex items-center justify-between gap-2 ${
          isActive ? 'bg-blue-900 text-white border-blue-900 shadow' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200'
        }"
      >
        <div>
          <div class="text-xs font-extrabold">${u.name}</div>
          <div class="text-[11px] ${isActive ? 'text-blue-200' : 'text-slate-500'} font-mono">NIM: ${u.nim}</div>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
          <span class="w-2 h-2 rounded-full ${dotColor}"></span>
          <span class="text-[10px] font-bold">${statusLabel}</span>
        </div>
      </button>
    `;
  }).join('');

  const selectedMhs = mhsUsers.find(u => u.email === selectedStudentEmailForAdvisor) || mhsUsers[0];
  const rec = appState.students[selectedMhs.email] || { selectedCourses: [], krsStatus: 'draft', advisorNote: '' };

  const pickedCourses = rec.selectedCourses.map(cid => appState.courses.find(x => x.id === cid)).filter(Boolean);
  const totalSks = pickedCourses.reduce((acc, c) => acc + c.sks, 0);

  let statusBannerClass = 'bg-slate-100 text-slate-800 border-slate-300';
  let statusTitle = 'Belum Diajukan (Masih Berupa Draf)';
  if (rec.krsStatus === 'submitted') {
    statusBannerClass = 'bg-amber-50 text-amber-950 border-amber-300';
    statusTitle = `Menunggu Validasi Dosen Wali (Diajukan: ${rec.submittedAt || '-'})`;
  } else if (rec.krsStatus === 'approved') {
    statusBannerClass = 'bg-emerald-50 text-emerald-950 border-emerald-300';
    statusTitle = `Telah Disetujui / ACC oleh Dosen Wali (Disahkan: ${rec.validatedAt || '-'})`;
  } else if (rec.krsStatus === 'revision') {
    statusBannerClass = 'bg-rose-50 text-rose-950 border-rose-300';
    statusTitle = 'Dikembalikan kepada Mahasiswa untuk Revisi KRS';
  }

  detailBox.innerHTML = `
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-blue-900 text-amber-300 font-extrabold text-base flex items-center justify-center">
          ${selectedMhs.initials}
        </div>
        <div>
          <h3 class="text-lg font-extrabold text-slate-900">${selectedMhs.name}</h3>
          <p class="text-xs text-slate-500">
            NIM: <strong class="font-mono text-slate-800">${selectedMhs.nim}</strong> • Semester ${selectedMhs.semester} • IPS: <strong>${selectedMhs.ips}</strong> / IPK: <strong>${selectedMhs.ipk}</strong>
          </p>
        </div>
      </div>
      <div class="px-3.5 py-2 rounded-xl border text-xs font-extrabold ${statusBannerClass}">
        ${statusTitle}
      </div>
    </div>

    <div class="overflow-x-auto border border-slate-200 rounded-xl">
      <table class="w-full text-left border-collapse text-xs">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
            <th class="py-2.5 px-3 text-center">No</th>
            <th class="py-2.5 px-3">Kode & Mata Kuliah</th>
            <th class="py-2.5 px-3 text-center">Sifat</th>
            <th class="py-2.5 px-3 text-center">Kelas</th>
            <th class="py-2.5 px-3">Jadwal & Ruang</th>
            <th class="py-2.5 px-3 text-center">SKS</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          ${pickedCourses.map((c, idx) => `
            <tr>
              <td class="py-2.5 px-3 text-center font-semibold text-slate-500">${idx + 1}</td>
              <td class="py-2.5 px-3">
                <span class="font-mono font-bold text-blue-900">${c.code}</span> — <strong class="text-slate-900">${c.name}</strong>
              </td>
              <td class="py-2.5 px-3 text-center">${c.mandatory ? 'Wajib' : 'Pilihan'}</td>
              <td class="py-2.5 px-3 text-center font-bold text-slate-800">${c.kelas}</td>
              <td class="py-2.5 px-3 text-slate-600">${c.schedule}</td>
              <td class="py-2.5 px-3 text-center font-extrabold text-slate-900">${c.sks}</td>
            </tr>
          `).join('')}
        </tbody>
        <tfoot>
          <tr class="bg-slate-50 font-extrabold text-slate-900 border-t border-slate-200">
            <td colspan="5" class="py-2.5 px-3 text-right">Total Beban SKS yang Diajukan Mahasiswa</td>
            <td class="py-2.5 px-3 text-center text-blue-900">${totalSks} SKS</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
      <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
        Catatan Pembimbing Akademik (Dosen Wali)
      </label>
      <textarea
        id="advisorReviewNoteInput"
        rows="2"
        placeholder="Berikan catatan perwalian atau arahan revisi..."
        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-700"
      >${rec.advisorNote || ''}</textarea>

      <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
        <div class="flex flex-wrap gap-2">
          <button
            onclick="handleAdvisorDecision('${selectedMhs.email}', 'approved')"
            class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs shadow transition flex items-center gap-2"
          >
            <span>✅</span>
            <span>Setujui & Sahkan KRS Mahasiswa (ACC)</span>
          </button>
          <button
            onclick="handleAdvisorDecision('${selectedMhs.email}', 'revision')"
            class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow transition flex items-center gap-2"
          >
            <span>↩️</span>
            <span>Kembalikan untuk Revisi KRS</span>
          </button>
        </div>
      </div>
    </div>
  `;
}

function quickApproveStudentKrs(studentEmail) {
  selectedStudentEmailForAdvisor = studentEmail;
  handleAdvisorDecision(studentEmail, 'approved', 'KRS telah diperiksa dan disetujui (ACC) oleh Dosen Wali.');
}

function handleAdvisorDecision(studentEmail, decision, customNote) {
  const mhs = REGISTERED_USERS.find(u => u.email === studentEmail);
  const rec = appState.students[studentEmail];
  if (!mhs || !rec) return;

  const noteInput = document.getElementById('advisorReviewNoteInput');
  const noteValue = customNote !== undefined
    ? customNote
    : (noteInput ? noteInput.value.trim() : '');

  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

  if (decision === 'approved') {
    rec.krsStatus = 'approved';
    rec.validatedAt = nowStr;
    rec.advisorNote = noteValue || 'Rencana studi sesuai dengan kurikulum semester berjalan. Selamat menempuh perkuliahan.';
    appState.advisorLogs.unshift({
      time: nowStr,
      studentName: mhs.name,
      studentNim: mhs.nim,
      action: `Menyetujui / Mengesahkan KRS Mahasiswa (ACC)`,
      type: 'approved'
    });
    showToast('KRS Disetujui (ACC)', `KRS atas nama ${mhs.name} telah disahkan oleh Dosen Wali.`, '✅');
  } else if (decision === 'revision') {
    rec.krsStatus = 'revision';
    rec.validatedAt = null;
    rec.advisorNote = noteValue || 'Mohon periksa kembali kelengkapan mata kuliah wajib semester ini sebelum mengajukan ulang KRS.';
    appState.advisorLogs.unshift({
      time: nowStr,
      studentName: mhs.name,
      studentNim: mhs.nim,
      action: `Mengembalikan KRS untuk direvisi mahasiswa`,
      type: 'revision'
    });
    showToast('KRS Dikembalikan untuk Revisi', `Notifikasi revisi dikirim ke portal ${mhs.name}.`, '↩️');
  }

  saveAppState();
  renderAdvisorPortal();
}

function renderAdvisorMonitoringTab() {
  const courseBody = document.getElementById('advisorCourseMonitorBody');
  const logList = document.getElementById('advisorHistoryLogList');
  if (!courseBody || !logList) return;

  courseBody.innerHTML = appState.courses.map(c => {
    const isFull = c.enrolled >= c.quota;
    return `
      <tr class="hover:bg-slate-50">
        <td class="py-3 px-4">
          <span class="font-mono font-bold text-blue-900">${c.code}</span>
          <div class="font-bold text-slate-900">${c.name}</div>
        </td>
        <td class="py-3 px-3 text-center font-bold">${c.kelas}</td>
        <td class="py-3 px-3 text-slate-600">${c.schedule}</td>
        <td class="py-3 px-3 text-center font-extrabold">${c.enrolled} / ${c.quota}</td>
        <td class="py-3 px-4 text-right">
          ${isFull
            ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">PENUH</span>'
            : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Tersedia</span>'}
        </td>
      </tr>
    `;
  }).join('');

  logList.innerHTML = appState.advisorLogs.map(item => `
    <div class="p-3 rounded-xl border bg-slate-50 border-slate-200 text-xs">
      <div class="flex items-center justify-between">
        <span class="font-extrabold">${item.studentName} (${item.studentNim})</span>
        <span class="text-[10px] font-mono text-slate-500">${item.time}</span>
      </div>
      <p class="mt-1 text-[11px] text-slate-700">${item.action}</p>
    </div>
  `).join('');
}

// ============================================================================
// 3. PORTAL ADMIN PRODI (admin.informatika@trunojoyo.ac.id)
// ============================================================================
function switchAdminTab(tabName) {
  activeAdminTab = tabName;
  ['proposals', 'analysis', 'courses'].forEach(t => {
    const panel = document.getElementById(`adminTab_${t}`);
    const btn = document.getElementById(`admTabBtn_${t}`);
    if (t === tabName) {
      panel.classList.remove('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-blue-900 text-white shadow';
    } else {
      panel.classList.add('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200';
    }
  });
  renderAdminPortal();
}

function renderAdminPortal() {
  const fullClassesCount = appState.courses.filter(c => c.enrolled >= c.quota).length;
  const incomingProposals = appState.quotaReports.filter(r => r.status === 'Diusulkan ke Admin Prodi').length;
  const resolvedReports = appState.quotaReports.filter(r => r.status === 'Kelas/Kuota Baru Telah Dibuka').length;

  document.getElementById('admStatFullClasses').textContent = `${fullClassesCount} Kelas`;
  document.getElementById('admStatIncomingProposals').textContent = `${incomingProposals} Usulan`;
  document.getElementById('admStatResolved').textContent = `${resolvedReports} Selesai`;
  document.getElementById('admStatTotalClasses').textContent = `${appState.courses.length} Kelas`;
  document.getElementById('admBadgeProposals').textContent = incomingProposals;

  renderAdminProposalsTab();
  renderAdminAnalysisTab();
  renderAdminCoursesTab();
}

function renderAdminProposalsTab() {
  const container = document.getElementById('adminProposalGroupContainer');
  if (!container) return;

  const forwardedReports = appState.quotaReports.filter(
    r => r.status === 'Diusulkan ke Admin Prodi' || r.status === 'Kelas/Kuota Baru Telah Dibuka'
  );

  if (forwardedReports.length === 0) {
    container.innerHTML = `
      <div class="p-8 text-center rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
        Belum ada dokumen rekapitulasi usulan dari Himatif yang masuk ke Admin Prodi.
      </div>
    `;
    return;
  }

  const grouped = {};
  forwardedReports.forEach(rep => {
    if (!grouped[rep.courseCode]) {
      grouped[rep.courseCode] = {
        courseId: rep.courseId,
        courseCode: rep.courseCode,
        courseName: rep.courseName,
        reports: []
      };
    }
    grouped[rep.courseCode].reports.push(rep);
  });

  container.innerHTML = Object.values(grouped).map(group => {
    const courseObj = appState.courses.find(c => c.code === group.courseCode);
    const isResolved = group.reports.every(r => r.status === 'Kelas/Kuota Baru Telah Dibuka');

    return `
      <div class="rounded-2xl border ${isResolved ? 'border-emerald-200 bg-emerald-50/30' : 'border-amber-200 bg-amber-50/30'} p-5 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200/80 pb-3">
          <div>
            <div class="flex items-center gap-2">
              <span class="font-mono text-xs font-extrabold px-2.5 py-0.5 rounded bg-blue-900 text-white">${group.courseCode}</span>
              <h4 class="text-sm font-extrabold text-slate-900">${group.courseName}</h4>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold ${
                isResolved ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300'
              }">
                ${isResolved ? '✓ Telah Ditindaklanjuti Admin' : `${group.reports.length} Mahasiswa Terdampak`}
              </span>
            </div>
            <p class="text-xs text-slate-600 mt-1">
              Kapasitas Kelas Eksisting: <strong>${courseObj ? `${courseObj.enrolled}/${courseObj.quota} Kursi` : '-'}</strong> • Dosen Pengampu: <strong>${courseObj ? courseObj.lecturer : '-'}</strong>
            </p>
          </div>
          <div class="flex flex-wrap gap-2">
            <button onclick="openAdminAnalysisForCourse('${group.courseId}')" class="px-4 py-2 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow transition">
              🏫 Analisis Ruang & Buka Kelas/Kuota →
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs bg-white rounded-xl overflow-hidden border border-slate-200">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                <th class="py-2.5 px-3">Mahasiswa Terdampak</th>
                <th class="py-2.5 px-3">Urgensi Pengambilan</th>
                <th class="py-2.5 px-3">Keterangan Keluhan</th>
                <th class="py-2.5 px-3 text-right">Status Usulan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              ${group.reports.map(r => `
                <tr>
                  <td class="py-2.5 px-3 font-bold text-slate-900">${r.studentName} <span class="font-mono text-slate-500">(${r.studentNim})</span></td>
                  <td class="py-2.5 px-3 text-slate-700">${r.urgency}</td>
                  <td class="py-2.5 px-3 text-slate-600">"${r.notes}"</td>
                  <td class="py-2.5 px-3 text-right font-bold ${r.status === 'Kelas/Kuota Baru Telah Dibuka' ? 'text-emerald-700' : 'text-amber-800'}">${r.status}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }).join('');
}

function openAdminAnalysisForCourse(courseId) {
  switchAdminTab('analysis');
  const sel = document.getElementById('admExecCourseSelect');
  if (sel && courseId) {
    sel.value = courseId;
    handleAdminCourseSelectionChange();
  }
}

function renderAdminAnalysisTab() {
  const roomBody = document.getElementById('adminRoomTableBody');
  const lecBody = document.getElementById('adminLecturerTableBody');
  if (!roomBody || !lecBody) return;

  roomBody.innerHTML = ROOM_LIST.map(r => `
    <tr class="hover:bg-slate-50">
      <td class="py-3 px-4 font-bold text-slate-900">${r.name}</td>
      <td class="py-3 px-3 text-center font-extrabold text-blue-900">${r.capacity} Kursi</td>
      <td class="py-3 px-3 text-slate-600">${r.slot}</td>
      <td class="py-3 px-4 text-right">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">${r.status}</span>
      </td>
    </tr>
  `).join('');

  lecBody.innerHTML = LECTURER_LIST.map(l => `
    <tr class="hover:bg-slate-50">
      <td class="py-3 px-4 font-bold text-slate-900">${l.name}</td>
      <td class="py-3 px-3 text-slate-600">${l.field}</td>
      <td class="py-3 px-3 text-center font-bold">${l.load}</td>
      <td class="py-3 px-4 text-right">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">${l.status}</span>
      </td>
    </tr>
  `).join('');

  const courseSel = document.getElementById('admExecCourseSelect');
  const currentVal = courseSel.value;
  const baseCourses = appState.courses.filter(c => c.kelas === 'IF 7B');
  courseSel.innerHTML = baseCourses.map(c => {
    const isFull = c.enrolled >= c.quota;
    return `<option value="${c.id}">${c.code} — ${c.name} (${c.enrolled}/${c.quota} Kursi ${isFull ? '• PENUH' : ''})</option>`;
  }).join('');

  if (currentVal && baseCourses.some(c => c.id === currentVal)) {
    courseSel.value = currentVal;
  } else {
    const firstFull = baseCourses.find(c => c.enrolled >= c.quota);
    if (firstFull) courseSel.value = firstFull.id;
  }

  document.getElementById('admQuotaRoomSelect').innerHTML = ROOM_LIST.map(r =>
    `<option value="${r.name}">${r.name} (Kapasitas ${r.capacity} Kursi)</option>`
  ).join('');

  document.getElementById('admNewClassSchedule').innerHTML = ROOM_LIST.map(r =>
    `<option value="${r.slot} • ${r.name}">${r.slot} • ${r.name}</option>`
  ).join('');

  document.getElementById('admNewClassLecturer').innerHTML = LECTURER_LIST.map(l =>
    `<option value="${l.name}">${l.name}</option>`
  ).join('');

  handleAdminCourseSelectionChange();
}

function handleAdminCourseSelectionChange() {
  const courseId = document.getElementById('admExecCourseSelect')?.value;
  const course = appState.courses.find(c => c.id === courseId);
  const summaryBox = document.getElementById('admExecCourseSummary');
  if (!course || !summaryBox) return;

  const repCount = appState.quotaReports.filter(r => r.courseCode === course.code).length;
  summaryBox.innerHTML = `
    <div class="flex flex-wrap items-center justify-between gap-2">
      <span class="font-extrabold text-slate-900">${course.code} — ${course.name} (Kelas ${course.kelas})</span>
      <span class="px-2 py-0.5 rounded text-[11px] font-bold ${course.enrolled >= course.quota ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800'}">
        Terisi: ${course.enrolled} / ${course.quota} Kursi
      </span>
    </div>
    <div class="text-[11px] text-slate-600">
      Dosen Pengampu: <strong>${course.lecturer}</strong> • Jadwal Saat Ini: <strong>${course.schedule}</strong> • Usulan Masuk Himatif: <strong>${repCount} Mahasiswa</strong>
    </div>
  `;

  const lecSelect = document.getElementById('admNewClassLecturer');
  if (lecSelect) lecSelect.value = course.lecturer;
}

function toggleAdminActionFields() {
  const actionType = document.querySelector('input[name="admActionType"]:checked')?.value || 'add_quota';
  const quotaBox = document.getElementById('admFieldAddQuota');
  const parallelBox = document.getElementById('admFieldOpenParallel');

  if (actionType === 'add_quota') {
    quotaBox.classList.remove('hidden');
    parallelBox.classList.add('hidden');
  } else {
    quotaBox.classList.add('hidden');
    parallelBox.classList.remove('hidden');
  }
}

function handleAdminExecuteAction(e) {
  e.preventDefault();
  const courseId = document.getElementById('admExecCourseSelect').value;
  const course = appState.courses.find(c => c.id === courseId);
  if (!course) return;

  const actionType = document.querySelector('input[name="admActionType"]:checked')?.value || 'add_quota';
  const customNote = document.getElementById('admExecNote').value.trim();
  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

  let resultSummary = '';

  if (actionType === 'add_quota') {
    const addSeats = parseInt(document.getElementById('admAddQuotaAmount').value, 10) || 15;
    course.quota += addSeats;
    resultSummary = `Admin Prodi telah menambahkan kapasitas kuota kelas ${course.kelas} pada mata kuliah ${course.code} - ${course.name} sebanyak +${addSeats} kursi (Kapasitas baru: ${course.enrolled}/${course.quota} kursi).`;
  } else {
    const newClassName = document.getElementById('admNewClassName').value.trim() || 'IF 7C';
    const newQuota = parseInt(document.getElementById('admNewClassQuota').value, 10) || 35;
    const newSchedule = document.getElementById('admNewClassSchedule').value;
    const newLecturer = document.getElementById('admNewClassLecturer').value;

    const newId = 'MK-PAR-' + Date.now();
    appState.courses.push({
      id: newId,
      code: course.code,
      name: course.name,
      sks: course.sks,
      kelas: newClassName,
      schedule: newSchedule,
      lecturer: newLecturer,
      enrolled: 0,
      quota: newQuota,
      mandatory: course.mandatory
    });

    resultSummary = `Admin Prodi telah membuka Kelas Paralel Baru (${newClassName}) untuk mata kuliah ${course.code} - ${course.name} dengan kapasitas ${newQuota} kursi (${newSchedule}).`;
  }

  appState.quotaReports.forEach(r => {
    if (r.courseCode === course.code) {
      r.status = 'Kelas/Kuota Baru Telah Dibuka';
    }
  });

  const finalNotice = customNote ? `${resultSummary} Catatan Admin: "${customNote}"` : resultSummary;
  appState.adminReplyToHimatif = finalNotice;

  appState.adminLogs.unshift({
    time: nowStr,
    title: actionType === 'add_quota' ? `Penambahan Kuota ${course.code}` : `Pembukaan Kelas Baru ${course.code}`,
    detail: finalNotice,
    type: 'executed'
  });

  appState.himatifLogs.unshift({
    time: nowStr,
    title: `Konfirmasi Pembukaan Kelas dari Admin (${course.code})`,
    detail: finalNotice
  });

  document.getElementById('admExecNote').value = '';
  saveAppState();
  renderAdminPortal();
  showToast('Eksekusi Berhasil & Diinformasikan ke Himatif', resultSummary, '⚡');
}

function renderAdminCoursesTab() {
  const tbody = document.getElementById('adminAllCoursesTableBody');
  const logList = document.getElementById('adminActionLogList');
  if (!tbody || !logList) return;

  tbody.innerHTML = appState.courses.map(c => {
    const isFull = c.enrolled >= c.quota;
    return `
      <tr class="hover:bg-slate-50">
        <td class="py-3 px-4">
          <span class="font-mono font-bold text-blue-900">${c.code}</span>
          <div class="font-bold text-slate-900">${c.name}</div>
          <div class="text-[11px] text-slate-500">Dosen: ${c.lecturer}</div>
        </td>
        <td class="py-3 px-3 text-center font-extrabold text-slate-900">${c.kelas}</td>
        <td class="py-3 px-3 text-slate-600">${c.schedule}</td>
        <td class="py-3 px-3 text-center">
          <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold ${
            isFull ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'
          }">
            ${c.enrolled} / ${c.quota} Kursi
          </span>
        </td>
        <td class="py-3 px-4 text-right">
          <button onclick="adminQuickAddQuota('${c.id}')" class="px-3 py-1.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow-sm transition">
            +10 Kuota Cepat
          </button>
        </td>
      </tr>
    `;
  }).join('');

  logList.innerHTML = appState.adminLogs.map(item => `
    <div class="p-3.5 rounded-xl border bg-slate-50 border-slate-200 text-xs space-y-1">
      <div class="flex items-center justify-between">
        <span class="font-extrabold text-slate-900">${item.title}</span>
        <span class="text-[10px] font-mono text-slate-500">${item.time}</span>
      </div>
      <p class="text-[11px] text-slate-600 leading-relaxed">${item.detail}</p>
    </div>
  `).join('');
}

function adminQuickAddQuota(courseId) {
  const course = appState.courses.find(c => c.id === courseId);
  if (!course) return;
  course.quota += 10;

  appState.quotaReports.forEach(r => {
    if (r.courseCode === course.code) {
      r.status = 'Kelas/Kuota Baru Telah Dibuka';
    }
  });

  const msg = `Kapasitas kelas ${course.kelas} untuk mata kuliah ${course.code} - ${course.name} telah ditambah +10 kursi oleh Admin Prodi (Kapasitas baru: ${course.enrolled}/${course.quota}).`;
  appState.adminReplyToHimatif = msg;

  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
  appState.adminLogs.unshift({
    time: nowStr,
    title: `Tambah Kuota Cepat (${course.code})`,
    detail: msg,
    type: 'executed'
  });

  saveAppState();
  renderAdminPortal();
  showToast('Kuota Kelas Ditambahkan', msg);
}

// ============================================================================
// 4. PORTAL HIMATIF LENGKAP (advokasi.himatif@trunojoyo.ac.id)
// ============================================================================
function switchHimatifTab(tabName) {
  activeHimatifTab = tabName;
  ['incoming', 'recap', 'broadcast'].forEach(t => {
    const panel = document.getElementById(`himatifTab_${t}`);
    const btn = document.getElementById(`hmTabBtn_${t}`);
    if (t === tabName) {
      panel.classList.remove('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-blue-900 text-white shadow';
    } else {
      panel.classList.add('hidden');
      btn.className = 'px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200';
    }
  });
  renderHimatifPortal();
}

function renderHimatifPortal() {
  const totalReports = appState.quotaReports.length;
  const waitingRecap = appState.quotaReports.filter(r => r.status === 'Menunggu Rekapitulasi Himatif').length;
  const forwardedCount = appState.quotaReports.filter(r => r.status === 'Diusulkan ke Admin Prodi').length;
  const openedCount = appState.quotaReports.filter(r => r.status === 'Kelas/Kuota Baru Telah Dibuka').length;

  document.getElementById('hmStatTotal').textContent = `${totalReports} Laporan`;
  document.getElementById('hmStatWaiting').textContent = `${waitingRecap} Belum Direkap`;
  document.getElementById('hmStatForwarded').textContent = `${forwardedCount} Diusulkan`;
  document.getElementById('hmStatResolved').textContent = `${openedCount} Selesai`;
  document.getElementById('hmBadgeIncoming').textContent = waitingRecap;

  // Banner Balasan Admin Prodi
  const replyBanner = document.getElementById('himatifAdminReplyBanner');
  if (appState.adminReplyToHimatif) {
    replyBanner.classList.remove('hidden');
    replyBanner.innerHTML = `
      <div class="flex items-start gap-3">
        <span class="text-2xl">✅</span>
        <div>
          <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-200 text-emerald-950">
            KONFIRMASI RESMI DARI ADMIN PRODI
          </span>
          <p class="font-bold text-emerald-950 mt-1 text-xs">${appState.adminReplyToHimatif}</p>
        </div>
      </div>
      <button onclick="switchHimatifTab('broadcast')" class="shrink-0 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs shadow">
        Teruskan Informasi ke Mahasiswa →
      </button>
    `;
  } else {
    replyBanner.classList.add('hidden');
  }

  renderHimatifIncomingTab();
  renderHimatifRecapTab();
  renderHimatifBroadcastTab();
}

function renderHimatifIncomingTab() {
  const tbody = document.getElementById('himatifIncomingTableBody');
  if (!tbody) return;

  if (appState.quotaReports.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
          Belum ada data laporan keluhan kuota penuh dari mahasiswa.
        </td>
      </tr>
    `;
    return;
  }

  tbody.innerHTML = appState.quotaReports.map(r => {
    let badgeClass = 'bg-amber-100 text-amber-900 border-amber-300';
    if (r.status === 'Diusulkan ke Admin Prodi') badgeClass = 'bg-blue-100 text-blue-900 border-blue-300';
    if (r.status === 'Kelas/Kuota Baru Telah Dibuka') badgeClass = 'bg-emerald-100 text-emerald-900 border-emerald-300';

    return `
      <tr class="hover:bg-slate-50 transition">
        <td class="py-3.5 px-4">
          <div class="font-extrabold text-slate-900 text-xs">${r.studentName}</div>
          <div class="text-[11px] font-mono text-slate-500">NIM: ${r.studentNim} • Angkatan 2024</div>
        </td>
        <td class="py-3.5 px-3">
          <span class="font-mono font-bold text-blue-900 text-[11px]">${r.courseCode}</span>
          <div class="font-bold text-slate-900 text-xs">${r.courseName}</div>
        </td>
        <td class="py-3.5 px-3">
          <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 font-semibold text-[11px]">${r.urgency}</span>
          <div class="text-[11px] text-slate-500 mt-0.5">"${r.notes}"</div>
        </td>
        <td class="py-3.5 px-3 text-center">
          ${r.verifiedByHimatif
            ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Tervalidasi</span>'
            : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Belum Divalidasi</span>'}
        </td>
        <td class="py-3.5 px-3 text-center">
          <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold border ${badgeClass}">${r.status}</span>
        </td>
        <td class="py-3.5 px-4 text-right">
          ${!r.verifiedByHimatif ? `
            <button onclick="himatifVerifySingleReport('${r.id}')" class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-sm transition">
              ✓ Validasi Data
            </button>
          ` : `
            <span class="text-[11px] text-slate-400 font-semibold">Siap Direkap</span>
          `}
        </td>
      </tr>
    `;
  }).join('');
}

function himatifVerifySingleReport(repId) {
  const rep = appState.quotaReports.find(r => r.id === repId);
  if (!rep) return;
  rep.verifiedByHimatif = true;
  saveAppState();
  renderHimatifPortal();
  showToast('Data Mahasiswa Tervalidasi', `Laporan atas nama ${rep.studentName} telah diverifikasi oleh Himatif.`);
}

function renderHimatifRecapTab() {
  const container = document.getElementById('himatifRecapGroupBox');
  if (!container) return;

  if (appState.quotaReports.length === 0) {
    container.innerHTML = `<div class="p-6 text-center rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">Belum ada laporan kuota penuh untuk direkapitulasi.</div>`;
    return;
  }

  const grouped = {};
  appState.quotaReports.forEach(r => {
    if (!grouped[r.courseCode]) {
      grouped[r.courseCode] = {
        courseCode: r.courseCode,
        courseName: r.courseName,
        items: []
      };
    }
    grouped[r.courseCode].items.push(r);
  });

  container.innerHTML = Object.values(grouped).map(g => {
    const waitingCount = g.items.filter(x => x.status === 'Menunggu Rekapitulasi Himatif').length;
    return `
      <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div>
            <span class="font-mono text-xs font-extrabold px-2.5 py-0.5 rounded bg-blue-900 text-white">${g.courseCode}</span>
            <h4 class="text-sm font-extrabold text-slate-900 inline-block ml-2">${g.courseName}</h4>
            <p class="text-xs text-slate-500 mt-1">
              Total Mahasiswa Terdampak: <strong>${g.items.length} Mahasiswa</strong> • Menunggu Diserahkan ke Admin: <strong>${waitingCount} Data</strong>
            </p>
          </div>
          <button onclick="himatifForwardCourseToAdmin('${g.courseCode}')" class="px-4 py-2 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow transition">
            📤 Serahkan Dokumen Rekap ${g.courseCode} ke Admin Prodi
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function himatifForwardCourseToAdmin(courseCode) {
  let count = 0;
  appState.quotaReports.forEach(r => {
    if (!courseCode || r.courseCode === courseCode) {
      r.verifiedByHimatif = true;
      if (r.status === 'Menunggu Rekapitulasi Himatif') {
        r.status = 'Diusulkan ke Admin Prodi';
        count++;
      }
    }
  });

  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
  const msg = `Himatif menyerahkan dokumen rekapitulasi usulan penambahan kelas/kuota mata kuliah ${courseCode || 'Wajib'} kepada Admin Prodi.`;

  appState.adminLogs.unshift({
    time: nowStr,
    title: `Dokumen Usulan Himatif Masuk (${courseCode || 'Kolektif'})`,
    detail: msg,
    type: 'proposal'
  });

  appState.himatifLogs.unshift({
    time: nowStr,
    title: `Rekapitulasi Diserahkan ke Admin Prodi`,
    detail: msg
  });

  saveAppState();
  renderHimatifPortal();
  showToast('Dokumen Usulan Diserahkan ke Admin', msg, '📤');
}

function himatifForwardToAdmin() {
  himatifForwardCourseToAdmin(null);
}

function renderHimatifBroadcastTab() {
  const inputEl = document.getElementById('himatifBroadcastTextInput');
  const logBox = document.getElementById('himatifLogListBox');
  if (inputEl && !inputEl.value) {
    inputEl.value = appState.adminReplyToHimatif ||
      'Diberitahukan kepada seluruh mahasiswa Kelas IF 7B yang sebelumnya terkendala kuota penuh, Admin Prodi telah membuka kapasitas/kelas tambahan atas usulan Himatif. Silakan segera melengkapi dan mengajukan kembali KRS Anda.';
  }

  if (logBox) {
    logBox.innerHTML = appState.himatifLogs.map(l => `
      <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-1">
        <div class="flex items-center justify-between">
          <span class="font-extrabold text-slate-900">${l.title}</span>
          <span class="text-[10px] font-mono text-slate-500">${l.time}</span>
        </div>
        <p class="text-[11px] text-slate-600">${l.detail}</p>
      </div>
    `).join('');
  }
}

function handleHimatifBroadcastSubmit(e) {
  e.preventDefault();
  const txt = document.getElementById('himatifBroadcastTextInput').value.trim();
  if (!txt) return;

  appState.broadcastMessage = txt;
  const nowStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

  appState.himatifLogs.unshift({
    time: nowStr,
    title: 'Pengumuman Pembukaan Kelas Disebarkan ke Mahasiswa',
    detail: txt
  });

  saveAppState();
  renderHimatifPortal();
  showToast('Informasi Disebarkan ke Mahasiswa', 'Seluruh mahasiswa kini menerima banner pengumuman pembukaan kelas baru di portal KRS mereka.', '📢');
}

// ============================================================================
// UTILITAS TOAST NOTIFICATION
// ============================================================================
let toastTimer = null;
function showToast(title, msg, icon = '✅') {
  const box = document.getElementById('toastBox');
  document.getElementById('toastIcon').textContent = icon;
  document.getElementById('toastTitle').textContent = title;
  document.getElementById('toastMsg').textContent = msg;
  box.classList.remove('hidden');
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => box.classList.add('hidden'), 4200);
}
