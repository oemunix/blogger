/*
 * Blogger Shortcode Plugin By Mohammad Mustafa Ahmedzai
 * All Shortcodes Here Created By Shivansh Verma Skv
 * Examples and documentation at: http://www.BloggerGuiders.blogspot.com
 * Copyright (c) 2008-2015 STCnetwork.org
 * Version: 1.0 (29-March-2015)
 * Dual licensed under the MIT and GPL licenses.
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 */

$(document).ready(function() {

// Global Variables
var bhf = ['gbutton', 'tfollow', 'ttweet', 'gbadge', 'embed', 'fbsend', 'fbfollow', 'fbshare', 'fblike', 'blockquote'];

// Can be added in Blog posts
$('.post').each(function() {
    var html = $(this).html();
    html = mbt(html);
    if (html != '') {
        $(this).html(html);
    }
});

// Can be added in Sidebar widgets
$('.widget-content').each(function() {
    var html = $(this).html();
    html = mbt(html);
    if (html != '') {
        $(this).html(html);
    }
});


// Can be added in Comments
$('.comment').each(function() {
    var html = $(this).html();
    html = mbt(html);
    if (html != '') {
        $(this).html(html);
    }
});

// Handler function & Shortcode Parser
function mbt(html) {
    var be = false;
    for (var i = 0; i < bhf.length; i++) {
        var aR = '[' + bhf[i];
        var bA = '<div class="shortcode sc-' + bhf[i] + '"';
        var bx = '[/' + bhf[i] + ']';
        var bb = '</div>';
        var aj = '/]';
        var bj = '></div>';
        var aT = 0;
        var bs = 0;
        var bF = 0;
        for (j = 0; j < 100; j++) {
            aT = html.indexOf(aR, aT);
            var aY = true;
            var k = '';
            if (aT != -1) {
                var bq = html.indexOf(bx, aT);
                var bH = html.indexOf(aj, aT);
                if ((bq != -1 && bH == -1) || (bq != -1 && bH != -1 && bq < bH)) {
                    k = html.substring(aT, bq + bx.length);
                    k = k.replace(bx, bb);
                    bs = bq;
                    bF = bx.length;
                } else if ((bq == -1 && bH != -1) || (bq != -1 && bH != -1 && bq > bH)) {
                    k = html.substring(aT, bH + aj.length);
                    k = k.replace(bx, bj);
                    bs = bH;
                    bF = aj.length;
                } else {
                    aY = false;
                }
            } else {
                break;
            }
            if (aY) {
                be = true;
                k = k.replace(aR, bA);
                k = k.replace(']', '>');
                html = html.substring(0, aT) + k + html.substring(bs + bF);
            } else {}
            aT++;
        }
    }
    if (be) {
        return html;
    }
    return '';
};

        // Google+ Badge Shortcode        
        $('.sc-gbadge').each(function () {
            var gbs = $(this).attr('user');
            var gbsw = $(this).attr('width');
            var gbsh = $(this).attr('height');
            var gbst = $(this).attr('theme');
            var gbsl = $(this).attr('tagline');
            var gbsr = $(this).attr('relation');
            var gbsty = $(this).attr('type');
            

            if (gbs == null || gbs == '') {
                gbs = '109987806005133887061';
            }

            if (gbsw == null || gbsw == '') {
                gbsw = '300';
            }

            if (gbsh == null || gbsh == '') {
                gbsh = 'auto';
            }

            if (gbst == null || gbst == '') {s
                gbst = 'dark';
            }

            if (gbsl == null || gbsl == '') {
                gbsl = 'true';
            }
            if (gbsr == null || gbsr == '') {
                gbsr = 'none';
            }          
            if (gbsty == null || gbsty == '') {
                gbsty = 'person';
            }
            var html = '<div class="g-' + gbsty + '" data-href="//plus.google.com/u/0/' + gbs + '" data-width="' + gbsw + '" data-height="' + gbsh + '" data-theme="' + gbst + '" data-rel="' + gbsr + '" data-showtagline="' + gbsl + '"></div>';
            $(this).replaceWith(html);
        });

        // Embed Shortcode   
        $('.sc-embed').each(function () {
            var ebu = $(this).attr('url');
            var ebt = $(this).attr('title');
            var ebk = $(this).attr('type');
            var ebp = $(this).attr('image');
            var ebd = $(this).attr('description');
            var ebl = $(this).attr('theme');

            if (ebu == null || ebu == '') {
                ebu = 'http://bloggerguiders.blogspot.com';
            }
            if (ebt == null || ebt == '') {
                ebt = 'Blogger Guiders';
            }
            if (ebk == null || ebk == '') {
                ebk = 'article-full';
            }
            if (ebp == null || ebp == '') {
                ebp = '';
            }
            if (ebd == null || ebd == '') {
                ebp = '';
            }
            if (ebl == null || ebl == '') {
                ebl = 'light';
            }
            var html = '<a class="embedly-card" href="' + ebu + '" data-card-type="' + ebk + '" data-card-image="' + ebp + '" data-card-theme="' + ebl + '">' + ebt + '</a>'
            $(this).replaceWith(html);
        });

        // Facebook Like Buttons
        $('.sc-fblike').each(function () {
            var flu = $(this).attr('url');
            var flt = $(this).attr('type');
            var flf = $(this).attr('faces');
            var fls = $(this).attr('share');

            if (flu == null || flu == '') {
                flu = 'http://www.facebook.com/bloggerguiders';
            }
            if (flt == null || flt == '') {
                flt = 'button';
            }
            if (flf == null || flf == '') {
                flf = 'false';
            }
            if (fls == null || fls == '') {
                fls = 'false';
            }
            var html = '<div class="fb-like" data-href="' + flu + '" data-layout="' + flt + '" data-action="like" data-show-faces="' + flf + '" data-share="' + fls + '"></div>'
            $(this).replaceWith(html);
        });

        // Facebook Follow Button
        $('.sc-fbfollow').each(function () {
            var ffu = $(this).attr('url');
            var ffw = $(this).attr('width');
            var ffh = $(this).attr('height');
            var fft = $(this).attr('theme');
            var ffty = $(this).attr('type');
            var fff = $(this).attr('faces');

            if (ffu == null || ffu == '') {
                flu = 'http://www.facebook.com/shivansh.verma.skv';
            }
            if (ffw == null || ffw == '') {
                flw = 'auto';
            }
            if (ffh == null || ffh == '') {
                ffh = 'auto';
            }
            if (fft ==  null || fft == '') {
                fft = 'light';
            }
            if (ffty == null || ffty == '') {
                ffty = 'button';
            }
            if (fff == null || fff == '') {
                fff = 'false';
            }

            var html = '<div class="fb-follow" data-href="' + ffu + '" data-width="' + ffw + '" data-height="' + ffh + '" data-colorscheme="' + fft + '" data-layout="' + ffty + '" data-show-faces="' + fff + '"></div>'
            $(this).replaceWith(html);
        });

        // Facebook Share Button
       $('.sc-fbshare').each(function () {
            var fsu = $(this).attr('url');
            var fst = $(this).attr('type');

            if (fsu == null || fsu == '') {
                fsu = 'http://www.facebook.com/BloggerGuiders';
            }
            if (fst == null || fst == '') {
                fst = 'button';
            }

            var html = '<div class="fb-share-button" data-href="' + fsu + '" data-layout="' + fst + '"></div>'
            $(this).replaceWith(html);
        });

        // Facebook Send Button
        $('.sc-fbsend').each(function () {
            var sbu = $(this).attr('url');

            if (sbu == null || sbu == '') {
                sbu = 'http://www.facebook.com/bloggerguiders';
            }

            var html = '<div class="fb-send" data-href="' + sbu + '"></div>'
            $(this).replaceWith(html);
        });

        // Tweet Button Shortcode
        $('.sc-ttweet').each(function () {
            var tbu = $(this).attr('url');
            var tbct = $(this).attr('counttype');
            var tbt = $(this).attr('text');
            var tbv = $(this).attr('via');
            var tbs = $(this).attr('size');

            if (tbu == null || tbu == '') {
                tbu = 'http://bloggerguiders.blogspot.com';
            }
            if (tbct == null || tbct == '') {
                tbct = 'none';
            }
            if (tbt == null || tbt == '') {
                tbt = 'Blogger Guiders is a blogger web portal where blogger tips and tricks and posted daily.';
            }
            if (tbv == null || tbv == '') {
                tbv = 'Blogger Guiders';
            }
            if (tbs == null || tbs == '') {
                tbs = '';
            }

            var html = '<a class="twitter-share-button" data-count="' + tbct + '" data-size="' + tbs + '" data related="" data-text="' + tbt + '" data-url="' + tbu + '" data-via="' + tbv + '" href="http://twitter.com/share">Tweet</a>'
            $(this).replaceWith(html);
        });

        // Twitter Follow Button Shortcode
        $('.sc-tfollow').each(function () {
            var tfu = $(this).attr('username');
            var tfs = $(this).attr('size');
            var tfc = $(this).attr('count');
            var tfn = $(this).attr('screenname');

            if (tfu == null || tfu == '') {
                tfu = 'http://www.twitter.com/bloggerguiders';
            }
            if (tfs == null || tfs == '') {
                tfs = '';
            }
            if (tfc == null || tfc == '') {
                tfc = 'true';
            }
            if (tfn == null || tfn == '') {
                tfn = 'true';
            }

            var html = '<a class="twitter-follow-button" href="http://twitter.com/' + tfu + '" data-size="' + tfs + '" data-show-count="' + tfc + '" data-show-screen-name="' + tfn + '">Follow @' + tfu + '</a>'
            $(this).replaceWith(html);
        });

        // Google+ +1 Button Shortcode
        $('.sc-gbutton').each(function () {
            var gbu = $(this).attr('url');
            var gbs = $(this).attr('size');
            var gbt = $(this).attr('type');
            var gbw = $(this).attr('width');
            var gba = $(this).attr('align');
            var gbe = $(this).attr('expandto');

            if (gbu == null || gbu == '') {
                gbu = 'http://bloggerguiders.blogspot.com';
            }
            if (gbs == null || gbs == '') {
                gbs = 'medium';
            }
            if (gbt == null || gbt == '') {
                gbt = 'none';
            }
            if (gbw == null || gbw == '') {
                gbw = '50';
            }
            if (gba == null || gba == '') {
                gba = 'left';
            }
            if (gbe == null || gbe == '') {
                gbe = 'right';
            }

            var html = '<div class="g-plusone" data-href="' + gbu + '" data-size="' + gbs + '" data-annotation="' + gbt + '" data-width="' + gbw + '" data-align="' + gba + '" expandTo="' + gbe + '"></div>'
            $(this).replaceWith(html);
        });

        // HTML, CSS, JS Blockquote Shortcode
        $('.sc-blockquote').each(function () {
            var hcjt = $(this).attr('type');
            var hcjc = $(this).attr('code');

            if (hcjt == null || hcjt == '')
                hcjt = 'html';

            var html = '<div class="' + hcjt + '">' + hcjc + '</div>'
            $(this).replaceWith(html);
        });
});
