$(document).ready(function () {
    const testId = $('#form input[name="test_id"]').val();
    const packetId = $('#form input[name="packet_id"]').val();
    const jumlahSoal = parseInt($('#jumlah_soal').val());
    let current = 1;

    // Load jawaban dari sessionStorage jika ada
    let jawabanSementara = JSON.parse(sessionStorage.getItem('jawabanSementara')) || {};
    Object.keys(jawabanSementara).forEach(k => { if (jawabanSementara[k] === "null") delete jawabanSementara[k]; });

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
            success: function (data) {
                $('#overlay-loading').hide();
                tampilkanSoal(data, nomor);
                updateNavigasi(nomor);
                updatePanelNavigasi();
                updateSoalTerjawab();
            },
            error: function () {
                $('#overlay-loading').hide();
                alert("Gagal memuat soal. Silakan refresh halaman.");
            }
        });
    }

    function tampilkanSoal(data, nomor) {
        $('.soal_number .num').text(`Soal Nomor ${nomor}`);
    
        // Cek apakah sudah ada jawaban
        const answered = jawabanSementara[nomor] !== undefined && jawabanSementara[nomor] !== "";
    
        // Render opsi jawaban
        const opsiHtml = data.options.map(opt => {
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
                </label>`;
        }).join('');
    
        // Jika sudah jawab, tampilkan tombol batal
        const batalButtonHtml = answered ? `
            <div class="text-start mt-2">
                <button type="button" class="btn btn-danger btn-sm batal-jawab" data-nomor="${nomor}">
                    Batal Pilihan
                </button>
            </div>` : '';
    
        $('.s').html(`
            <p>${data.questionText}</p>
            ${data.questionImage ? `<img src="/storage/${data.questionImage}" class="img-fluid mb-2">` : ''}
            <div class="list-group">
                ${opsiHtml}
            </div>
            ${batalButtonHtml}
        `);
    
        // Listener: input berubah (simpan jawaban)
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
    
            // Auto Next (opsional)
            if (!data.multiSelect && current < jumlahSoal) {
                setTimeout(() => getSoal(++current), 300);
            }
        });
    
        // Listener: tombol batal
        $('.batal-jawab').off('click').on('click', function () {
            const nomorSoal = $(this).data('nomor');
            delete jawabanSementara[nomorSoal];
            sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
            tampilkanSoal(data, nomorSoal); // render ulang soal
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

        const sudahSemua = totalJawab === jumlahSoal;
        $('#btn-nextj').hide();
        $('#btn-submit').show().prop('disabled', !sudahSemua);
        $('#btn-tiki').hide();
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
            let btnClass = 'btn-outline-secondary';
            if (isCurrent) btnClass = 'btn-success';
            else if (hasAnswer) btnClass = 'btn-warning';
            html += `<button type="button" class="btn ${btnClass} btn-sm m-1 nav-soal" data-index="${i}">${i}</button>`;
        }
        $('#soal-container').html(html);
        $('.nav-soal').off('click').on('click', function () {
            current = $(this).data('index');
            getSoal(current);
        });
    }
});
