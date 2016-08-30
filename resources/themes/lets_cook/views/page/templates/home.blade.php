@extends('layouts.master')

@section('content')
    <main class="main">
        <section class="let-cook">
            <h3 class="let-cook__supTitle">Сервис доставки качественных продуктов на дом</h3>
            <h1 class="let-cook__title">&laquo; Давай готовить! &raquo;</h1>
            <h2 class="let-cook__subTitle">просто и вкусно</h2>

            <div class="make-order--desktop">
                <a href="#" class="choose-basket green-long-button">Выбрать корзину</a>

                <a href="#" class="demo-dinner">Заказать пробный ужин</a>
            </div>
        </section>

        <section class="make-order--mobile">
            <a href="#" class="choose-basket">Выбрать корзину</a>

            <a href="#" class="demo-dinner">Заказать пробный ужин</a>
        </section>

        <section class="about-food">
            <ul class="about-food__list">
                <li class="about-food__item">
                    <div class="about-food__img"
                         style="background-image: url('../images/about-food/about-food.jpeg')"></div>
                    <div class="about-food__title">Экономия времени</div>
                </li>
                <li class="about-food__item">
                    <div class="about-food__img"
                         style="background-image: url('../images/about-food/about-food.jpeg')"></div>
                    <div class="about-food__title">Качественные и свежие продукты</div>
                </li>
                <li class="about-food__item">
                    <div class="about-food__img"
                         style="background-image: url('../images/about-food/about-food.jpeg')"></div>
                    <div class="about-food__title">Оригинальные рецепты</div>
                </li>
                <li class="about-food__item">
                    <div class="about-food__img"
                         style="background-image: url('../images/about-food/about-food.jpeg')"></div>
                    <div class="about-food__title">Новое меню каждую неделю</div>
                </li>
            </ul>
        </section>

        <section class="about-service">
            <div class="about-service__title">
                <div class="about-service__icon"></div>
                <div class="about-service__text">Основатель о том, как работает сервис</div>
            </div>

            <div class="about-service__content">
                <div class="about-service__close"></div>

                <div class="about-service__video">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=0&showinfo=0&controls=0&enablejsapi=1"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </section>

        <section class="recipes-and-baskets">
            <div class="recipes-and-baskets__item recipes-menu">
                <ul class="recipes-menu__choose">
                    <li class="recipes-menu__chooseItem">Меню на эту неделю</li>
                    <li class="recipes-menu__chooseItem">Меню на следующую неделю</li>
                </ul>

                <ul class="recipes-menu__content">
                    <li class="recipes-menu__contentItem">
                        <ul class="recipes-menu__list">
                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-1.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #ffd8ae; color: #0a2229;">
                                        Белая рыба по-французски со свеклой</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-2.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #a0152d; color: #ffd8ae;">
                                        Курица с томатами на гриле и булгуром</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-3.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #ffd8ae; color: #0a2229;">
                                        Суп-пюре из шампиньонов</h3>
                                </a>
                            </li>
                        </ul>

                        <a href="#" class="recipes-menu__all yellow-small-button">Все рецепты</a>
                    </li>

                    <li class="recipes-menu__contentItem">
                        <ul class="recipes-menu__list">
                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-4.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #a0152d; color: #ffd8ae;">
                                        Курица с томатами на гриле и булгуром</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-1.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #6d0f31; color: #ffd8ae;">
                                        Белая рыба по-французски со свеклой</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-2.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #a0152d; color: #ffd8ae;">
                                        Суп-пюре из шампиньонов</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-5.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #1d5137; color: #ffd8ae;">
                                        Суп-пюре из шампиньонов</h3>
                                </a>
                            </li>

                            <li class="recipes-menu__item">
                                <a href="#" class="recipes-menu__link">
                                    <div class="recipes-menu__img"
                                         style="background-image: url('images/recipes-menu/recipe-menu-1-3.jpg');"></div>
                                    <h3 class="recipes-menu__title" style="background-color: #ffd8ae; color: #0a2229;">
                                        Суп-пюре из шампиньонов</h3>
                                </a>
                            </li>
                        </ul>

                        <a href="#" class="recipes-menu__all yellow-small-button">Все рецепты</a>
                    </li>
                </ul>
            </div>

            <div class="recipes-and-baskets__item baskets-menu">
                <h2 class="baskets-menu__title">наши корзины</h2>

                <table class="baskets-menu__list">
                    <tbody>
                    <tr>
                        <td><a href="#">Классическая корзина</a></td>
                        <td><span>5</span> ужинов</td>
                        <td><span>4</span> порции</td>
                    </tr>

                    <tr>
                        <td><a href="#">Три ужина</a></td>
                        <td><span>3</span> ужинов</td>
                        <td><span>3</span> порции</td>
                    </tr>

                    <tr>
                        <td><a href="#">Простая классика</a></td>
                        <td><span>4</span> ужинов</td>
                        <td><span>2</span> порции</td>
                    </tr>

                    <tr>
                        <td><a href="#">Четыре ужина</a></td>
                        <td><span>2</span> ужинов</td>
                        <td><span>4</span> порции</td>
                    </tr>

                    <tr>
                        <td><a href="#">Вегетарианская корзина</a></td>
                        <td><span>3</span> ужинов</td>
                        <td><span>2</span> порции</td>
                    </tr>
                    </tbody>
                </table>

                <div class="baskets-menu__desc">
                    Оформите сегодня, и вы <span>получите заказ 22 или23 февраля</span>.<br/>
                    Заказы на это меню принимаются до <span>14:00 19 февраля</span>.<br/>
                    Заказав после, вы получите продукты меню следующей недели.
                </div>

                <a href="#" class="baskets-menu__details black-long-button">Подробнее про корзины</a>
            </div>
        </section>

        <section class="our-diff">
            <h2 class="our-diff__heading">чем мы отличаемся от других</h2>

            <ul class="our-diff__list">
                <li class="our-diff__item">
                    <img src="images/our-diff/our-diff-1.jpg" alt="Differences" class="our-diff__img">
                    <h3 class="our-diff__title">Рецепты от диетологов</h3>
                    <div class="our-diff__desc">Повара совместно с диетологами разрабатывают вкусные и полезные рецепты
                        для
                        здорового питания
                    </div>
                </li>
                <li class="our-diff__item">
                    <img src="images/our-diff/our-diff-2.jpg" alt="Differences" class="our-diff__img">
                    <h3 class="our-diff__title">Продукты от фермеров</h3>
                    <div class="our-diff__desc">Поставляются только свежие, сезонные и экологически чистые продукты от
                        наших
                        поставщиков
                    </div>
                </li>
                <li class="our-diff__item">
                    <img src="images/our-diff/our-diff-3.jpg" alt="Differences" class="our-diff__img">
                    <h3 class="our-diff__title">Машины-холодильники</h3>
                    <div class="our-diff__desc">Принцип постоянного холодильного цикла гарантирует свежие и качественные
                        продукты
                    </div>
                </li>
                <li class="our-diff__item">
                    <img src="images/our-diff/our-diff-4.jpg" alt="Differences" class="our-diff__img">
                    <h3 class="our-diff__title">Минилаботатория</h3>
                    <div class="our-diff__desc">Дополнительный контроль качества чтобы обеспечить пищевую безопасность
                        каждой партии
                    </div>
                </li>
            </ul>
        </section>

        <section class="free-delivery">
            <h2 class="free-delivery__title">доставка бесплатно</h2>
            <div class="free-delivery__subTitle">c воскресенья по понедельник, и до 12 км от МКАД</div>
            <div class="free-delivery__desc">Доставка на большие расстояния стоит 24 рубля за каждый километр от МКАД
            </div>
        </section>

        <section class="spec-review">
            <h2 class="spec-review__title">диетолог о «Давай готовить!»</h2>

            <div class="spec-review__main">
                <div class="spec-review__img" style="background-image: url('images/spec-review-1.jpg')"></div>

                <div class="spec-review__comment">
                    <div class="spec-review__inner">
                        <div class="spec-review__toggle">
                            <span class="spec-review__show">Подробнее</span>
                            <span class="spec-review__hide">Закрыть</span>
                        </div>

                        <div class="spec-review__text">
                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>

                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>

                            <p>Просчитав детально меню на следующую неделю (состав и калорийность блюд), я могу
                                подтвердить, что
                                блюда сбалансированы по белкам, жирам и углеводам, низкокалорийные, содержат необходимые
                                нашему
                                организму витамины и минералы. И самое главное — вкусные и разнообразные!</p>
                        </div>
                    </div>

                    <div class="spec-review__hois">
                        <div class="spec-review__name">Людмила Денисенко</div>

                        <div class="spec-review__profession">(диетолог, «Азбука стройности»)</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="clients-reviews">
            <h2 class="clients-reviews__title">отзывы клиентов</h2>

            <ul class="clients-reviews__list">
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img" style="background-image: url('images/reviews/reviews-1.jpg');">
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">Петшик Ольга Николаевна</div>
                        <div class="review-item__comment">Спасибо «Давай готовить»! Вы решили моюглавную проблему —
                            нафантазировать новыйужин! А с вами проблема решена! Порции огромные! Всё очень вкусно,
                            сбалансированно, нравится даже маленьким привередам. Спасибо за ваш труд!
                        </div>
                        <div class="review-item__date">13.02.2015</div>
                    </div>
                </li>
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img" style="background-image: url('images/reviews/reviews-2.jpg');">
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">Петшик Ольга Николаевна</div>
                        <div class="review-item__comment">Спасибо «Давай готовить»! Вы решили моюглавную проблему —
                            нафантазировать новыйужин! А с вами проблема решена! Порции огромные! Всё очень вкусно,
                            сбалансированно, нравится даже маленьким привередам. Спасибо за ваш труд!
                        </div>
                        <div class="review-item__date">13.02.2015</div>
                    </div>
                </li>
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img" style="background-image: url('images/reviews/reviews-3.jpg');">
                    </div>
                    <div class="review-item__main">
                        <div class="review-item__name">Петшик Ольга Николаевна</div>
                        <div class="review-item__comment">Спасибо «Давай готовить»! Вы решили моюглавную проблему —
                            нафантазировать новыйужин! А с вами проблема решена! Порции огромные! Всё очень вкусно,
                            сбалансированно, нравится даже маленьким привередам. Спасибо за ваш труд!
                        </div>
                        <div class="review-item__date">13.02.2015</div>
                    </div>
                </li>
                <li class="clients-reviews__item review-item">
                    <div class="review-item__img" style="background-image: url('images/reviews/reviews-4.jpg');"></div>
                    <div class="review-item__main">
                        <div class="review-item__name">Петшик Ольга Николаевна</div>
                        <div class="review-item__comment">Спасибо «Давай готовить»! Вы решили моюглавную проблему —
                            нафантазировать новыйужин! А с вами проблема решена! Порции огромные! Всё очень вкусно,
                            сбалансированно, нравится даже маленьким привередам. Спасибо за ваш труд!
                        </div>
                        <div class="review-item__date">13.02.2015</div>
                    </div>
                </li>
            </ul>
        </section>

        <section class="all-news-articles">
            <div class="all-news-articles__item" data-order="1">
                <h3 class="all-news-articles__title">Запуск вегетарианского меню</h3>
                <div class="all-news-articles__desc">У нас появилось вегетарианское меню, и теперь вы можете заказывать
                    вегетарианские корзины!
                </div>
                <a href="#" class="all-news-articles__link yellow-long-button">Посмотреть все новости</a>
            </div>

            <div class="all-news-articles__item" data-order="2">
                <h3 class="all-news-articles__title">Польза вегетарианской диеты</h3>
                <div class="all-news-articles__desc">Нужно не просто заменить мясо эквивалентным количеством
                    растительной
                    пищи, а пересмотреть всю свою диету
                </div>
                <a href="#" class="all-news-articles__link yellow-long-button">Посмотреть все статьи</a>
            </div>
        </section>

        <section class="main-subscribe">
            <h2 class="main-subscribe__title">будьте в курсе</h2>

            <div class="main-subscribe__desc">Получать раз в неделю информацию о специальных предложениях, акциях и
                изменениях меню
            </div>

            <form action="subscribe.php" class="main-subscribe__form subscribe-form">
                <input type="text" name="subscribe-mail" placeholder="Введите адрес электронной почты"
                       class="input-text-large">

                <input type="submit" name="subscribe-submit" value="Подписаться">
            </form>

        </section>
    </main>
@endsection