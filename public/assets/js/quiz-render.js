$(document).ready(function () {
    const testId = $('#form input[name="test_id"]').val();
    const packetId = $('#form input[name="packet_id"]').val();
    const jumlahSoal = parseInt($('#jumlah_soal').val());
    let current = 1;
    const jawabanSementara = {};

    // Memuat soal pertama
    getSoal(current);

    $('#next').click(function () {
        if (current < jumlahSoal) {
            current++;
            getSoal(current);
        }
    });

    $('#prev').click(function () {
        if (current > 1) {
            current--;
            getSoal(current);
        }
    });

    function getSoal(nomor) {
        $('#overlay-loading').show();
        $.ajax({
            url: `/api/soal/${testId}/${packetId}/${nomor}`,
            type: 'GET',
            success: function (res) {
                $('#overlay-loading').hide();
                tampilkanSoal(res, nomor);
                updateNavigasi(nomor);
            },
            error: function () {
                $('#overlay-loading').hide();
                alert("Gagal memuat soal. Silakan refresh halaman.");
            }
        });
    }

    function tampilkanSoal(data, nomor) {
      $('.soal_number .num').text(`Soal Nomor ${nomor}`);
      $('.s').html(`
          <p>${data.questionText}</p>
          ${data.questionImage ? `<img src="/storage/${data.questionImage}" class="img-fluid mb-2">` : ''}
          <div class="list-group">
              ${data.options.map(opt => `
                  <label class="list-group-item">
                      <input type="${data.multiSelect ? 'checkbox' : 'radio'}"
                          name="answer_${data.number}${data.multiSelect ? '[]' : ''}" 
                          value="${opt.value}"
                          class="form-check-input me-1"
                          ${jawabanSementara[data.number] === opt.value ? 'checked' : ''}>
                      ${opt.text}
                  </label>
              `).join('')}
          </div>
      `);
  
      $(`input[name="answer_${data.number}"]`).change(function () {
          jawabanSementara[data.number] = $(this).val();
          updateJawabanForm(data.number, $(this).val());
          updateSoalTerjawab();
      });
  
      if (!$(`#form input[name="answer_${data.number}"]`).length) {
          $('#form').append(`<input type="hidden" name="answer_${data.number}" value="">`);
      }
  
      $('#jumlah_soal').val(jumlahSoal);
  }
  

    function updateJawabanForm(qid, oid) {
        $(`#form input[name="answer_${qid}"]`).val(oid);
    }

    function updateSoalTerjawab() {
        const totalJawab = Object.keys(jawabanSementara).length;
        $('#answered').text(totalJawab);
        $('#totals').text(jumlahSoal);

        if (totalJawab === jumlahSoal) {
            $('#btn-submit').show();
            $('#btn-nextj').hide();
        } else {
            $('#btn-submit').hide();
            $('#btn-nextj').show();
        }

        updatePanelNavigasi();
    }

    function updateNavigasi(nomor) {
        $('#prev').toggle(nomor > 1);
        $('#next').toggle(nomor < jumlahSoal);
    }

    function updatePanelNavigasi() {
        let html = '';
        for (let i = 1; i <= jumlahSoal; i++) {
            const answered = Object.values(jawabanSementara).includes(i) ? 'btn-success' : 'btn-outline-secondary';
            html += `<button type="button" class="btn ${answered} btn-sm m-1 nav-soal" data-index="${i}">${i}</button>`;
        }
        $('#soal-container').html(html);

        $('.nav-soal').click(function () {
            const index = $(this).data('index');
            current = index;
            getSoal(current);
        });
    }

});
