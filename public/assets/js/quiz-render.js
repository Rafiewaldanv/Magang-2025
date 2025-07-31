

$(document).ready(function () {
    const testId = $('#form input[name="test_id"]').val();
    const packetId = $('#form input[name="packet_id"]').val();
    const jumlahSoal = parseInt($('#jumlah_soal').val());
    let current = 1;

    // Load jawaban dari sessionStorage jika ada
    let jawabanSementara = JSON.parse(sessionStorage.getItem('jawabanSementara')) || {};

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
                updatePanelNavigasi();
                updateSoalTerjawab(); // update submit tombol saat load soal
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
                        Array.isArray(jawabanSementara[nomor])
                            ? jawabanSementara[nomor].includes(opt.value)
                            : jawabanSementara[nomor] === opt.value
                    ) ? 'checked' : '';
                    return `
                        <label class="list-group-item">
                            <input type="${data.multiSelect ? 'checkbox' : 'radio'}"
                                name="answer_${nomor}${data.multiSelect ? '[]' : ''}" 
                                value="${opt.value}"
                                class="form-check-input me-1"
                                ${isChecked}>
                            ${opt.text}
                        </label>
                    `;
                }).join('')}
            </div>
        `);
    
        // Event listener
        $(`input[name^="answer_${nomor}"]`).change(function () {
            if (data.multiSelect) {
                const selected = [];
                $(`input[name="answer_${nomor}[]"]:checked`).each(function () {
                    selected.push($(this).val());
                });
                jawabanSementara[nomor] = selected;
                updateJawabanForm(nomor, selected.join(','));
            } else {
                const selected = $(this).val();
                console.log('Nomor:', nomor, 'Selected:', selected);

                if (selected !== null && selected !== undefined) {
                    jawabanSementara[nomor] = selected;
                    updateJawabanForm(nomor, selected);
                }
            }
    
            sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
            updateSoalTerjawab();
    
            // Auto Next
            if (!data.multiSelect && current < jumlahSoal) {
                setTimeout(() => {
                    current++;
                    getSoal(current);
                }, 300);
            }
        });
    
        // Hidden input jika belum ada
        if (!$(`#form input[name="answer_${nomor}"]`).length) {
            $('#form').append(`<input type="hidden" name="answer_${nomor}" value="">`);
        }
    
        if (jawabanSementara[nomor]) {
            updateJawabanForm(
                nomor,
                Array.isArray(jawabanSementara[nomor])
                    ? jawabanSementara[nomor].join(',')
                    : jawabanSementara[nomor]
            );
        }
    }
    
    function updateJawabanForm(qid, val) {
        const inputField = $(`#form input[name="answer_${qid}"]`);
        if (val === null || val === "null") {
            inputField.val('');
        } else {
            inputField.val(val);
        }
    }
    
    function updateSoalTerjawab() {
        const totalJawab = Object.keys(jawabanSementara).filter(k => {
            const val = jawabanSementara[k];
            return Array.isArray(val) ? val.length > 0 : val !== '';
        }).length;

        $('#answered').text(totalJawab);
        $('#totals').text(jumlahSoal);

        const sudahSemua = totalJawab === jumlahSoal;


        // Hanya tombol #btn-nextj yang digunakan
        $('#btn-nextj').hide();
    
        // Sembunyikan tombol lain
        $('#btn-submit').show().prop('disabled', !sudahSemua);
        $('#btn-tiki').hide()

        updatePanelNavigasi();
    }

    function updateNavigasi(nomor) {
        $('#prev').toggle(nomor > 1);
        $('#next').toggle(nomor < jumlahSoal);
    }

   function updatePanelNavigasi() {
    let html = '';
    for (let i = 1; i <= jumlahSoal; i++) {
        const isCurrent = i === current;
        const isAnswered = jawabanSementara[i];
        const hasAnswer = Array.isArray(isAnswered) ? isAnswered.length > 0 : !!isAnswered;

        let btnClass = 'btn-outline-secondary'; // default (abu)
        if (isCurrent) {
            btnClass = 'btn-success'; // soal yang sedang ditampilkan
        } else if (hasAnswer) {
            btnClass = 'btn-warning'; // soal sudah dijawab
        }

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
