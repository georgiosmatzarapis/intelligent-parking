const pop_up_available_places_input_selector = "#pop_up_available_places_input";

function subtract_one() {
    let raw_value = $(pop_up_available_places_input_selector).val();
    if (Number.isInteger(parseInt(raw_value)) && raw_value > 0) {
        $(pop_up_available_places_input_selector).val(raw_value - 1);
    } else {
        $(pop_up_available_places_input_selector).val("0");
    }
}

function add_one() {
    let raw_value = $(pop_up_available_places_input_selector).val();
    if (Number.isInteger(parseInt(raw_value)) && raw_value > 0) {
        $(pop_up_available_places_input_selector).val(+raw_value + 1)
    } else {
        $(pop_up_available_places_input_selector).val("1");
    }
}
