function keep_number_value_positive(input_id, def_value) {
    let raw_value = $(`#${input_id}`).val();
    if (!(Number.isInteger(parseInt(raw_value)) && raw_value > 0)) {
        $(`#${input_id}`).val(def_value);
    }
}