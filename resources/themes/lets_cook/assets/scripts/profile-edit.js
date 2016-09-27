
/* ----- PROFILE EDIT ----- */

function profileEdit() {
    let $profileEdit = $('.profile-edit'),
        $inputBirthday = $('#f-personal-data-add-birthday'),
        $selectCity = $('#f-personal-data-add-city'),
        $anotherCity = $profileEdit.find('.order-personal__row[data-row="another-city"]');

    datePicker($inputBirthday);

    selectCity();

    // SELECT CHANGE
    $selectCity.on('change', function() {
        selectCity();
    });

    function selectCity() {
        let $lastOption = $selectCity.find('option:last');

        if ($lastOption.is(':selected')) {
            $anotherCity.attr('data-active', '');
        } else {
            $anotherCity.removeAttr('data-active');
            $anotherCity.find('input').val('');
        }
    }
}

/* ----- end PROFILE EDIT ----- */