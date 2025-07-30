$(document).ready(function () {
    const testId = $('#form input[name="test_id"]').val();
    const packetId = $('#form input[name="packet_id"]').val();
    const jumlahSoal = parseInt($('#jumlah_soal').val());
    let current = 1;
    const jawabanSementara = {};

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
                updatePanelNavigasi(); // update warna nav tombol
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
                ${data.options.map(opt => {
                    const isChecked = (
                        Array.isArray(jawabanSementara[data.number])
                            ? jawabanSementara[data.number].includes(opt.value)
                            : jawabanSementara[data.number] === opt.value
                    ) ? 'checked' : '';
                    return `
                        <label class="list-group-item">
                            <input type="${data.multiSelect ? 'checkbox' : 'radio'}"
                                name="answer_${data.number}${data.multiSelect ? '[]' : ''}" 
                                value="${opt.value}"
                                class="form-check-input me-1"
                                ${isChecked}>
                            ${opt.text}
                        </label>
                    `;
                }).join('')}
            </div>
        `);

        // Event listener simpan jawaban
        $(`input[name^="answer_${data.number}"]`).change(function () {
            if (data.multiSelect) {
                const selected = [];
                $(`input[name="answer_${data.number}[]"]:checked`).each(function () {
                    selected.push($(this).val());
                });
                jawabanSementara[data.number] = selected;
                updateJawabanForm(data.number, selected.join(','));
            } else {
                const selected = $(this).val();
                jawabanSementara[data.number] = selected;
                updateJawabanForm(data.number, selected);
            }

            updateSoalTerjawab();
        });

        // Tambahkan hidden input jika belum ada
        if (!$(`#form input[name="answer_${data.number}"]`).length) {
            $('#form').append(`<input type="hidden" name="answer_${data.number}" value="">`);
        }

        // Set ulang nilai hidden dari jawaban sebelumnya
        if (jawabanSementara[data.number]) {
            updateJawabanForm(data.number, Array.isArray(jawabanSementara[data.number]) ? jawabanSementara[data.number].join(',') : jawabanSementara[data.number]);
        }
    }

    function updateJawabanForm(qid, val) {
        $(`#form input[name="answer_${qid}"]`).val(val);
    }

    function updateSoalTerjawab() {
        const totalJawab = Object.keys(jawabanSementara).filter(k => {
            const val = jawabanSementara[k];
            return Array.isArray(val) ? val.length > 0 : val !== '';
        }).length;

        $('#answered').text(totalJawab);
        $('#totals').text(jumlahSoal);

        const sudahSemua = totalJawab === jumlahSoal;
        $('#btn-submit').prop('disabled', !sudahSemua).toggle(sudahSemua);
        $('#btn-nextj').prop('disabled', sudahSemua).toggle(!sudahSemua);

        updatePanelNavigasi();
    }

    function updateNavigasi(nomor) {
        $('#prev').toggle(nomor > 1);
        $('#next').toggle(nomor < jumlahSoal);
    }

    function updatePanelNavigasi() {
        let html = '';
        for (let i = 1; i <= jumlahSoal; i++) {
            const isAnswered = jawabanSementara[i];
            const hasAnswer = Array.isArray(isAnswered) ? isAnswered.length > 0 : !!isAnswered;
            const btnClass = hasAnswer ? 'btn-success' : 'btn-outline-secondary';
            html += `<button type="button" class="btn ${btnClass} btn-sm m-1 nav-soal" data-index="${i}">${i}</button>`;
        }
        $('#soal-container').html(html);

        $('.nav-soal').click(function () {
            const index = $(this).data('index');
            current = index;
            getSoal(current);
        });
    }
});
