
/* ----- DATE PICKER ----- */

function datePicker($obj) {
    let picker = new Pikaday({
        field: document.getElementById($obj.attr('id')),
        firstDay: 1,
        format: 'D MMMM YYYY',
        i18n: {
            previousMonth : 'Предыдущий месяц',
            nextMonth     : 'Следующий месяц',
            months        : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            weekdays      : ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
            weekdaysShort : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
        }
    });
}

/* ----- end DATE PICKER ----- */