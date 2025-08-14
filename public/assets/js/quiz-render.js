$(document).ready(function () {
    const testId = $('#form input[name="test_id"]').val();
    const packetId = $('#form input[name="packet_id"]').val();
    const part = $('#form input[name="part"]').val() || 1; // <-- ditambahkan: baca part (default 1)
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

    // key unik per packet
    const startKey = `quizStartTime_${packetId}`;

    // total durasi (15 menit)
    const totalTime = 15 * 60 * 1000;

    // ambil / set startTime spesifik packet
    let startTime = localStorage.getItem(startKey);
    if (!startTime) {
        startTime = Date.now();
        localStorage.setItem(startKey, startTime);
    } else {
        startTime = parseInt(startTime);
    }

    function updateTimer() {
        const now = Date.now();
        const elapsed = now - startTime;
        const remaining = totalTime - elapsed;

        if (remaining <= 0) {
            $('#timer').text('00:00');
            // auto-submit apabila waktu habis
            localStorage.removeItem(startKey);
            sessionStorage.removeItem('jawabanSementara');
            setTimeout(() => kirimJawaban(), 300);
            return;
        }

        const minutes = Math.floor((remaining / 1000) / 60);
        const seconds = Math.floor((remaining / 1000) % 60);
        $('#timer').text(
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
        );
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // ===== MODIFIKASI MINIMAL DI SINI: kirimJawaban pakai AJAX ke /soal/simpan =====
    function kirimJawaban() {
        const jumlah = jumlahSoal;
        const pilihan = jawabanSementara;

        // siapkan payload sesuai controller: test_id, packet_id, part, answers
        const payload = {
            test_id: parseInt(testId),
            packet_id: parseInt(packetId),
            part: parseInt(part),
            answers: pilihan
        };

        // tampilkan loading
        $('#overlay-loading').show();
        $('#btn-submit').prop('disabled', true);

        const token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/soal/simpan',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            success: function (res) {
                $('#overlay-loading').hide();
                // hapus storage
                localStorage.removeItem(startKey);
                sessionStorage.removeItem('jawabanSementara');

                if (res.status === 'lanjut') {
                    // lanjut ke part berikutnya (server berikan next_packet_id/part)
                    alert(res.message || 'Part disimpan. Lanjut ke part berikutnya.');
                    // simplest: reload agar backend siap dengan packet baru
                    location.reload();
                    return;
                }

                if (res.status === 'selesai') {
                    if (res.redirect) {
                        window.location.href = res.redirect;
                        return;
                    }
                    if (res.result) {
                        alert(`Tes selesai!\nScore: ${res.result.score}%\nBenar: ${res.result.total_correct}\nSalah: ${res.result.total_wrong}`);
                        window.location.href = '/history';
                        return;
                    }
                    window.location.href = '/history';
                    return;
                }

                // fallback
                alert('Jawaban berhasil disimpan.');
                window.location.href = '/history';
            },
            error: function (xhr) {
                $('#overlay-loading').hide();
                $('#btn-submit').prop('disabled', false);
                if (xhr.status === 422) {
                    try {
                        const json = xhr.responseJSON;
                        if (json && json.unanswered) {
                            alert('Masih ada soal belum dijawab: ' + json.unanswered.join(', '));
                            return;
                        }
                        if (json && json.message) {
                            alert(json.message);
                            return;
                        }
                    } catch (e) {}
                    alert('Validasi gagal.');
                } else {
                    alert('Gagal mengirim jawaban. Silakan coba lagi.');
                }
            }
        });
    }
    // ===== akhir modifikasi minimal =====

});
