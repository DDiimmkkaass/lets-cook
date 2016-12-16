
/* ----- ARTICLES LIST ----- */

function articlesList($rootElement, loadedPage) {
    let $articles = $rootElement,
        $searchForm = $articles.find('.articles-list-search__form'),
        $filterList = $articles.find('.articles-list-filter__categories'),
        $tagsFilterList = $articles.find('.articles-list-filter__tags'),
        $tagsList = $articles.find('.articles-list-filter__list-tags'),
        $articlesPanel = $articles.find('.articles-list-filter__panel'),
        $articlesList = $articles.find('.articles-list-main__list'),
        $articlesLoader = $articles.find('.articles-list-main__loader'),
        $pagination = $articles.find('.articles-list__pagination'),
        $paginationAll = $pagination.find('.articles-list-pag__all'),
        $paginationPrev = $pagination.find('.articles-list-pag__item[data-pagination="prev"]'),
        $paginationNext = $pagination.find('.articles-list-pag__item[data-pagination="next"]'),
        articlesObj = {},
        page = loadedPage,
        currTag = 0,
        currPage = 1,
        textSearch = '';

    // FUNCTIONS
    function articlesAjax(tag = 0, pageNum = 0, searchText = '') {
        let ajaxUrl = page + '/' + tag + '?page=' + pageNum + '&search_text=' + searchText;

        console.log(ajaxUrl);

        return $.ajax({
            type: 'GET',
            url: ajaxUrl,
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },

            beforeSend: function() {
                $articlesLoader.attr('data-active', '');
            },

            success: function(data) {
                if (data.status === 'error') {
                    let errorMessage = data.message;
                }

                console.log('articles ajax success');
            },

            error: function() {
                console.log('articles ajax error');
            }
        });
    }

    function articlesOutput() {
        $articlesList.empty();

        $.each(articlesObj[page], function(index, item) {
            let currentArticle = '';

            if (page == 'comments') {
                currentArticle = '<li class="clients-reviews__item review-item">'
                    + '<div class="review-item__img" ';

                if (item.image) {
                    currentArticle += 'style="background-image: url('+item.image+');"';
                }

                currentArticle += '></div>'
                    + '<div class="review-item__main">'
                    + '<div class="review-item__name">' + item.name + '</div>'
                    + '<div class="review-item__comment">' + item.comment + '</div>'
                    + '<div class="review-item__date">' + item.date + '</div>'
                    + '</div></li>';
            } else {
                currentArticle = '<li class="articles-list-main__item article-item">'
                    + '<div class="article-item__main">'
                    + '<a href="' + item.href + '" class="article-item__img" style="background-image: url(' + '\'' + item.image + '\'' + ');"></a>'
                    + '<div class="article-item__content">'
                    + '<a href="' + item.href + '" class="article-item__title">' + item.name + '</a>'
                    + '<ul class="article-item__tag-list">';

                $.each(item.tags, function(index, item) {
                    currentArticle += '<li class="article-item__tag-item" data-tag="' + item.id + '">' + item.name + '</li>';
                });

                currentArticle += '</ul>'
                    + '<div class="article-item__description"><span>Ингридиенты: </span>' + item.description + '</div>'
                    + '</div>'
                    + '</div>';
            }

            if (page === 'recipes') {
                currentArticle += '<div class="article-item__additional">'
                    + '<div class="article-item__rating">'
                    + '<div class="article-item__rating-title">рейтинг:</div>'
                    + '<div class="article-item__rating-stars">';

                switch (item.rating) {
                    case 1:
                        currentArticle += '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 2:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 3:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;

                    case 4:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span></span>';

                        break;

                    case 5:
                        currentArticle += '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<span data-active></span>'
                            + '<<span data-active></span>';

                        break;

                    default:
                        currentArticle += '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>'
                            + '<span></span>';

                        break;
                }

                currentArticle +='</div>'
                    + '</div>'
                    + '<div class="article-item__time">'
                    + '<div class="article-item__time-title">время:</div>'
                    + '<div class="article-item__time-value">' + item.cooking_time + ' минут</div>'
                    + '</div>'
                    + '</div>'
                    + '</li>';
            }

            $articlesList.append($(currentArticle));
        });

        $paginationNext.text(articlesObj.next_count_label);
    }

    $filterList.on('click', '.articles-list-filter__subItem.category', function() {
        let $that = $(this);
        let module = $that.closest('.articles-list-filter__list').data('module');

        $filterList.find('.articles-list-filter__subItem').removeAttr('data-active');
        $that.attr('data-active', '');

        $.ajax({
            type: 'GET',
            url: '/tags/' + $that.data('cat') + '/' + module,
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                console.log('categories ajax success');

                if (data.status == 'success') {
                    if ($tagsList.hasClass('h-hidden')) {
                        $tagsList.removeClass('h-hidden');
                    }

                    $('.articles-list-filter__tags .articles-list-filter__subList').html(data.html)
                } else {
                    popUp(lang_error, response.message)
                }
            },
            error: function() {
                console.log('categories ajax error');
            }
        });
    });

    $filterList.on('click', '.articles-list-filter__subItem.all', function() {
        let $that = $(this);

        $.when(articlesAjax(currTag = 0, currPage = 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                articlesOutput();

                $searchForm.find('input[name="search-text"]').val('');

                $filterList.find('.articles-list-filter__subItem').removeAttr('data-active');
                $that.attr('data-active', '');

                $tagsList.addClass('h-hidden');
                $tagsFilterList.find('.articles-list-filter__subList').html('');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationAll.removeAttr('data-active');
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationAll.attr('data-active', '');
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationAll.on('click', function() {
        $.when(articlesAjax(currTag, currPage = 0, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'fast');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                $paginationAll.removeAttr('data-active');
                $paginationPrev.removeAttr('data-active');
                $paginationNext.removeAttr('data-active');
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationPrev.on('click', function() {
        $.when(articlesAjax(currTag, currPage -= 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'slow');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $paginationNext.on('click', function() {
        $.when(articlesAjax(currTag, currPage += 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'slow');

                articlesOutput();

                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $(document).on('click', '.article-item__tag-item', function() {
        let $that = $(this);

        currTag = $that.attr('data-tag');

        $tagsFilterList.find('.article-item__tag-item').removeAttr('data-active');
        $articlesList.find('.article-item__tag-item').removeAttr('data-active');
        $that.attr('data-active', '');

        $.when(articlesAjax(currTag, currPage = 1, textSearch = ''))
            .done(function(ra) {
                articlesObj = $.extend(true, {}, ra);

                $('html,body').animate({
                    scrollTop: $articlesPanel.offset().top
                }, 'fast');

                articlesOutput();

                currPage = 1;

                $articlesList.find('.article-item__tag-item').removeAttr('data-active');
                $articlesList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');
                $tagsFilterList.find('.article-item__tag-item[data-tag="' + currTag + '"]').attr('data-active', '');

                if ((currPage - 1) < 1) {
                    $paginationPrev.removeAttr('data-active');
                } else {
                    $paginationPrev.attr('data-active', '');
                }

                if (articlesObj.next_count < 1) {
                    $paginationAll.removeAttr('data-active');
                    $paginationNext.removeAttr('data-active');
                } else {
                    $paginationAll.attr('data-active', '');
                    $paginationNext.attr('data-active', '');
                }
            })
            .always(function() {
                setTimeout(function() {
                    $articlesLoader.removeAttr('data-active');
                }, 300);
            });
    });

    $searchForm.on('submit', function(e) {
        e.preventDefault();

        let $input = $(this).find('input[name="search-text"]');

        if ($input.val()) {
            $.when(articlesAjax(currTag = 0, currPage = 1, textSearch = $input.val()))
                .done(function(ra) {
                    articlesObj = $.extend(true, {}, ra);

                    articlesOutput();

                    $filterList.find('.articles-list-filter__subItem').removeAttr('data-active');
                    $filterList.find('.articles-list-filter__subItem[data-cat="0"]').attr('data-active', '');

                    //скидать категорію і теги

                    if ((currPage - 1) < 1) {
                        $paginationPrev.removeAttr('data-active');
                    } else {
                        $paginationPrev.attr('data-active', '');
                    }

                    if (articlesObj.next_count < 1) {
                        $paginationAll.removeAttr('data-active');
                        $paginationNext.removeAttr('data-active');
                    } else {
                        $paginationAll.attr('data-active', '');
                        $paginationNext.attr('data-active', '');
                    }
                })
                .always(function() {
                    setTimeout(function() {
                        $articlesLoader.removeAttr('data-active');
                    }, 300);
                });
        } else {
            $input.focus();
        }
    });
}

/* ----- end ARTICLES LIST ----- */