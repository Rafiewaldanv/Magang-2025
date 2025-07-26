// File: public/assets/js/quiz-render.js

const testID = $('#form input[name="test_id"]').val();
const packetID = $('#form input[name="packet_id"]').val();
const path = $('#form input[name="path"]').val();
const jumlahSoal = parseInt($('#jumlah_soal').val());
console.log('Mengirim request soal:', testID, packetID);

// 🔥 Load soal via AJAX
function loadQuestion(nomor) {
  $.ajax({
    url: `/api/soal/${testID}/${packetID}/${nomor}`,
    type: 'GET',
    success: function(questionData) {
      renderQuestion(questionData);
      updateAnsweredCount();
      sessionStorage.setItem('currentQuestion', nomor);
      updateNavButton(nomor, jumlahSoal);

      // Update sidebar nomor soal aktif
      $('.nav-number').removeClass('active');
      $(`.nav-number[data-num="${nomor}"]`).addClass('active');
    },
    error: function () {
      alert('Gagal memuat soal.');
    }
  });
}

// ✅ Render komponen soal & pilihan
function renderQuestion(questionData) {
  const container = document.querySelector('.s');
  if (!container || !questionData) return;

  let html = '';

  if (questionData.questionImage)
    html += `<img src="../assets/images/gambar/${questionData.questionImage}" class="img-fluid mb-3">`;

  if (questionData.questionText)
    html += `<p>${questionData.questionText}</p>`;

  questionData.options.forEach((opt, index) => {
    const inputType = questionData.multiSelect ? 'checkbox' : 'radio';
    const optionContent = opt.image
      ? `<img src="../assets/images/gambar/${opt.image}" class="img-option">`
      : opt.text;

    const inputId = `opt-${questionData.number}-${index}`;

    html += `
      <div class="form-check option mb-2" data-num="${questionData.number}">
        <input type="${inputType}" name="option-${questionData.number}" value="${opt.value}" class="form-check-input" id="${inputId}">
        <label class="form-check-label" for="${inputId}">${optionContent}</label>
      </div>`;
  });

  container.innerHTML = html;

  highlightSelectedOption(questionData.number);
  localStorage.setItem('currentQuestion', questionData.number);
}

//  Tandai opsi terpilih
function highlightSelectedOption(num) {
  const answers = JSON.parse(localStorage.getItem('answers') || '{}');
  const values = answers[num] || [];

  document.querySelectorAll(`input[name="option-${num}"]`).forEach((input) => {
    const wrapper = input.closest('.option');

    if (values.includes(input.value)) {
      input.checked = true;
      wrapper.classList.add('active');
    } else {
      input.checked = false;
      wrapper.classList.remove('active');
    }
  });
}

//  Simpan jawaban saat memilih
document.addEventListener("change", function (e) {
  if (!e.target.name.startsWith('option-')) return;

  let answers = JSON.parse(localStorage.getItem('answers') || '{}');
  const num = e.target.name.split('-')[1];

  if (!answers[num]) answers[num] = [];

  if (e.target.type === 'checkbox') {
    if (e.target.checked && !answers[num].includes(e.target.value)) {
      answers[num].push(e.target.value);
    } else if (!e.target.checked) {
      answers[num] = answers[num].filter(val => val !== e.target.value);
    }
  } else {
    answers[num] = [e.target.value];
  }

  localStorage.setItem('answers', JSON.stringify(answers));
  localStorage.setItem('currentQuestion', num);

  highlightSelectedOption(num);
  updateAnsweredCount();
});

// Hitung soal yang terjawab
function updateAnsweredCount() {
  const answers = JSON.parse(localStorage.getItem('answers') || '{}');
  const totalAnswered = Object.values(answers).filter(val => val.length > 0).length;
  document.getElementById('answered').textContent = totalAnswered;
}

//  Toggle tombol Next dan Prev
function updateNavButton(nomor, totalSoal) {
  $('#next').toggle(nomor < totalSoal);
  $('#prev').toggle(nomor > 1);
}

//  Auto-submit saat waktu habis
function handleTimeoutSubmit() {
  const answers = JSON.parse(localStorage.getItem('answers') || '{}');

  $.ajax({
    url: `/tes/${path}/submit`,
    type: 'POST',
    data: {
      answers: answers,
      _token: $('meta[name="csrf-token"]').attr('content'),
      test_id: testID,
      packet_id: packetID
    },
    success: function() {
      localStorage.clear();
      sessionStorage.clear();
      window.location.href = '/tes/selesai';
    },
    error: function () {
      alert('Gagal submit jawaban.');
    }
  });
}

//  Saat halaman dimuat, tampilkan soal terakhir
$(document).ready(function () {
  const currentNum = sessionStorage.getItem('currentQuestion') || 1;
  loadQuestion(currentNum);
});

// Backup saat keluar halaman
window.addEventListener("beforeunload", function () {
  localStorage.setItem('answers_backup', localStorage.getItem('answers'));
});
