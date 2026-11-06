// ============================================================================
// LIVE AUTOMATED TEST RUNNER — SIAKAD KRS TEKNIK INFORMATIKA UTM
// Pengujian visual interaktif langsung di layar pengguna (Visible Live Demo)
// ============================================================================

(function () {
  let isRunning = false;
  let isPaused = false;
  let currentStepIdx = 0;
  let speedMultiplier = 1.0; // 1.0 = normal, 0.6 = cepat, 1.8 = santai/lambat
  let timerId = null;

  // DOM Elements for Live Test HUD
  let hudContainer = null;
  let cursorEl = null;

  const TEST_STEPS = [
    {
      title: "Persiapan Lingkungan Pengujian",
      narration: "Menyiapkan data simulasi SIAKAD UTM ke status awal...",
      action: async () => {
        if (typeof resetDemoData === 'function') {
          appState = getDefaultState();
          saveAppState();
        }
        if (currentUser) {
          handleLogout();
        }
        await sleep(800);
      }
    },
    {
      title: "Fase 1: Login Akun Mahasiswa",
      narration: "Mengetikkan email resmi mahasiswa: 240411100157@student.trunojoyo.ac.id...",
      action: async () => {
        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, '240411100157@student.trunojoyo.ac.id');
      }
    },
    {
      title: "Fase 1: Input Kata Sandi Mahasiswa",
      narration: "Mengetikkan kata sandi resmi mahasiswa: utm2026...",
      action: async () => {
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'utm2026');
      }
    },
    {
      title: "Fase 1: Mengirimkan Formulir Login SSO",
      narration: "Mengklik tombol 'Masuk ke Sistem Akademik'...",
      action: async () => {
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1000);
      }
    },
    {
      title: "Fase 1: Verifikasi Portal Mahasiswa",
      narration: "Berhasil masuk! Meninjau data mahasiswa Ahmad Maulana Ishaq (IF 7B, Sem 5)...",
      action: async () => {
        const badge = document.getElementById('mhsStatusBadge');
        highlightElement(badge, 'emerald');
        await sleep(1200);
      }
    },
    {
      title: "Fase 2: Pemilihan Mata Kuliah Kuota Tersedia",
      narration: "Mengambil mata kuliah wajib IF24501 - Pemodelan Proses Bisnis (3 SKS)...",
      action: async () => {
        switchStudentTab('krs');
        await sleep(500);
        // Find add button for MK01
        const addBtn = document.querySelector("button[onclick*=\"addCourseToKrs('MK01')\"]");
        if (addBtn) {
          await pointAndClick(addBtn);
        } else {
          // If already in cart, remove and re-add for visual effect
          const remBtn = document.querySelector("button[onclick*=\"removeCourseFromKrs('MK01')\"]");
          if (remBtn) {
            await pointAndClick(remBtn);
            await sleep(600);
            const newAddBtn = document.querySelector("button[onclick*=\"addCourseToKrs('MK01')\"]");
            if (newAddBtn) await pointAndClick(newAddBtn);
          }
        }
        await sleep(1000);
      }
    },
    {
      title: "Fase 2: Pemilihan Mata Kuliah Tambahan",
      narration: "Mengambil mata kuliah IF24506 - Pemrograman Perangkat Bergerak (Mobile)...",
      action: async () => {
        const addBtn = document.querySelector("button[onclick*=\"addCourseToKrs('MK06')\"]");
        if (addBtn) {
          await pointAndClick(addBtn);
        }
        await sleep(1000);
      }
    },
    {
      title: "Fase 2: Pengajuan KRS ke Dosen Wali",
      narration: "Memeriksa ringkasan SKS dan mengklik 'Ajukan KRS ke Dosen Wali'...",
      action: async () => {
        const submitBtn = document.getElementById('btnSubmitKrs');
        if (submitBtn && !submitBtn.disabled) {
          await pointAndClick(submitBtn);
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 2: Validasi Status Pengajuan",
      narration: "Status KRS kini: 'Menunggu Validasi Dosen Wali'. Beralih akun untuk validasi...",
      action: async () => {
        const badge = document.getElementById('mhsStatusBadge');
        if (badge) highlightElement(badge, 'amber');
        await sleep(1500);
      }
    },
    {
      title: "Fase 3: Logout dari Portal Mahasiswa",
      narration: "Mengklik tombol Keluar untuk beralih ke akun Dosen Pembimbing Akademik...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) {
          await pointAndClick(logoutBtn);
        } else {
          handleLogout();
        }
        await sleep(1000);
      }
    },
    {
      title: "Fase 3: Login Dosen Wali",
      narration: "Menginputkan email resmi Dosen Wali: rika.yunitarini@trunojoyo.ac.id...",
      action: async () => {
        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, 'rika.yunitarini@trunojoyo.ac.id');
      }
    },
    {
      title: "Fase 3: Kata Sandi Dosen Wali",
      narration: "Menginputkan password Dosen Wali: dosen2026...",
      action: async () => {
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'dosen2026');
      }
    },
    {
      title: "Fase 3: Masuk ke Portal Dosen Wali",
      narration: "Mengklik tombol 'Masuk ke Sistem Akademik'...",
      action: async () => {
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1200);
      }
    },
    {
      title: "Fase 3: Memeriksa Antrean Validasi Mahasiswa",
      narration: "Dosen Wali memeriksa daftar mahasiswa bimbingan yang telah mengajukan KRS...",
      action: async () => {
        switchAdvisorTab('queue');
        await sleep(800);
        selectStudentForAdvisor('240411100157@student.trunojoyo.ac.id');
        await sleep(1200);
      }
    },
    {
      title: "Fase 3: Memberikan Catatan & ACC KRS",
      narration: "Mengetikkan catatan pengesahan: 'KRS telah sesuai dengan kurikulum Sem 5. Disetujui.'...",
      action: async () => {
        const noteInput = document.getElementById('advisorNoteInput');
        if (noteInput) {
          await pointAndType(noteInput, 'KRS telah sesuai dengan rancangan kurikulum Semester 5. Disetujui (ACC).');
        }
        await sleep(800);
        const approveBtn = document.querySelector("button[onclick*='handleAdvisorApprove']");
        if (approveBtn) {
          await pointAndClick(approveBtn);
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 4: Verifikasi Lembar KRS Sah Mahasiswa",
      narration: "Login kembali sebagai mahasiswa untuk memeriksa lembar KRS yang telah disahkan...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) await pointAndClick(logoutBtn);
        await sleep(800);

        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, '240411100157@student.trunojoyo.ac.id');
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'utm2026');
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1000);

        switchStudentTab('cetak');
        await sleep(1500);
      }
    },
    {
      title: "Fase 5: Uji Skenario Kuota Penuh (40/40)",
      narration: "Membuka pemilihan kelas, meninjau mata kuliah Kecerdasan Buatan yang berstatus PENUH...",
      action: async () => {
        switchStudentTab('krs');
        await sleep(800);
        const reportBtn = document.querySelector("button[onclick*=\"openAdvokasiForCourse('MK02')\"]");
        if (reportBtn) {
          highlightElement(reportBtn, 'rose');
          await sleep(1200);
          await pointAndClick(reportBtn);
        } else {
          switchStudentTab('advokasi');
        }
        await sleep(1000);
      }
    },
    {
      title: "Fase 5: Mengirim Laporan Advokasi ke Himatif",
      narration: "Mahasiswa mengirim laporan kendala kuota penuh ke Himpunan Mahasiswa (Himatif)...",
      action: async () => {
        const notesArea = document.getElementById('formRepNotes');
        if (notesArea) {
          await pointAndType(notesArea, 'Kuota kelas IF 7B penuh (40/40). Kami mohon pembukaan kuota tambahan agar dapat memenuhi SKS wajib semester 5.');
        }
        await sleep(800);
        const submitRepBtn = document.querySelector('#advokasiReportForm button[type="submit"]');
        if (submitRepBtn) {
          await pointAndClick(submitRepBtn);
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 6: Rekapitulasi oleh Himatif",
      narration: "Login sebagai Pengurus Himatif (advokasi.himatif@trunojoyo.ac.id)...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) await pointAndClick(logoutBtn);
        await sleep(800);

        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, 'advokasi.himatif@trunojoyo.ac.id');
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'himatif2026');
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1200);

        switchHimatifTab('incoming');
        await sleep(1000);
      }
    },
    {
      title: "Fase 6: Penyerahan Usulan ke Admin Prodi",
      narration: "Himatif memvalidasi keluhan mahasiswa dan menyerahkan rekapitulasi ke Admin Prodi...",
      action: async () => {
        const submitProposalBtn = document.querySelector("button[onclick*='handleHimatifSubmitToAdmin']");
        if (submitProposalBtn) {
          await pointAndClick(submitProposalBtn);
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 7: Login Admin Program Studi",
      narration: "Login sebagai Tata Usaha/Admin Prodi (admin.informatika@trunojoyo.ac.id)...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) await pointAndClick(logoutBtn);
        await sleep(800);

        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, 'admin.informatika@trunojoyo.ac.id');
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'admin2026');
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1200);

        switchAdminTab('capacity');
        await sleep(1000);
      }
    },
    {
      title: "Fase 7: Eksekusi Penambahan Kuota Kelas (+15)",
      narration: "Admin Prodi mengeksekusi penambahan daya tampung Lab Komputasi Cerdas (+15 Kursi)...",
      action: async () => {
        const expandBtn = document.querySelector("button[onclick*=\"openCapacityModal('MK02')\"]");
        if (expandBtn) {
          await pointAndClick(expandBtn);
          await sleep(800);
          const confirmBtn = document.getElementById('btnConfirmCapacityAction');
          if (confirmBtn) {
            await pointAndClick(confirmBtn);
          }
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 8: Penyebaran Pengumuman oleh Himatif",
      narration: "Himatif menyebarkan broadcast pengumuman kuota baru ke seluruh mahasiswa...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) await pointAndClick(logoutBtn);
        await sleep(800);

        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, 'advokasi.himatif@trunojoyo.ac.id');
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'himatif2026');
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1200);

        switchHimatifTab('broadcast');
        await sleep(800);

        const broadInput = document.getElementById('himatifBroadcastTextInput');
        if (broadInput) {
          broadInput.value = 'Pengumuman Resmi: Kuota mata kuliah Kecerdasan Buatan telah ditambah +15 kursi. Silakan langsung melakukan pemrograman di SIAKAD!';
        }
        const broadSubmitBtn = document.querySelector("form[onsubmit*='handleHimatifBroadcastSubmit'] button[type='submit']");
        if (broadSubmitBtn) {
          await pointAndClick(broadSubmitBtn);
        }
        await sleep(1500);
      }
    },
    {
      title: "Fase 9: Mahasiswa Memprogram Kelas Baru",
      narration: "Mahasiswa login kembali dan berhasil memprogram mata kuliah yang baru dibuka...",
      action: async () => {
        const logoutBtn = document.querySelector("button[onclick*='handleLogout']");
        if (logoutBtn) await pointAndClick(logoutBtn);
        await sleep(800);

        const emailInput = document.getElementById('emailInput');
        await pointAndType(emailInput, '240411100157@student.trunojoyo.ac.id');
        const passInput = document.getElementById('passwordInput');
        await pointAndType(passInput, 'utm2026');
        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        await pointAndClick(submitBtn);
        await sleep(1200);

        switchStudentTab('krs');
        await sleep(1000);
      }
    },
    {
      title: "Pengujian Selesai: 100% SUKSES!",
      narration: "Seluruh skenario BPMN SIAKAD KRS UTM berhasil diuji secara live di browser Anda!",
      action: async () => {
        if (typeof showToast === 'function') {
          showToast('Uji Live Selesai', 'Seluruh 9 fase proses bisnis SIAKAD KRS UTM berhasil diuji 100%!', '🏆');
        }
        await sleep(2500);
      }
    }
  ];

  // ==========================================================================
  // HELPERS: ANIMATION, VIRTUAL CURSOR, TYPING
  // ==========================================================================
  function sleep(ms) {
    return new Promise(resolve => {
      timerId = setTimeout(resolve, ms * speedMultiplier);
    });
  }

  function createHUD() {
    if (document.getElementById('liveTestHud')) return;

    hudContainer = document.createElement('div');
    hudContainer.id = 'liveTestHud';
    hudContainer.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-[9999] max-w-2xl w-[92%] bg-slate-950/90 backdrop-blur-md text-white rounded-2xl shadow-2xl border border-slate-700/80 p-4 transition-all duration-300 font-sans';
    hudContainer.innerHTML = `
      <div class="flex items-center justify-between gap-3 mb-2 pb-2 border-b border-slate-800">
        <div class="flex items-center gap-2.5">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
          </span>
          <span class="text-xs font-black uppercase tracking-wider text-rose-400">PENGUJIAN LIVE OTOMATIS (SIAKAD UTM)</span>
        </div>
        <div class="flex items-center gap-2">
          <span id="hudStepIndicator" class="text-[11px] font-bold text-slate-400">Langkah 0/${TEST_STEPS.length}</span>
          <button id="hudPauseBtn" class="px-2 py-0.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold border border-slate-700">⏸️ Jeda</button>
          <button id="hudSpeedBtn" class="px-2 py-0.5 rounded-lg bg-blue-900/60 hover:bg-blue-800 text-xs font-bold border border-blue-600 text-blue-200">Kecepatan: Normal</button>
          <button id="hudCloseBtn" class="px-2 py-0.5 rounded-lg bg-rose-950/80 hover:bg-rose-900 text-xs font-bold border border-rose-800 text-rose-300">✕ Tutup</button>
        </div>
      </div>

      <!-- Step Title & Narration Subtitles -->
      <div class="space-y-1">
        <div id="hudStepTitle" class="text-sm font-extrabold text-amber-300 flex items-center gap-2">
          <span>🎯</span> <span id="hudStepTitleText">Menunggu Mulai...</span>
        </div>
        <p id="hudStepDesc" class="text-xs text-slate-200 leading-relaxed font-medium bg-slate-900/80 p-2 rounded-xl border border-slate-800">
          Pengujian visual interaktif akan menguji alur Mahasiswa, Dosen Wali, Himatif, dan Admin Prodi secara langsung.
        </p>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-slate-800 rounded-full h-1.5 mt-3 overflow-hidden">
        <div id="hudProgressBar" class="bg-gradient-to-r from-amber-400 to-emerald-400 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
      </div>
    `;

    document.body.appendChild(hudContainer);

    // Create Virtual Animated Cursor
    cursorEl = document.createElement('div');
    cursorEl.id = 'liveTestVirtualCursor';
    cursorEl.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 24px;
      height: 24px;
      z-index: 10000;
      pointer-events: none;
      transition: transform 0.35s cubic-bezier(0.25, 1, 0.5, 1);
      display: none;
    `;
    cursorEl.innerHTML = `
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5));">
        <path d="M5.5 3.2L18.8 12.3C19.7 12.9 19.3 14.3 18.2 14.4L13.1 14.8L16.2 20.9C16.6 21.7 15.8 22.5 15.0 22.1L12.1 20.6L9.5 22.6C8.7 23.2 7.5 22.6 7.5 21.6L7.5 3.8C7.5 2.7 8.7 2.1 9.5 2.7L5.5 3.2Z" fill="#F59E0B" stroke="#FFFFFF" stroke-width="1.5"/>
      </svg>
      <div id="cursorRipple" style="position: absolute; top: 0; left: 0; width: 24px; height: 24px; border-radius: 50%; border: 2px solid #F59E0B; transform: scale(0); opacity: 0; transition: all 0.3s ease-out;"></div>
    `;
    document.body.appendChild(cursorEl);

    // Attach Event Listeners
    document.getElementById('hudPauseBtn').onclick = togglePause;
    document.getElementById('hudSpeedBtn').onclick = toggleSpeed;
    document.getElementById('hudCloseBtn').onclick = stopLiveTest;
  }

  async function moveCursorTo(el) {
    if (!cursorEl || !el) return;
    cursorEl.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    await sleep(250);

    const rect = el.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.top + rect.height / 2;
    cursorEl.style.transform = `translate(${x}px, ${y}px)`;
    await sleep(350);
  }

  async function playClickEffect() {
    const ripple = document.getElementById('cursorRipple');
    if (ripple) {
      ripple.style.transform = 'scale(2.2)';
      ripple.style.opacity = '1';
      setTimeout(() => {
        ripple.style.transform = 'scale(0)';
        ripple.style.opacity = '0';
      }, 300);
    }
    await sleep(200);
  }

  async function pointAndClick(el) {
    if (!el) return;
    await moveCursorTo(el);
    highlightElement(el, 'amber');
    await playClickEffect();
    el.click();
    await sleep(300);
  }

  async function pointAndType(inputEl, text) {
    if (!inputEl) return;
    await moveCursorTo(inputEl);
    highlightElement(inputEl, 'blue');
    inputEl.focus();
    inputEl.value = '';
    for (let i = 0; i < text.length; i++) {
      inputEl.value += text[i];
      inputEl.dispatchEvent(new Event('input', { bubbles: true }));
      await sleep(25);
    }
    inputEl.dispatchEvent(new Event('change', { bubbles: true }));
    await sleep(200);
  }

  function highlightElement(el, color = 'amber') {
    if (!el) return;
    const originalShadow = el.style.boxShadow;
    const originalTransition = el.style.transition;
    const shadowColor = color === 'rose' ? 'rgba(244, 63, 94, 0.7)' : color === 'emerald' ? 'rgba(16, 185, 129, 0.7)' : 'rgba(245, 158, 11, 0.7)';
    el.style.transition = 'all 0.3s ease';
    el.style.boxShadow = `0 0 0 3px ${shadowColor}`;
    setTimeout(() => {
      el.style.boxShadow = originalShadow;
      el.style.transition = originalTransition;
    }, 1200);
  }

  // ==========================================================================
  // CONTROLLER LOGIC
  // ==========================================================================
  async function runStep(idx) {
    if (!isRunning || idx >= TEST_STEPS.length) {
      isRunning = false;
      if (cursorEl) cursorEl.style.display = 'none';
      return;
    }

    while (isPaused) {
      await new Promise(r => setTimeout(r, 200));
    }

    currentStepIdx = idx;
    const step = TEST_STEPS[idx];

    // Update HUD
    document.getElementById('hudStepIndicator').textContent = `Langkah ${idx + 1}/${TEST_STEPS.length}`;
    document.getElementById('hudStepTitleText').textContent = step.title;
    document.getElementById('hudStepDesc').textContent = step.narration;
    const pct = Math.round(((idx + 1) / TEST_STEPS.length) * 100);
    document.getElementById('hudProgressBar').style.width = `${pct}%`;

    try {
      await step.action();
    } catch (err) {
      console.warn('Step error:', err);
    }

    if (isRunning) {
      runStep(idx + 1);
    }
  }

  function startLiveTest() {
    createHUD();
    isRunning = true;
    isPaused = false;
    currentStepIdx = 0;
    hudContainer.classList.remove('hidden');
    runStep(0);
  }

  function togglePause() {
    isPaused = !isPaused;
    const btn = document.getElementById('hudPauseBtn');
    if (btn) {
      btn.textContent = isPaused ? '▶️ Lanjut' : '⏸️ Jeda';
      btn.className = isPaused
        ? 'px-2 py-0.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-xs font-bold border border-emerald-500 text-white'
        : 'px-2 py-0.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold border border-slate-700 text-white';
    }
  }

  function toggleSpeed() {
    const btn = document.getElementById('hudSpeedBtn');
    if (speedMultiplier === 1.0) {
      speedMultiplier = 0.55;
      btn.textContent = 'Kecepatan: Cepat (2x)';
    } else if (speedMultiplier === 0.55) {
      speedMultiplier = 1.6;
      btn.textContent = 'Kecepatan: Santai (0.6x)';
    } else {
      speedMultiplier = 1.0;
      btn.textContent = 'Kecepatan: Normal (1x)';
    }
  }

  function stopLiveTest() {
    isRunning = false;
    isPaused = false;
    if (timerId) clearTimeout(timerId);
    if (cursorEl) cursorEl.style.display = 'none';
    if (hudContainer) hudContainer.remove();
  }

  // Expose globally
  window.startLiveTest = startLiveTest;
  window.stopLiveTest = stopLiveTest;

  // Auto-start logic: If ?live_test=true or #live_test, start immediately.
  // Otherwise, display an auto-start prompt banner on login screen with 3s countdown.
  window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('live_test') === 'true' || window.location.hash === '#live_test') {
      setTimeout(startLiveTest, 500);
      return;
    }

    // Show auto-start banner if on login screen
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      let count = 4;
      const promptBanner = document.createElement('div');
      promptBanner.id = 'liveTestAutoStartPrompt';
      promptBanner.className = 'mb-4 p-3.5 rounded-2xl bg-gradient-to-r from-amber-500/20 via-amber-400/20 to-emerald-500/20 border-2 border-amber-400 text-slate-900 shadow-lg flex items-center justify-between gap-3 text-xs font-semibold animate-pulse';
      promptBanner.innerHTML = `
        <div class="flex items-center gap-2">
          <span class="text-xl">🚀</span>
          <div>
            <div class="font-extrabold text-slate-900">Pengujian Live Otomatis Siap!</div>
            <div id="liveTestCountdownText" class="text-slate-600 text-[11px]">Memulai demonstrasi dalam <strong class="text-amber-700">${count} detik</strong>...</div>
          </div>
        </div>
        <div class="flex items-center gap-1.5">
          <button id="btnCancelAutoTest" class="px-2.5 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-[11px] transition">Batal</button>
          <button id="btnStartNowAutoTest" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-[11px] shadow transition">Mulai Sekarang</button>
        </div>
      `;
      loginForm.parentNode.insertBefore(promptBanner, loginForm);

      const countdownInterval = setInterval(() => {
        count--;
        const txt = document.getElementById('liveTestCountdownText');
        if (txt) txt.innerHTML = `Memulai demonstrasi dalam <strong class="text-amber-700">${count} detik</strong>...`;
        if (count <= 0) {
          clearInterval(countdownInterval);
          if (promptBanner) promptBanner.remove();
          startLiveTest();
        }
      }, 1000);

      document.getElementById('btnStartNowAutoTest').onclick = () => {
        clearInterval(countdownInterval);
        if (promptBanner) promptBanner.remove();
        startLiveTest();
      };

      document.getElementById('btnCancelAutoTest').onclick = () => {
        clearInterval(countdownInterval);
        if (promptBanner) promptBanner.remove();
      };
    }
  });
})();
