$(document).ready(function () {
  const testId = $('#form input[name="test_id"]').val();
  const packetId = $('#form input[name="packet_id"]').val();
  const jumlahSoal = parseInt($('#jumlah_soal').val());
  let current = 1;

  let jawabanSementara = JSON.parse(sessionStorage.getItem('jawabanSementara')) || {};
  Object.keys(jawabanSementara).forEach(k => { if (jawabanSementara[k] === "null") delete jawabanSementara[k]; });

  getSoal(current);

  $('#next').click(() => {
      if (current < jumlahSoal) {
          current++;
          getSoal(current);
      }
  });

  $('#prev').click(() => {
      if (current > 1) {
          current--;
          getSoal(current);
      }
  });

  function getSoal(nomor) {
      $('#overlay-loading').show();
      $.get(`/api/soal/${testId}/${packetId}/${nomor}`, function (data) {
          $('#overlay-loading').hide();
          tampilkanSoal(data, nomor);
          updateNavigasi(nomor);
          updatePanelNavigasi();
          updateSoalTerjawab();
      }).fail(function () {
          $('#overlay-loading').hide();
          alert("Gagal memuat soal. Silakan refresh halaman.");
      });
  }

  function tampilkanSoal(data, nomor) {
    $('.soal_number .num').text(`Soal Nomor ${nomor}`);
    const answered = jawabanSementara[nomor] !== undefined && jawabanSementara[nomor] !== "";

    // helper: dapatkan src gambar — kalau sudah URL penuh, pakai langsung,
    // kalau hanya filename, bangun dari data.path (packet id).
    const resolveImageSrc = (img) => {
        if (!img) return null;
        img = ('' + img).trim();
        // jika sudah path absolut atau mulai dengan http or slash, pakai langsung
        if (/^(https?:\/\/|\/|assets\/)/i.test(img)) return img.startsWith('/') ? img : (img.startsWith('http') ? img : '/' + img);
        // fallback: build dari packet path (data.path expected to be packet_id)
        const base = data.path ? `/assets/images/${data.path}/` : `/assets/images/`;
        return base + img;
    };

    const opsiHtml = (data.options || []).map(opt => {
        const stored = jawabanSementara[nomor];
        const isChecked = Array.isArray(stored)
            ? stored.includes(opt.value)
            : stored === opt.value;

        const checkedAttr = isChecked ? 'checked' : '';

        // resolve opt.image (could be full URL or filename)
        const imgSrc = resolveImageSrc(opt.image);

        const content = imgSrc
            ? `<img src="${imgSrc}" class="img-fluid" style="max-width:160px; display:block; margin:6px 0;">`
            : (opt.text ?? '');

        return `
            <label class="list-group-item d-flex align-items-center">
                <input type="${data.multiSelect ? 'checkbox' : 'radio'}"
                    name="answer_${nomor}${data.multiSelect ? '[]' : ''}" 
                    value="${opt.value}" class="form-check-input me-2" ${checkedAttr}>
                <div class="flex-grow-1">${content}</div>
            </label>`;
    }).join('');

    // soal image(s)
    let soalImageHtml = '';
    if (Array.isArray(data.questionImages) && data.questionImages.length) {
        soalImageHtml = data.questionImages
            .map(u => `<img src="${resolveImageSrc(u)}" class="img-fluid mb-2">`)
            .join('');
    } else if (data.questionImage) {
        soalImageHtml = `<img src="${resolveImageSrc(data.questionImage)}" class="img-fluid mb-2">`;
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
        <div class="list-group">${opsiHtml}</div>
        ${batalHtml}
    `);

    // event handlers (unchanged logic)
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
        if (!data.multiSelect && current < jumlahSoal) {
            setTimeout(() => getSoal(++current), 300);
        }
    });

    $('.batal-jawab').off('click').on('click', function () {
        const nomorSoal = $(this).data('nomor');
    
        // hapus jawaban di object
        delete jawabanSementara[nomorSoal];
        sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
    
        // uncheck semua input pilihan (tanpa reload ulang soal)
        $(`input[name^="answer_${nomorSoal}"]`).prop('checked', false);
    
        // sembunyikan tombol batal (optional)
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
              // 🧠 HITUNG ULANG DI SINI
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
      $('#prev').toggle(nomor > 1);
      $('#next').toggle(nomor < jumlahSoal);
  }

  function updatePanelNavigasi() {
      let html = '';
      for (let i = 1; i <= jumlahSoal; i++) {
          const isCurrent = i === current;
          const hasAnswer = Array.isArray(jawabanSementara[i]) ? jawabanSementara[i].length > 0 : !!jawabanSementara[i];
          let btnClass = isCurrent ? 'btn-success' : (hasAnswer ? 'btn-warning' : 'btn-outline-secondary');
          html += `<button type="button" class="btn ${btnClass} btn-sm m-1 nav-soal" data-index="${i}">${i}</button>`;
      }
      $('#soal-container').html(html);
      $('.nav-soal').off('click').on('click', function () {
          current = $(this).data('index');
          getSoal(current);
      });
  }

  // Tombol Submit diklik
  

  // --- di bagian atas (masih di $(document).ready(...)) --- // sudah ada di filemu
// key unik per packet
const startKey = `quizStartTime_${packetId}`;

// total durasi (15 menit)
const totalTime = 15 * 60 * 1000;

// ambil / set startTime spesifik packet
let startTime = localStorage.getItem(startKey);
if (!startTime) {
  // kalau belum ada, set sekarang (hanya set pertama kali saat user benar2 mulai)
  startTime = Date.now();
  localStorage.setItem(startKey, startTime);
} else {
  startTime = parseInt(startTime);
}

// timer update (update elemen #timer)
function updateTimer() {
  const now = Date.now();
  const elapsed = now - startTime;
  const remaining = totalTime - elapsed;

  if (remaining <= 0) {
      $('#timer').text('00:00');
      // auto-submit apabila waktu habis — panggil kirimJawaban otomatis
      // pastikan kirimJawaban sudah didefinisikan (diletakkan di file yang sama)
      // hapus key sebelum submit agar tidak tersisa
      localStorage.removeItem(startKey);
      sessionStorage.removeItem('jawabanSementara');
      // submit (tunda sedikit biar form ter-append dengan benar)
      setTimeout(() => kirimJawaban(), 300);
      return;
  }

  const minutes = Math.floor((remaining / 1000) / 60);
  const seconds = Math.floor((remaining / 1000) % 60);
  $('#timer').text(
      `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
  );
}

// jalankan timer setiap detik
updateTimer();
const timerInterval = setInterval(updateTimer, 1000);

// --- pada akhir file: ubah fungsi kirimJawaban() seperti ini ---
function kirimJawaban() {
  const jumlah = jumlahSoal;
  const pilihan = jawabanSementara;

  const form = $('<form>', {
      method: 'POST',
      action: '/soal/{path}/submit' // atau sesuai route finalmu
  });

  const token = $('meta[name="csrf-token"]').attr('content');
  form.append($('<input>', { type: 'hidden', name: '_token', value: token }));
  form.append($('<input>', { type: 'hidden', name: 'jumlah', value: jumlah }));
  form.append($('<input>', { type: 'hidden', name: 'test_id', value: testId }));
  form.append($('<input>', { type: 'hidden', name: 'packet_id', value: packetId }));

  // hitung time_taken (detik)
  const now = Date.now();
  const timeTakenMs = now - startTime;
  const timeTakenSec = Math.max(0, Math.round(timeTakenMs / 1000));
  form.append($('<input>', { type: 'hidden', name: 'time_taken', value: timeTakenSec }));

  for (let i = 1; i <= jumlah; i++) {
      form.append($('<input>', { type: 'hidden', name: `id[]`, value: i }));
      form.append($('<input>', {
          type: 'hidden',
          name: `pilihan[${i}]`,
          value: Array.isArray(pilihan[i]) ? pilihan[i].join(',') : (pilihan[i] || '')
      }));
  }

  // hapus storage spesifik packet sebelum submit (prevent reuse)
  localStorage.removeItem(startKey);
  sessionStorage.removeItem('jawabanSementara');

  $('body').append(form);
  form.submit();
}

});
