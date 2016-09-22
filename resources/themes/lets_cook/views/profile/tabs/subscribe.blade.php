<div class="profile-subscribe__header">
    <div class="profile-subscribe__drop">
        <div class="profile-subscribe__select-wrapper">
            <div class="profile-subscribe__select order-select">
                <select name="profile-subscribe__name" id="profile-subscribe-name">
                    <option value="1" selected>Простая классика (3600 руб.)</option>
                    <option value="2">Простая классика 2 (1255 руб.)</option>
                    <option value="3">Простая классика 3 (8946 руб.)</option>
                    <option value="4">Простая классика 4 (6878 руб.)</option>
                </select>
            </div>

            <div class="profile-subscribe__add">
                <div class="profile-subscribe__add-button">
                    <span data-icon>+</span>
                    <span data-device="mobile">Дополнительно</span>
                    <span data-device="desktop">доп.</span>
                </div>

                <div class="profile-subscribe__add-wrapper">
                    <div class="profile-subscribe__add-close"></div>

                    <ul class="profile-subscribe__add-list">
                        <li class="profile-subscribe__add-item square-red-checkbox">
                            <input type="checkbox" id="order-more-1" name="order-more" value="1">
                            <label for="order-more-1">Корзина с фруктами (1200 руб.)</label>
                        </li>

                        <li class="profile-subscribe__add-item square-red-checkbox">
                            <input type="checkbox" id="order-more-2" name="order-more" value="2">
                            <label for="order-more-2">Корзина с завтраками (1200 руб.)</label>
                        </li>

                        <li class="profile-subscribe__add-item square-red-checkbox">
                            <input type="checkbox" id="order-more-3" name="order-more" value="3">
                            <label for="order-more-3">Корзина со специями (1200 руб.)</label>
                        </li>

                        <li class="profile-subscribe__add-item square-red-checkbox">
                            <input type="checkbox" id="order-more-4" name="order-more" value="4">
                            <label for="order-more-4">Корзина с водой (1200 руб.)</label>
                        </li>

                        <li class="profile-subscribe__add-item square-red-checkbox">
                            <input type="checkbox" id="order-more-5" name="order-more" value="5">
                            <label for="order-more-5">Корзина с фруктами (1200 руб.)</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="profile-subscribe__select-wrapper">
            <div class="profile-subscribe__select order-select">
                <select name="profile-subscribe__date" id="profile-subscribe-date">
                    <option value="1" selected>18 октября</option>
                    <option value="2">20 октября</option>
                    <option value="3">30 октября</option>
                    <option value="4">31 октября</option>
                </select>
            </div>
        </div>
    </div>

    <div class="profile-subscribe__delivery order-delivery">
        <div class="order-delivery__day">
            <div class="order-delivery__day-radio order-delivery-radio">
                <input type="radio" id="order-subs-del-radio-1" name="order-subs-del-day" value="sunday" checked>
                <label for="order-subs-del-radio-1">Воскресенье</label>
            </div>

            <div class="order-delivery__day-radio order-delivery-radio">
                <input type="radio" id="order-subs-del-radio-2" name="order-subs-del-day" value="monday">
                <label for="order-subs-del-radio-2">Понедельник</label>
            </div>
        </div>

        <div class="order-delivery__time">
            <div class="order-delivery__time-radio order-delivery-radio">
                <input type="radio" id="order-subs-del-radio-3" name="order-subs-del-time" value="morning">
                <label for="order-subs-del-radio-3">Утро</label>
            </div>

            <div class="order-delivery__time-radio order-delivery-radio">
                <input type="radio" id="order-subs-del-radio-4" name="order-subs-del-time" value="day" checked>
                <label for="order-subs-del-radio-4">День</label>
            </div>

            <div class="order-delivery__time-radio order-delivery-radio">
                <input type="radio" id="order-subs-del-radio-5" name="order-subs-del-time" value="evening">
                <label for="order-subs-del-radio-5">Вечер</label>
            </div>
        </div>
    </div>

    <div class="profile-subscribe__subscribe">
        <div class="profile-subscribe__subscribe-checkbox square-red-checkbox">
            <input type="checkbox" id="order-subscribe-1" name="order-subscribe" value="1">
            <label for="order-subscribe-1">Каждую неделю</label>
        </div>

        <div class="profile-subscribe__subscribe-checkbox square-red-checkbox">
            <input type="checkbox" id="order-subscribe-2" name="order-subscribe" value="2">
            <label for="order-subscribe-2">Каждые 2 недели</label>
        </div>
    </div>
</div>

<ul class="profile-subscribe__table subscribe-table">
    <li class="subscribe-table__row">
        <div class="subscribe-table__basket">
            <span>Простая классика</span>
            <span>+ корзина с фруктами, корзина со специями</span>
        </div>

        <div class="subscribe-table__when">18 октября, воскресенье, утро</div>

        <div class="subscribe-table__functional">
            <div class="subscribe-table__change">Изменить</div>

            <div class="subscribe-table__check table-checkbox">
                <input type="checkbox" id="subscribe-check-1" name="subscribe-check" value="1">
                <label for="subscribe-check-1"></label>
            </div>

            <div class="subscribe-table__delete"></div>
        </div>
    </li>

    <li class="subscribe-table__row">
        <div class="subscribe-table__basket">
            <span>Простая классика</span>
            <span>+ корзина с фруктами, корзина со специями</span>
        </div>

        <div class="subscribe-table__when">18 октября, воскресенье, утро</div>

        <div class="subscribe-table__functional">
            <div class="subscribe-table__change">Изменить</div>

            <div class="subscribe-table__check table-checkbox">
                <input type="checkbox" id="subscribe-check-2" name="subscribe-check" value="1">
                <label for="subscribe-check-2"></label>
            </div>

            <div class="subscribe-table__delete"></div>
        </div>
    </li>
</ul>

<div class="profile-subscribe__submit">
    <div class="profile-subscribe__submit-wrapper">Подписаться</div>
</div>

<div class="profile-orders-content__prev-orders">
    <div class="profile-orders-content__tabs-title" data-tab="prev-orders">Предыдущие заказы</div>

    <div class="profile-orders-content__main profile-orders-own" data-tab="prev-orders">
        <ul class="profile-orders-own__list">
            <li class="profile-orders-own__item own-order" data-order="even">
                <a href="#" class="own-order__change-link">
                    <div class="own-order__image" style="background-image: url('images/recipes-list/recipes-list-1.jpg');">
                        <div class="own-order__retry"><span>Повторить</span></div>
                    </div>

                    <div class="own-order__info">
                        <div class="own-order__title">Простая класика</div>

                        <ul class="own-order__count-list">
                            <li class="own-order__count-item">
                                <span>5</span>
                                <span>ужинов</span>
                            </li>

                            <li class="own-order__count-item">
                                <span>4</span>
                                <span>порции</span>
                            </li>
                        </ul>

                        <div class="own-order__when">18 октября, воскресенье вечер</div>

                        <div class="own-order__price">5600 руб.</div>
                    </div>
                </a>
            </li>

            <li class="profile-orders-own__item own-order" data-order="odd">
                <a href="#" class="own-order__change-link">
                    <div class="own-order__image" style="background-image: url('images/recipes-list/recipes-list-1.jpg');">
                        <div class="own-order__retry"><span>Повторить</span></div>
                    </div>

                    <div class="own-order__info">
                        <div class="own-order__title">Простая класика</div>

                        <ul class="own-order__count-list">
                            <li class="own-order__count-item">
                                <span>5</span>
                                <span>ужинов</span>
                            </li>

                            <li class="own-order__count-item">
                                <span>4</span>
                                <span>порции</span>
                            </li>
                        </ul>

                        <div class="own-order__when">18 октября, воскресенье вечер</div>

                        <div class="own-order__price">5600 руб.</div>
                    </div>
                </a>
            </li>

            <li class="profile-orders-own__item own-order" data-order="even">
                <a href="#" class="own-order__change-link">
                    <div class="own-order__image" style="background-image: url('images/recipes-list/recipes-list-1.jpg');">
                        <div class="own-order__retry"><span>Повторить</span></div>
                    </div>

                    <div class="own-order__info">
                        <div class="own-order__title">Простая класика</div>

                        <ul class="own-order__count-list">
                            <li class="own-order__count-item">
                                <span>5</span>
                                <span>ужинов</span>
                            </li>

                            <li class="own-order__count-item">
                                <span>4</span>
                                <span>порции</span>
                            </li>
                        </ul>

                        <div class="own-order__when">18 октября, воскресенье вечер</div>

                        <div class="own-order__price">5600 руб.</div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>