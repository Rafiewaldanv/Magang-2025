function appendSoal(array){
    var div_all = $('<div class="row"></div>');
    for(var i = 0; i < array.length; i++){
        div_all.append(array[i]);
    }
    return div_all;
}


// opsiPilihan(1, ['Ya', 'Tidak'], 'radio', 'col-md-4', 'jawaban', ['yes', 'no']);
// opsiPilihan(1, ['a.jpg', 'b.jpg'], 'radio', 'col-md-4', 'jawaban', ['A', 'B'], true, '/img/jawaban');
// num = nomor soal (indeks)
// array = array jawaban ['a.jpg', 'b.jpg']
// type = radio, checkbox
// class_row = class css input
// name = name attribute input
// value = value array ['A', 'B']

function opsiPilihan(num, array, type, class_row, name, value, isImage = false, src = '') {
    const all_array = [];
    const suffix = num - 1;

    for (let i = 0; i < array.length; i++) {
        const idSuffix = String.fromCharCode(65 + i); // A, B, C, ...
        const content = isImage
            ? `<img src="${src}/${array[i][suffix]}" />`
            : array[i][suffix];

        const label = $(`
            <p class="${class_row}">
                <label id="id_work_days">
                    <input type="${type}" class="radioClass${suffix}" id="radio${idSuffix}${suffix}" name="${name}[${suffix}]" value="${value[i]}">
                    <span class="text-center">${content}</span>
                </label>
            </p>
        `);

        all_array.push(label);
    }

    return all_array;
}


//untuk soal text dan opsi jawaban text single selected
function textSoal(num, quest, label, type, src, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const suffix = num - 1;
    const soal = $('<p style="font-size: 20px">' + quest[0]['soal'][suffix] + '</p>');

    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value);

    opsiJawaban.unshift(soal);

    if (src != null) {
        const image_soal = $('<img class="gambar-soal" src="' + src + '"/>');
        opsiJawaban.unshift(image_soal);
    }

    return appendSoal(opsiJawaban);
}
//opsi jawaban text tanpa ada soal
function textNoSoal(num, quest, label, type, name, value, class_cek) {
    const class_row = class_cek || 'col-12 col-sm-6 col-xl-4';
    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value);
    return appendSoal(opsiJawaban);
}

//untuk soal text dan opsi jawaban dengan gambar multi selected
function textGambar(num, quest, src, label, type, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const suffix = num - 1;

    const div_all = $('<div class="row"></div>');
    const soal = $('<p>' + quest[0]['soal'][suffix] + '</p>');

    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value, true, src);
    const div_cek = appendSoal(opsiJawaban);

    div_all.append(soal);
    div_all.append(div_cek);

    return div_all;
}

//untuk soal gambar dan opsi jawaban GAMBAR multi selected
function gambarGambar(num, quest, src, label, type, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const suffix = num - 1;
    const div_all = $('<div class="row"></div>');
    const soalImg = $(`<img class="gambar-soal ${class_row}" src="${src}/${quest[0]['soal'][suffix]}" />`);
    div_all.append(soalImg);
    // Spacer <p><br></p>
    div_all.append($('<p><br></p>'));
    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value, true, src);
    const div_cek = appendSoal(opsiJawaban);

    div_all.append(div_cek);
    return div_all;
}

//tanpa ada soal dan opsinya gambar dengan multi selected
function gambarNoSoal(num, quest, src, label, type, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const div_opsi = $('<div class="row"></div>');

    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value, true, src);

    div_opsi.append(opsiJawaban);
    return div_opsi;
}

//gambar soal dengan opsi jawabannya text
function gambarSoal(num, quest, src, label, type, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const suffix = num - 1;
    const div_all = $('<div></div>');
    const div_opsi = $('<div class="row"></div>');

    const soal = $(`<img class="gambar-soal ${class_row}" src="${src[0]}/${quest[0]['soal'][suffix]}" />`);
    div_all.append(soal);
    div_all.append($('<br><br>'));

    if (src.length > 1) {
        const add_soal = $(`<img class="col-12" style="width:50%" src="${src[1]}" /><br><br>`);
        div_all.append(add_soal);
    }

    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value, false);
    div_opsi.append(opsiJawaban);
    div_all.append(div_opsi);

    return div_all;
}

function gambarSoalEx(num, quest, src, label, type, name, value) {
    const class_row = 'col-12 col-sm-6 col-xl-4';
    const suffix = num - 1;
    const div_all = $('<div></div>');
    const div_opsi = $('<div class="row"></div>');
    const div_cek = $('<div class="row"></div>');

    if (src.length > 1) {
        for (let i = 0; i < src.length; i++) {
            if (i % 2 === 1) {
                const add_soal = $(`<img class="col-12" style="width:40px" src="${src[i]}" /><br><br>`);
                div_cek.append(add_soal);
            }
        }
    }

    const opsiJawaban = opsiPilihan(num, label, type, class_row, name, value, false);
    div_opsi.append(opsiJawaban);

    div_all.append($('<br><br>'));
    div_all.append(div_cek);
    div_all.append($('<br><br>'));
    div_all.append(div_opsi);

    return div_all;
}




