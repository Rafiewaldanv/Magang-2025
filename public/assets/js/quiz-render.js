$(document).ready(function () {
  const form = document.getElementById("form");
  const testId = $('#form input[name="test_id"]').val();
  const packetId = $('#form input[name="packet_id"]').val();
  const jumlahSoal = parseInt($('#jumlah_soal').val());

  // Keys untuk persistence per packet
  const currentKey = `quizCurrent_${packetId}`;
  const totalStartKey = `quizTotalStart_${packetId}`;
  const totalDurationKey = `quizTotalDuration_${packetId}`;
  const perStartKey = `quizPerStart_${packetId}`;
  const perEndKey = `quizPerEnd_${packetId}`;
  const perCurrentKey = `quizPerCurrent_${packetId}`;
  const perDurationKey = `quizPerDuration_${packetId}`; // key untuk menyimpan durasi per-soal (ms)

  // -----------------
  // DEV: NO-TIMER PATCH (manual developer list)
  // -----------------
  (function detectNoTimer() {
    // developer: isi array ini dengan ID packet yang TIDAK punya batas waktu
    const developerNoTimerPackets = [8]; // <-- sesuaikan

    // safe parse packetId (trim whitespace)
    const pidRaw = ('' + packetId).trim();
    const pid = parseInt(pidRaw, 10);

    // set global flag
    window.__quizIsUnlimited = (!isNaN(pid) && developerNoTimerPackets.includes(pid));

    // debug (bisa dihapus setelah OK)
    console.log('detectNoTimer → packetId:', pidRaw, 'parsed:', pid, 'unlimited:', window.__quizIsUnlimited);

    if (window.__quizIsUnlimited) {
      // clear any stored timer data to avoid resuming previous timers
      try {
        localStorage.removeItem(totalStartKey);
        localStorage.removeItem(totalDurationKey);
        localStorage.removeItem(perStartKey);
        localStorage.removeItem(perEndKey);
        localStorage.removeItem(perCurrentKey);
        localStorage.removeItem(currentKey);
      } catch (e) { console.warn('clear storages error', e); }

      // set UI text immediately (if element exists)
      if ($('#timer').length) {
        $('#timer').text('Tidak ada batas waktu').addClass('no-timer');
      }
    }
  })();

  // --- Prevent browser back and show modal confirmation ---
  (function requireModalBeforeLeave() {
      let allowLeave = false;
      let modalShown = false;
      const INIT_PUSH = 60;
      const SAFETY_PUSH = 2;

      // pending navigation intent: { type: 'link'|'form'|'history', href, form }
      let pendingNavigation = null;

      function pushStates(n = 1) {
        try {
          for (let i = 0; i < n; i++) history.pushState({ inTest: true }, '', location.href);
        } catch (e) { console.warn('pushState failed', e); }
      }

      // init heavy buffer
      pushStates(INIT_PUSH);

      function showExitModal() {
        if (!modalShown) {
          modalShown = true;
          $('#modalKembali').modal('show');
        }
        pushStates(SAFETY_PUSH);
      }

      function onPopState(e) {
        if (allowLeave) return;
        pendingNavigation = { type: 'history' };
        showExitModal();
      }
      window.addEventListener('popstate', onPopState);

      function onDocumentClick(e) {
        if (allowLeave) return;
        const a = e.target.closest && e.target.closest('a');
        if (!a) return;

        const href = a.getAttribute('href');
        if (!href || href.startsWith('javascript:') || a.target === '_blank') return;

        const url = new URL(href, location.href);
        if (url.origin !== location.origin) return;

        if (a.hasAttribute('data-no-protect')) return;

        e.preventDefault();
        pendingNavigation = { type: 'link', href: url.href, anchor: a };
        showExitModal();
      }
      document.addEventListener('click', onDocumentClick, true);

      function onFormSubmit(e) {
        if (allowLeave) return;
        const f = e.target;
        if (!(f && f.tagName === 'FORM')) return;
        if (f.hasAttribute('data-no-protect')) return;

        e.preventDefault();
        pendingNavigation = { type: 'form', form: f };
        showExitModal();
      }
      document.addEventListener('submit', onFormSubmit, true);

      function onKeyDown(e) {
        if (allowLeave) return;
        const activeTag = document.activeElement && document.activeElement.tagName;
        if ((e.altKey && e.key === 'ArrowLeft') ||
            (e.key === 'Backspace' && !['INPUT','TEXTAREA','SELECT'].includes(activeTag))) {
          e.preventDefault();
          pendingNavigation = { type: 'history' };
          showExitModal();
        }
      }
      window.addEventListener('keydown', onKeyDown, { passive: false });

      $('#confirm-kembali').off('click').on('click', function (ev) {
        ev.preventDefault();
        allowLeave = true;

        window.removeEventListener('popstate', onPopState);
        document.removeEventListener('click', onDocumentClick, true);
        document.removeEventListener('submit', onFormSubmit, true);
        window.removeEventListener('keydown', onKeyDown);

        try {
          localStorage.removeItem(currentKey);
          localStorage.removeItem(totalStartKey);
          localStorage.removeItem(totalDurationKey);
          localStorage.removeItem(perStartKey);
          localStorage.removeItem(perEndKey);
          localStorage.removeItem(perCurrentKey);
        } catch (e) { console.warn('error clearing storage', e); }
        try { sessionStorage.removeItem('jawabanSementara'); } catch (e) {}

        $('#modalKembali').modal('hide');

        setTimeout(() => {
          if (!pendingNavigation) {
            const href = $(this).attr('href') || '/';
            window.location.href = href;
            return;
          }

          if (pendingNavigation.type === 'link') {
            window.location.href = pendingNavigation.href;
          } else if (pendingNavigation.type === 'form') {
            try { pendingNavigation.form.submit(); } catch (e) { window.location.href = '/'; }
          } else if (pendingNavigation.type === 'history') {
            history.back();
          } else {
            const href = $(this).attr('href') || '/';
            window.location.href = href;
          }
        }, 120);
      });

      $('#modalKembali').on('hidden.bs.modal', function () {
        modalShown = false;
        pendingNavigation = null;
        pushStates(3);
      });

    })();


  // -----------------------
  // Mode & per-question config
  // -----------------------
  // developer: set daftar packet yang pakai per-question mode (masukkan 5)
  const perQuestionPacketSet = new Set([7, 9, 3, 2, 1, 6, 4, 5, 10]); // <-- tambahkan/ubah sesuai kebutuhan

  // developer: default durasi per soal (detik) dan overrides per packet (detik)
  const perQuestionConfig = {
    defaultSeconds: 15,          // default durasi per-soal (detik)
    overrides: {
      5: 7,
      10: 20, // spesial: packet id 5 -> 10 detik
    }
  };

  // parse pid & determine mode
  const pid = parseInt(packetId, 10);
  const perQuestionMode = (!isNaN(pid) && perQuestionPacketSet.has(pid));

  // compute duration for this packet (ms) but allow stored override
  let perQuestionDuration = ((perQuestionConfig.overrides[pid] ?? perQuestionConfig.defaultSeconds) * 1000);
  const perQuestionIntervalDelay = 200; // tick frequency (ms)
  let perQuestionInterval = null;
  let perQuestionEnd = null;

  // state
  let current = 1;
  let jawabanSementara = JSON.parse(sessionStorage.getItem('jawabanSementara')) || {};
  Object.keys(jawabanSementara).forEach(k => { if (jawabanSementara[k] === "null") delete jawabanSementara[k]; });

  // --- TOTAL TIMER (for normal tests) ---
  function detectTotalDurationMs() {
    if (window.__quizIsUnlimited) return 0;

    const dataDur = $('#form').data('totalDuration') || $('#form').data('total-duration');
    if (dataDur) {
      const s = parseInt(dataDur);
      if (!isNaN(s) && s > 0) return s * 1000;
    }

    const hidden = $('input[name="total_time"], input[name="total_duration"], #total_time, #total-duration').first();
    if (hidden && hidden.val()) {
      const s = parseInt(hidden.val());
      if (!isNaN(s) && s > 0) return s * 1000;
    }

    const persisted = parseInt(localStorage.getItem(totalDurationKey));
    if (!isNaN(persisted) && persisted > 0) return persisted;

    return 15 * 60 * 1000;
  }

  const totalDurationMs = detectTotalDurationMs();
  if (!window.__quizIsUnlimited) localStorage.setItem(totalDurationKey, totalDurationMs.toString());

  let totalInterval = null;
  let totalEnd = null;

  function startTotalTimer() {
    if (window.__quizIsUnlimited) return;
    if (perQuestionMode) return;

    const savedStart = parseInt(localStorage.getItem(totalStartKey));
    const now = Date.now();

    if (!isNaN(savedStart) && savedStart > 0) {
      totalEnd = savedStart + totalDurationMs;
    } else {
      const start = now;
      totalEnd = start + totalDurationMs;
      localStorage.setItem(totalStartKey, start.toString());
    }

    if (totalInterval) clearInterval(totalInterval);

    totalTick();
    totalInterval = setInterval(totalTick, 1000);
  }

  function stopTotalTimer() {
    if (totalInterval) {
      clearInterval(totalInterval);
      totalInterval = null;
    }
  }

  function totalTick() {
    if (!totalEnd) return;
    const now = Date.now();
    const remaining = totalEnd - now;

    if (remaining <= 0) {
      $('#timer').text('00:00');
      localStorage.removeItem(totalStartKey);
      localStorage.removeItem(currentKey);

      stopTotalTimer();
      localStorage.removeItem(perStartKey);
      localStorage.removeItem(perEndKey);
      localStorage.removeItem(perCurrentKey);

      sessionStorage.removeItem('jawabanSementara');

      setTimeout(() => {
        if (typeof kirimJawaban === 'function') kirimJawaban();
        else form.submit();
      }, 250);
      return;
    }

    const minutes = Math.floor((remaining / 1000) / 60).toString().padStart(2, '0');
    const seconds = Math.floor((remaining / 1000) % 60).toString().padStart(2, '0');
    $('#timer').text(`${minutes}:${seconds}`);
  }

  // --- restore current question on load (both modes)
  const savedCurrent = parseInt(localStorage.getItem(currentKey));
  if (!isNaN(savedCurrent) && savedCurrent >= 1 && savedCurrent <= jumlahSoal) {
    current = savedCurrent;
  } else {
    current = 1;
  }

  // --- per-question resume handling (use stored per-duration if present) ---
  if (perQuestionMode) {
    const storedPerDur = parseInt(localStorage.getItem(perDurationKey));
    if (!isNaN(storedPerDur) && storedPerDur > 0) {
      perQuestionDuration = storedPerDur;
    } else {
      try { localStorage.setItem(perDurationKey, perQuestionDuration.toString()); } catch (e) {}
    }

    const savedPerCurrent = parseInt(localStorage.getItem(perCurrentKey));
    const savedPerStart = parseInt(localStorage.getItem(perStartKey));
    if (!isNaN(savedPerCurrent) && !isNaN(savedPerStart)) {
      const now = Date.now();
      const elapsed = now - savedPerStart;
      const passed = Math.floor(elapsed / perQuestionDuration);
      let resumed = savedPerCurrent + passed;
      if (resumed > jumlahSoal) {
        localStorage.removeItem(perCurrentKey);
        localStorage.removeItem(perStartKey);
        localStorage.removeItem(perEndKey);
        localStorage.removeItem(currentKey);
        setTimeout(() => {
          sessionStorage.removeItem('jawabanSementara');
          if (typeof kirimJawaban === 'function') kirimJawaban();
          else form.submit();
        }, 200);
        return;
      }
      current = resumed;
      const baseStart = savedPerStart + passed * perQuestionDuration;
      const newEnd = baseStart + perQuestionDuration;
      try {
        localStorage.setItem(perCurrentKey, current.toString());
        localStorage.setItem(perStartKey, baseStart.toString());
        localStorage.setItem(perEndKey, newEnd.toString());
      } catch (e) {}
      perQuestionEnd = newEnd;
    }
  }

  // --- initial fetch of the current soal ---
  getSoal(current);

  // --- click handlers ---
  $('#next').off('click').on('click', () => {
      if (current < jumlahSoal) {
          current++;
          localStorage.setItem(currentKey, current.toString());
          getSoal(current);
      }
  });

  if (!perQuestionMode) {
    $('#prev').off('click').on('click', () => {
        if (current > 1) {
            current--;
            localStorage.setItem(currentKey, current.toString());
            getSoal(current);
        }
    });
  } else {
    $('#prev').hide();
  }

  function getSoal(nomor) {
      $('#overlay-loading').show();

      // stop per-question timer first to avoid races
      stopPerQuestionTimer();

      $.get(`/api/soal/${testId}/${packetId}/${nomor}`, function (data) {
          $('#overlay-loading').hide();
          tampilkanSoal(data, nomor);
          updateNavigasi(nomor);
          updatePanelNavigasi();
          updateSoalTerjawab();

          // persist current for normal tests too so refresh stays on same soal
          localStorage.setItem(currentKey, nomor.toString());

          // Start timers based on mode (skip timers if unlimited)
          if (window.__quizIsUnlimited) {
            stopTotalTimer();
            stopPerQuestionTimer();
            $('#timer').text('Tidak ada batas waktu').addClass('no-timer');
          } else if (perQuestionMode) {
            startPerQuestionTimer();
            stopTotalTimer();
          } else {
            startTotalTimer();
            stopPerQuestionTimer();
          }
      }).fail(function () {
          $('#overlay-loading').hide();
          alert("Gagal memuat soal. Silakan refresh halaman.");
      });
  }

  function tampilkanSoal(data, nomor) {
      $('.soal_number .num').text(`Soal Nomor ${nomor}`);
      const answered = jawabanSementara[nomor] !== undefined && jawabanSementara[nomor] !== "";

      const resolveImageSrc = (img) => {
          if (!img) return null;
          img = ('' + img).trim();
          if (/^(https?:\/\/|\/|assets\/)/i.test(img)) return img.startsWith('/') ? img : (img.startsWith('http') ? img : '/' + img);
          const base = data.path ? `/assets/images/${data.path}/` : `/assets/images/`;
          return base + img;
      };

      const opsiHtml = (data.options || []).map(opt => {
        const stored = jawabanSementara[nomor];
        const isChecked = Array.isArray(stored)
            ? stored.includes(opt.value)
            : stored === opt.value;

        const checkedAttr = isChecked ? 'checked' : '';
        const imgSrc = resolveImageSrc(opt.image);

        let content = '';
        if (imgSrc) {
            content = `
                <img src="${imgSrc}" class="q-img" alt="option-${opt.value}">
                <div class="option-text ms-2">${opt.text ?? ''}</div>
            `;
        } else {
            content = `<div class="option-text">${opt.text ?? ''}</div>`;
        }

        return `
            <label class="list-group-item option-item d-flex align-items-center" role="option" tabindex="0">
                <input type="${data.multiSelect ? 'checkbox' : 'radio'}"
                    name="answer_${nomor}${data.multiSelect ? '[]' : ''}" 
                    value="${opt.value}" class="form-check-input me-2" ${checkedAttr}>
                <div class="flex-grow-1 d-flex align-items-center">
                    ${content}
                </div>
            </label>`;
    }).join('');

      let soalImageHtml = '';
      if (Array.isArray(data.questionImages) && data.questionImages.length) {
          soalImageHtml = `<div class="q-image-row">` +
              data.questionImages
                  .map(u => {
                      const src = resolveImageSrc(u);
                      return src ? `<img src="${src}" class="q-img" alt="soal-img">` : '';
                  })
                  .join('') +
              `</div>`;
      } else if (data.questionImage) {
          soalImageHtml = `<div class="q-image-row"><img src="${resolveImageSrc(data.questionImage)}" class="q-img" alt="soal-img"></div>`;
      }

      const batalHtml = answered ? `
          <div class="text-start mt-2">
              <button type="button" class="btn btn-danger btn-sm batal-jawab" data-nomor="${nomor}">
                  Batal Pilihan
              </button>
          </div>` : '';

      $('.s').html(`
          <p>${data.questionText ?? ''}</p>
          ${soalImageHtml}
          <div class="list-group options-grid" role="list">${opsiHtml}</div>
          ${batalHtml}
      `);

      // attach change handlers
      $(`input[name^="answer_${nomor}"]`).off('change').on('change', function () {
          if (data.multiSelect) {
              const selected = [];
              $(`input[name="answer_${nomor}[]"]:checked`).each(function () {
                  selected.push($(this).val());
              });
              jawabanSementara[nomor] = selected;
          } else {
              jawabanSementara[nomor] = $(this).val();
          }

          sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
          updateSoalTerjawab();
          updatePanelNavigasi();

          // only auto-next on normal tests (not per-question)
          if (!data.multiSelect && current < jumlahSoal && !perQuestionMode) {
              setTimeout(() => {
                  current++;
                  localStorage.setItem(currentKey, current.toString());
                  getSoal(current);
              }, 300);
          }
      });

      $('.batal-jawab').off('click').on('click', function () {
          const nomorSoal = $(this).data('nomor');
          delete jawabanSementara[nomorSoal];
          sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
          $(`input[name^="answer_${nomorSoal}"]`).prop('checked', false);
          $(this).remove();
          updateSoalTerjawab();
          updatePanelNavigasi();
      });
  }

  function updateSoalTerjawab() {
      const totalJawab = Object.keys(jawabanSementara).filter(k => {
          const val = jawabanSementara[k];
          return Array.isArray(val) ? val.length > 0 : val !== undefined && val !== '';
      }).length;

      $('#answered').text(totalJawab);
      $('#totals').text(jumlahSoal);

      $('#btn-submit').show().prop('disabled', false);
      if (!$('#btn-submit').data('bound')) {
          $('#btn-submit').data('bound', true).on('click', function () {
              const currentJawab = Object.keys(jawabanSementara).filter(k => {
                  const val = jawabanSementara[k];
                  return Array.isArray(val) ? val.length > 0 : val !== undefined && val !== '';
              }).length;

              const modalBody = currentJawab < jumlahSoal
                  ? 'Masih ada soal yang belum dijawab. Yakin ingin mengumpulkan sekarang?'
                  : 'Apakah Anda yakin ingin mengumpulkan semua jawaban?';
              $('#konfirmasiModal .modal-body').text(modalBody);
              $('#konfirmasiModal').modal('show');
          });
      }

      if (!$('#confirm-submit').data('bound')) {
          $('#confirm-submit').data('bound', true).on('click', function () {
              $('#konfirmasiModal').modal('hide');
              kirimJawaban();
          });
      }
  }

  function updateNavigasi(nomor) {
      if (perQuestionMode) {
        $('#prev').hide();
      } else {
        $('#prev').toggle(nomor > 1);
      }
      $('#next').toggle(nomor < jumlahSoal);
  }

  function updatePanelNavigasi() {
      let html = '';
      for (let i = 1; i <= jumlahSoal; i++) {
          const isCurrent = i === current;
          const hasAnswer = Array.isArray(jawabanSementara[i]) 
              ? jawabanSementara[i].length > 0 
              : !!jawabanSementara[i];

          let statusClass = hasAnswer ? 'answered' : 'unanswered';
          if (isCurrent) statusClass = 'active';

          html += `<button type="button" class="nav-soal ${statusClass}" data-index="${i}">${i}</button>`;
      }
      $('#soal-container').html(html);

      if (!perQuestionMode) {
        $('#soal-container .nav-soal').off('click').on('click', function () {
            current = $(this).data('index');
            localStorage.setItem(currentKey, current.toString());
            getSoal(current);
        });
      } else {
        $('#soal-container .nav-soal').css('cursor', 'not-allowed').off('click');
      }

      if ($('#mobile-dropside-btn').length === 0) {
        const btnHtml = `<button id="mobile-dropside-btn" class="dropside-btn" aria-label="Navigasi Soal" title="Navigasi Soal">
                            <span class="icon">&#9776;</span>
                         </button>`;
        const panelHtml = `<div id="mobile-dropside-panel" class="dropside-panel" role="dialog" aria-label="Navigasi Soal Panel" aria-hidden="true">
                             <div class="nav-grid" id="mobile-nav-grid"></div>
                           </div>`;

        $('#mobile-nav-placeholder').append(btnHtml);
        $('#mobile-nav-placeholder').append(panelHtml);

        $('#mobile-dropside-btn').on('click', function (e) {
          e.preventDefault();
          const panel = $('#mobile-dropside-panel');
          if (panel.is(':visible')) {
            panel.hide().attr('aria-hidden','true');
          } else {
            panel.show().attr('aria-hidden','false');
            setTimeout(() => $('#mobile-dropside-panel .nav-soal').first().focus(), 60);
          }
        });

        $(document).on('click', function (e) {
          const panel = $('#mobile-dropside-panel');
          if (!panel.length) return;
          if ($(e.target).closest('#mobile-dropside-panel, #mobile-dropside-btn').length === 0) {
            panel.hide().attr('aria-hidden','true');
          }
        });

        $(document).on('keydown', function (e) {
          if (e.key === 'Escape') {
            $('#mobile-dropside-panel').hide().attr('aria-hidden','true');
          }
        });
      }

      let mobileHtml = '';
      for (let i = 1; i <= jumlahSoal; i++) {
        const isCurrent = i === current;
        const hasAnswer = Array.isArray(jawabanSementara[i]) 
            ? jawabanSementara[i].length > 0 
            : !!jawabanSementara[i];

        let statusClass = hasAnswer ? 'answered' : 'unanswered';
        if (isCurrent) statusClass = 'active';

        mobileHtml += `<button type="button" class="nav-soal ${statusClass}" data-index="${i}" aria-label="Soal ${i}">${i}</button>`;
      }
      $('#mobile-nav-grid').html(mobileHtml);

      $('#mobile-nav-grid .nav-soal').off('click').on('click', function (e) {
        e.preventDefault();
        const idx = $(this).data('index');
        if (perQuestionMode) return;
        current = idx;
        localStorage.setItem(currentKey, current.toString());
        $('#mobile-dropside-panel').hide().attr('aria-hidden','true');
        getSoal(current);
      });
  }

  // -----------------------
  // Per-question timer funcs
  // -----------------------
  function startPerQuestionTimer() {
    if (window.__quizIsUnlimited) return;
    if (!perQuestionMode) return;

    stopPerQuestionTimer();

    // load stored duration if available
    const storedPerDur = parseInt(localStorage.getItem(perDurationKey));
    if (!isNaN(storedPerDur) && storedPerDur > 0) {
      perQuestionDuration = storedPerDur;
    } else {
      try { localStorage.setItem(perDurationKey, perQuestionDuration.toString()); } catch (e) {}
    }

    const now = Date.now();
    const savedStart = parseInt(localStorage.getItem(perStartKey));
    const savedPerCur = parseInt(localStorage.getItem(perCurrentKey));

    if (!isNaN(savedStart) && !isNaN(savedPerCur) && savedPerCur === current) {
        const storedEnd = parseInt(localStorage.getItem(perEndKey));
        if (!isNaN(storedEnd) && storedEnd > now) {
            perQuestionEnd = storedEnd;
        } else {
            const newStart = now;
            perQuestionEnd = newStart + perQuestionDuration;
            try {
              localStorage.setItem(perStartKey, newStart.toString());
              localStorage.setItem(perEndKey, perQuestionEnd.toString());
              localStorage.setItem(perCurrentKey, current.toString());
              localStorage.setItem(perDurationKey, perQuestionDuration.toString());
            } catch (e) {}
        }
    } else {
        const start = now;
        perQuestionEnd = start + perQuestionDuration;
        try {
          localStorage.setItem(perStartKey, start.toString());
          localStorage.setItem(perEndKey, perQuestionEnd.toString());
          localStorage.setItem(perCurrentKey, current.toString());
          localStorage.setItem(perDurationKey, perQuestionDuration.toString());
        } catch (e) {}
    }

    perQuestionTick();
    perQuestionInterval = setInterval(perQuestionTick, perQuestionIntervalDelay);
  }

  function stopPerQuestionTimer() {
      if (perQuestionInterval) {
          clearInterval(perQuestionInterval);
          perQuestionInterval = null;
      }
  }

  function perQuestionTick() {
      if (!perQuestionEnd) return;
      const now = Date.now();
      const remaining = perQuestionEnd - now;
      if (remaining <= 0) {
          // cleanup per-question saved keys for that slot
          localStorage.removeItem(perStartKey);
          localStorage.removeItem(perEndKey);
          localStorage.removeItem(perCurrentKey);
          // keep perDurationKey so resume uses same length across refresh

          stopPerQuestionTimer();
          if (current < jumlahSoal) {
              current++;
              localStorage.setItem(currentKey, current.toString());
              getSoal(current);
          } else {
              kirimJawaban();
          }
          return;
      }
      const seconds = Math.ceil(remaining / 1000);
      const minutesPart = Math.floor(seconds / 60).toString().padStart(2, '0');
      const secondsPart = (seconds % 60).toString().padStart(2, '0');
      $('#timer').text(`${minutesPart}:${secondsPart}`);
  }

  // -----------------------
  // Submit / cleanup
  // -----------------------
  function kirimJawaban() {
      stopPerQuestionTimer();
      stopTotalTimer();

      const jumlah = document.getElementById("jumlah_soal").value;
      const pilihan = jawabanSementara;

      form.querySelectorAll('input[name^="answers"]').forEach(el => el.remove());

      for (let i = 1; i <= jumlah; i++) {
        if (Array.isArray(pilihan[i])) {
          pilihan[i].forEach(val => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = `answers[${i}][]`;
            input.value = val;
            form.appendChild(input);
          });
        } else {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = `answers[${i}]`;
          input.value = pilihan[i] || '';
          form.appendChild(input);
        }
      }

      // cleanup persistence
      localStorage.removeItem(currentKey);
      localStorage.removeItem(totalStartKey);
      localStorage.removeItem(totalDurationKey);
      localStorage.removeItem(perStartKey);
      localStorage.removeItem(perEndKey);
      localStorage.removeItem(perCurrentKey);
      // optional: remove perDurationKey if you don't want it kept after submit
      try { localStorage.removeItem(perDurationKey); } catch (e) {}

      sessionStorage.removeItem("jawabanSementara");

      form.submit();
  }

  // attach submit listener
  document.getElementById("form").addEventListener("submit", function(e) {
    e.preventDefault();
    kirimJawaban();
  });

  // cleanup intervals on unload
  $(window).on('beforeunload', function() {
    stopPerQuestionTimer();
    stopTotalTimer();
  });

});
