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
                        value="${opt.value}" class="form-check-input me-1" ${isChecked}>
                    ${opt.text}
                </label>`;
        }).join('');

        const batalHtml = answered ? `
            <div class="text-start mt-2">
                <button type="button" class="btn btn-danger btn-sm batal-jawab" data-nomor="${nomor}">
                    Batal Pilihan
                </button>
            </div>` : '';

        $('.s').html(`
            <p>${data.questionText}</p>
            ${data.questionImage ? `<img src="/storage/${data.questionImage}" class="img-fluid mb-2">` : ''}
            <div class="list-group">${opsiHtml}</div>${batalHtml}
        `);

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
            delete jawabanSementara[$(this).data('nomor')];
            sessionStorage.setItem('jawabanSementara', JSON.stringify(jawabanSementara));
            tampilkanSoal(data, nomor);
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
    

    function kirimJawaban() {
        const jumlah = jumlahSoal;
        const pilihan = jawabanSementara;

        form = $('<form>', {
            method: 'POST',
            action: `/tes/${$('input[name="path"]').val()}/submit` // ✅ Sesuai route yang ada
        });
        

        const token = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { type: 'hidden', name: '_token', value: token }));
        form.append($('<input>', { type: 'hidden', name: 'jumlah', value: jumlah }));
        form.append($('<input>', { type: 'hidden', name: 'test_id', value: testId }));

        for (let i = 1; i <= jumlah; i++) {
            form.append($('<input>', { type: 'hidden', name: `id[]`, value: i }));
            form.append($('<input>', {
                type: 'hidden',
                name: `pilihan[${i}]`,
                value: Array.isArray(pilihan[i]) ? pilihan[i].join(',') : (pilihan[i] || '')
            }));
        }

        $('body').append(form);
        form.submit();
    }
});
