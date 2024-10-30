jQuery(document).ready(function ($) {
  var CFI_SITE = 'https://cryptofavorite.com';
  var CFI_TICKER_API_URL = 'https://api.cryptofavorite.com/marketsSearch/';
  var mouseOverText = 'Prices by Cryptofavorite.com';

  function cfi_init(selector) {
    var tickers = $(selector);

    if (tickers.length > 0) {
      tickers.each(function () {
        var coin = $(this).data('cfi-coin');
        var style = $(this).data('cfi-style');
        var options = {
          hideLink: $(this).data('cfi-hide-link'),
          graph: $(this).data('cfi-graph'),
          volume: $(this).data('cfi-volume'),
          marketCap: $(this).data('cfi-market-cap')
        }
        getCoinData($(this), coin, style, options);
      });
    }
  }

  // run ticker style widget
  cfi_init('.cfi-coin-ticker');
  // run ticker default style text
  cfi_init('.cfi-coin-ticker-text');

  function getCoinData(elem, coinName, style, options) {
    $.ajax({
      url: CFI_TICKER_API_URL + coinName
    })
    .done(function (response) {
      if (response.success && response.markets.length > 0) {
        // output widget style
        if (style === 'widget') {
          outputTickerWidget(elem, response.markets[0], options);
        } else {
          // output default style text
          outputTickerText(elem, response.markets[0], options);
        }
      } else {
        outputTickerError(elem, coinName);
      }
    })
    .fail(function () {
      outputTickerError(elem, coinName);
    });
  }

  function outputTickerWidget(elem, market, options) {
    var template = $('<div class="cfi-ticker-inner"></div>');
    var tmplTop = $('<div class="cfi-ticker-top"></div>');

    // icon
    if (market.data && market.data.icon) {
      var icon = $('<img>').addClass('cfi-ticker-icon');
      icon.attr('src', CFI_SITE + market.data.icon);
      tmplTop.append(icon);
    }

    var topInfo = $('<div class="cfi-ticker-top-info"></div>');
    // name
    var name = $('<strong class="cfi-ticker-name"></strong>').text(market.name + ' (' + market.symbol + ')');
    topInfo.append(name);

    // price
    var price = $('<span class="cfi-ticker-price"></span>').text('$ ' + market.price_usd.toLocaleString());
    topInfo.append(price);

    // percent change
    var percent = $('<span class="cfi-ticker-change"></span>').text(market.percent_change_24h + '%');
    if (market.percent_change_24h < 0) {
      percent.addClass('cfi-ticker-change-red');
    }
    topInfo.append(percent);
    tmplTop.append(topInfo);

    // output top block
    template.append(tmplTop);

    // additional info
    var tmplBody = $('<div class="cfi-ticker-body"></div>');

    if (options.volume == 'on' || options.marketCap == 'on') {
      var additionalInfo = $('<div class="cfi-ticker-additional-info"></div>');
    }

    // volume
    if (options.volume == 'on' && market['24h_volume_usd']) {
      var volume = $('<div class="cfi-ticker-title"></div>');
      volume.text('Volume (24h): $' + market['24h_volume_usd'].toLocaleString());
      additionalInfo.append(volume);
    }

    // market cap
    if (options.marketCap == 'on' && market.market_cap_usd) {
      var marketCap = $('<div class="cfi-ticker-title"></div>');
      marketCap.text('Market Cap: $' + market.market_cap_usd.toLocaleString());
      additionalInfo.append(marketCap);
    }

    // output additional info
    if (options.volume == 'on' || options.marketCap == 'on') {
      tmplBody.append(additionalInfo);
    }

    // graph
    if (options.graph === 'on') {
      var graph = $('<img>').addClass('cfi-ticker-graph');
      graph.attr('src', CFI_SITE + '/img/' + market.id + '.png');
      //
      // var graphInner = $('<div></div>');
      // graphInner.append('<div class="cfi-ticker-title">Price Graph (7d): </div>');
      // graphInner.append(graph);
      // output graph
      if (options.volume !== 'on' || options.marketCap !== 'on') {
        graph.addClass('cfi-ticker-graph-center');
      }
      tmplBody.append(graph);
    }

    // output body
    template.append(tmplBody);

    // output widget
    elem.html(template);

    // create link
    var info = $('<div class="cfi-coin-ticker-info"></div>');
    if (options.hideLink === 'on') {
      var message = $('<span></span>');
    } else {
      var message = $('<a></a>');
      message.attr('href', CFI_SITE + '/#/market/' + market.id);
      message.attr('target', '_blank');
    }

    message.text(mouseOverText);

    // output info
    info.append(message);
    elem.parent().append(info);
  }

  function outputTickerText(elem, market, options) {
    if (options.hideLink === 'on') {
      var inner = $('<span></span>');
    } else {
      var inner = $('<a></a>');
      inner.attr('href', CFI_SITE + '/#/market/' + market.id);
      inner.attr('target', '_blank');
    }
    inner.addClass('cfi-ticker-text-inner');
    inner.text(market.name + ' (Current price: ' + market.price_usd + ' USD)');
    inner.attr('title', mouseOverText);

    // output
    elem.html(inner);
  }

  function outputTickerError(elem, coinName) {
    var message = '<a href="'+ CFI_SITE + '" target="_blank">Error get data for '+ coinName +'. Detail page on cryptofavorite.com</a>';
    elem.html(message);
  }
});
