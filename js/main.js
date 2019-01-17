(function($) {

    "use strict";
    //改变页面的标题，
    var originTitile = document.title
    var timer = '';
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            document.title = "(つェ⊂)小僵尸蹦跶走了0_o";
            document.querySelector('.icon').href = 'https://littlombie.github.io/website/img/favicon2.ico';
            clearTimeout(timer);
        } else {
            document.querySelector('.icon').href = 'https://littlombie.github.io/website/img/favicon.ico';
            document.title = '(*´∇｀*)欢迎来到 0_o ' + originTitile;
            timer = setTimeout(function() {
                document.title = originTitile;
            }, 3000)
        }
    });

    $('.carousel').carousel({
        interval: false
    });

    // jQuery Stick menu
    $(".navbar").sticky({
        topSpacing: 0,
    });


    $('.nav').singlePageNav({
        currentClass: 'current'
    });


    //Click event to scroll to top
    $('#scroll-to-content').on('click', function(e) {
        e.preventDefault();

        $('html,body').animate({
            scrollTop: $('.first-section').offset().top - 70
        }, 600);
    });


    //Click event to scroll to top
    $('.go-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });


})(jQuery);

$(function() {
    var winH = document.documentElement.clientHeight;
    $('.carousel').css('height', winH + 'px');

    //获取作品
    $.getJSON("data/creation.json", function(data) { //请求作品数据
        // console.log(data);
        var $jsontip = $("#portfoliolist");
        var strHtml = ""; //存储数据的变量
        var fdm = document.createDocumentFragment();

        $.each(data, function(infoIndex, info) {
            // console.log(infoIndex,info);
            $.each(info, function(n, belongs) {
                // console.log(n, belongs);
                $.each(belongs.item, function(x, items) {
                    strHtml += "<div class='portfolio  col-md-3 col-sm-6 col-xs-12 creation-item  z9 " + belongs.belong + " ' data-cat='" + belongs.belong + "'> <div class='portfolio-wrapper'><a  href='" + items.url + "'  target='_blank'> <img src='img/production/" + items.img + "' alt=''/></a> <div class='label'> <div class='label-text'><a class='text-title' href='" + items.url + "'  target='_blank'>" + items.name + " </a></div> <div class='label-bg'></div> </div> </div> </div>"

                    // cdf.appendChild(strHtml);
                    // console.log(cdf);
                    //fdm.append(strHtml);
                    // $jsontip.html(strHtml + "<p class='loadList'></p>");
                    $jsontip.html(strHtml);
                })
            })
        })
    }).done(function() { //数据请求成功后 初始化样式
        console.log('加载成功');
        filterList.init();
        filterList.clickEffect();
        console.log($("#portfoliolist>div").size());
        $('#portfoliolist').on('click', 'div', function(e) {
            console.log(e.target.tagName);
        });

    }).fail(function() { //数据请求失败后
        console.log('作品加载失败');
    });

    var filterList = {

        init: function() {
            // MixItUp plugin
            $('#portfoliolist').mixitup({
                targetSelector: '.portfolio',
                filterSelector: '.filter',
                effects: ['fade'],
                easing: 'snap',
                // call the hover effect
                onMixEnd: filterList.hoverEffect()
            });

        },

        hoverEffect: function() {
            // Simple parallax effect
            $('#portfoliolist .portfolio').hover(function() {
                $(this).find('.label').stop().animate({ bottom: 0 }, 200, 'easeOutQuad');
                $(this).find('img').stop().animate({ top: -30 }, 500, 'easeOutQuad');
            }, function() {
                $(this).find('.label').stop().animate({ bottom: -40 }, 200, 'easeInQuad');
                $(this).find('img').stop().animate({ top: 0 }, 300, 'easeOutQuad');
            });
        },
        clickEffect: function() {
            var winW = document.documentElement.clientWidth,
                ua = navigator.userAgent.toLowerCase();
            if (winW < 961 && /iphone|ipod|android|windows phone|blackberry/.test(ua)) {

                $('#portfoliolist .portfolio').each(function() {
                    $('#portfoliolist .portfolio').click(function() {
                        window.location.href = $(this).find('.portfolio-wrapper').find('.label').find('.label-text').find('a.text-title').attr('href');
                    })
                })
            }
        }

    };
    // if ($('.loadList')) {
    //     setTimeout(function(){
    //         console.log('load end');
    //         // Run the show!
    //
    //     },2000)
    //
    // }

});

//  头部进度条
(function() {
    // progress
    var wh = document.documentElement.offsetHeight;
    var h = document.body.getBoundingClientRect().height;
    var dh = h - wh;
    window.addEventListener('scroll', function() {
        window.requestAnimationFrame(function() {
            var percent = Math.max(0, Math.min(1, window.pageYOffset / wh));
            // console.log(wh,h,dh,percent);
            document.querySelector('#progress').style.width = percent * 100 + '%';
        }, false);
    })
})();
//post表单
var oSubmitBtn = document.getElementById('submit'),
    bCouldPost = true;
oSubmitBtn.addEventListener("click", function() {
    var eName = $('#email').val(),
        fName = $('#name').val();
    if (eName == '') {
        alert('请输入正确的邮箱！');
        return false;
    } else if (fName == '') {
        alert('请输入名称');
        return false;
    } else if (bCouldPost) {
        bCouldPost = false;
        document.querySelector('form').setAttribute('action', 'https://littlombie.github.io/website/send-mail.php');
    }

}, false);