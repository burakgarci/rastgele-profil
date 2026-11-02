/* Rastgele Profil arayüzü. Bağımlılık yok; tüm veriler api.php'den gelir. */
(function () {
  'use strict';

  var $ = function (s, k) { return (k || document).querySelector(s); };
  var $$ = function (s, k) { return Array.prototype.slice.call((k || document).querySelectorAll(s)); };
  var sayfa = document.body.getAttribute('data-sayfa');

  function kopyala(metin) {
    if (navigator.clipboard && window.isSecureContext) return navigator.clipboard.writeText(metin);
    return new Promise(function (tamam, hata) {
      var t = document.createElement('textarea');
      t.value = metin;
      t.setAttribute('readonly', '');
      t.style.position = 'fixed';
      t.style.opacity = '0';
      document.body.appendChild(t);
      t.select();
      try { document.execCommand('copy') ? tamam() : hata(); } catch (e) { hata(e); }
      document.body.removeChild(t);
    });
  }

  function geriBildirim(dugme, metin, basarili) {
    if (!dugme._ilk) dugme._ilk = dugme.textContent;
    dugme.textContent = metin;
    dugme.classList.toggle('ok', basarili);
    clearTimeout(dugme._t);
    dugme._t = setTimeout(function () {
      dugme.textContent = dugme._ilk;
      dugme.classList.remove('ok');
    }, 1400);
  }

  function kopyalaDugme(dugme, metinVer, tamamMetni) {
    dugme.addEventListener('click', function () {
      kopyala(metinVer()).then(
        function () { geriBildirim(dugme, tamamMetni || 'Kopyalandı', true); },
        function () { geriBildirim(dugme, 'Kopyalanamadı', false); }
      );
    });
  }

  function getir(adres) {
    var bas = Date.now();
    return fetch(adres, { cache: 'no-store' }).then(function (y) {
      return y.text().then(function (govde) {
        var veri = null;
        try { veri = JSON.parse(govde); } catch (e) { /* JSON değil */ }
        return { durum: y.status, ok: y.ok, veri: veri, govde: govde, sure: Date.now() - bas, kalan: y.headers.get('X-RateLimit-Remaining'), limit: y.headers.get('X-RateLimit-Limit') };
      });
    });
  }

  function hataMesaji(h) {
    if (h instanceof TypeError) return 'Bağlantı hatası, lütfen tekrar deneyin.';
    return (h && h.message) || 'Profil oluşturulamadı.';
  }

  /* Resim inene kadar parıltı gösterir; inince yumuşakça belirir, inmezse baş harfler görünür. */
  function avatarYerlestir(el, p) {
    el.classList.toggle('kadin', p.cinsiyet === 'kadın');
    el.classList.add('bekliyor');
    el.textContent = '';
    var img = new Image();
    img.alt = '';
    var guncel = function () { return el.getAttribute('data-src') === img.src; }; // daha yeni bir resim istenmiş olabilir
    img.onload = function () {
      if (!guncel()) return;
      el.textContent = '';
      el.appendChild(img);
      el.classList.remove('bekliyor');
      requestAnimationFrame(function () { img.classList.add('gorunur'); });
    };
    img.onerror = function () {
      if (!guncel()) return;
      el.classList.remove('bekliyor');
      el.textContent = (p.isim.charAt(0) + p.soyisim.charAt(0)).toLocaleUpperCase('tr');
    };
    img.src = p.profil_resmi;
    el.setAttribute('data-src', img.src);
  }

  /* Satır kopyalama: her sayfada .satir içindeki .kopya düğmesi kendi değerini kopyalar. */
  $$('.satir').forEach(function (s) {
    var d = $('.d', s);
    var b = $('.kopya', s);
    if (!d || !b) return;
    kopyalaDugme(b, function () { return d.textContent; });
    // Değerin kendisine tıklamak da kopyalar; metin seçiliyorsa seçime dokunmaz.
    d.addEventListener('click', function () {
      var secim = window.getSelection ? String(window.getSelection()) : '';
      if (!secim) b.click();
    });
  });

  /* Cinsiyet sekmeleri */
  function cinsiyetSekmeleri(kok, degisince) {
    var dugmeler = $$('[data-cinsiyet]', kok);
    dugmeler.forEach(function (b) {
      b.addEventListener('click', function () {
        var c = b.getAttribute('data-cinsiyet');
        // Aynı değerdeki tüm düğmeler birlikte seçilir (masaüstü kartı ve mobil alt çubuk).
        dugmeler.forEach(function (x) { x.setAttribute('aria-pressed', x.getAttribute('data-cinsiyet') === c ? 'true' : 'false'); });
        degisince(c);
      });
    });
  }

  /* ---------- Ana sayfa ---------- */
  if (sayfa === 'ana') {
    var kok = $('#profil');
    var durum = $('#durum');
    var simdiki = null;
    var cinsiyet = '';
    var bekliyor = false;

    var doldur = function (p) {
      simdiki = p;
      $$('[data-alan]').forEach(function (el) { el.textContent = p[el.getAttribute('data-alan')]; });
      fotoGoster(p);
    };

    var fotoGoster = function (p) {
      avatarYerlestir($('#avatar'), p);
      var indir = $('#foto-indir');
      indir.href = p.profil_resmi;
      indir.setAttribute('download', p.profil_resmi.split('/').slice(-2).join('-'));
    };

    var fotoYenile = function () {
      if (!simdiki || bekliyor) return;
      var dugme = $('#foto-yenile');
      dugme.disabled = true;
      getir('api.php?cinsiyet=' + (simdiki.cinsiyet === 'kadın' ? 'kadin' : 'erkek'))
        .then(function (s) {
          if (!s.ok || !s.veri || !s.veri.profil_resmi) throw new Error(s.veri && s.veri.hata ? s.veri.hata : 'Resim alınamadı.');
          simdiki.profil_resmi = s.veri.profil_resmi;
          $('[data-alan="profil_resmi"]').textContent = simdiki.profil_resmi;
          fotoGoster(simdiki);
          durum.textContent = '';
        })
        .catch(function (h) { durum.textContent = hataMesaji(h); })
        .then(function () { dugme.disabled = false; });
    };

    var yeni = function () {
      if (bekliyor) return;
      bekliyor = true;
      kok.classList.add('yukleniyor');
      $$('[data-yeni]').forEach(function (b) { b.disabled = true; });
      getir('api.php' + (cinsiyet ? '?cinsiyet=' + cinsiyet : ''))
        .then(function (s) {
          if (!s.ok || !s.veri || s.veri.hata) throw new Error(s.veri && s.veri.hata ? s.veri.hata : 'Profil oluşturulamadı.');
          doldur(s.veri);
          durum.textContent = '';
        })
        .catch(function (h) { durum.textContent = hataMesaji(h); })
        .then(function () {
          bekliyor = false;
          kok.classList.remove('yukleniyor', 'ilk-yukleme');
          $$('[data-yeni]').forEach(function (b) { b.disabled = false; });
        });
    };

    cinsiyetSekmeleri(document, function (c) {
      cinsiyet = c;
      yeni();
    });
    $$('[data-yeni]').forEach(function (b) { b.addEventListener('click', yeni); });
    $('#foto-yenile').addEventListener('click', fotoYenile);
    kopyalaDugme($('#json-kopyala'), function () { return JSON.stringify(simdiki, null, 4); }, 'JSON kopyalandı');

    document.addEventListener('keydown', function (e) {
      if ((e.key !== ' ' && e.code !== 'Space') || e.repeat || e.ctrlKey || e.metaKey || e.altKey) return;
      if (e.target.closest && e.target.closest('button, a, input, textarea, select, [contenteditable]')) return;
      e.preventDefault();
      yeni();
    });

    yeni();
  }

  /* ---------- API sayfası ---------- */
  if (sayfa === 'api') {
    var form = $('#istek');
    var adet = $('#adet');
    var alan = $('#alan');
    var seed = $('#seed');
    var cikti = $('#cikti');
    var profilGorunum = $('#profil-gorunum');
    var apiCinsiyet = '';
    var gorunum = 'json';
    var son = null;

    var esc = function (s) {
      return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    };

    var boya = function (json) {
      var cikis = '';
      var onceki = 0;
      var re = /"(?:\\.|[^"\\])*"(\s*:)?|[{}\[\],]/g;
      var m;
      while ((m = re.exec(json))) {
        cikis += esc(json.slice(onceki, m.index));
        if (m[0].charAt(0) === '"') {
          cikis += m[1]
            ? '<span class="j-k">' + esc(m[0].slice(0, -m[1].length)) + '</span><span class="j-p">' + esc(m[1]) + '</span>'
            : esc(m[0]);
        } else {
          cikis += '<span class="j-p">' + m[0] + '</span>';
        }
        onceki = re.lastIndex;
      }
      return cikis + esc(json.slice(onceki));
    };

    var adres = function () {
      var q = [];
      if (apiCinsiyet) q.push('cinsiyet=' + apiCinsiyet);
      [['adet', adet], ['eposta_alan_adi', alan], ['seed', seed]].forEach(function (p) {
        var v = p[1].value.trim();
        if (v) q.push(p[0] + '=' + encodeURIComponent(v));
      });
      return 'api.php' + (q.length ? '?' + q.join('&') : '');
    };
    var tamAdres = function () { return new URL(adres(), location.href).href; };
    var adresYaz = function () { $('#istek-url').textContent = '/' + adres(); };

    var metin = function () {
      if (!son) return '';
      if (gorunum === 'curl') return 'curl "' + tamAdres() + '"';
      return son.veri ? JSON.stringify(son.veri, null, 4) : son.govde;
    };

    var ciz = function () {
      var profil = gorunum === 'profil' && son && son.veri && !son.veri.hata;
      cikti.hidden = profil;
      profilGorunum.hidden = !profil;
      if (profil) {
        var dizi = Array.isArray(son.veri);
        var p = dizi ? son.veri[0] : son.veri;
        var not = dizi && son.veri.length > 1 ? '<p class="not" style="padding: 10px 0">' + son.veri.length + ' profilden ilki gösteriliyor. Tamamı JSON görünümünde.</p>' : '';
        profilGorunum.innerHTML = p ? not + Object.keys(p).map(function (k) {
          return '<div class="satir"><span class="e">' + esc(k) + '</span><span class="d">' + esc(p[k]) + '</span><span></span></div>';
        }).join('') : '<p class="not">Boş yanıt.</p>';
      } else if (gorunum === 'curl') {
        cikti.textContent = metin();
      } else {
        cikti.innerHTML = son ? (son.veri ? boya(metin()) : esc(son.govde)) : '';
      }
    };

    var DURUMLAR = { 200: '200 OK', 400: '400 Geçersiz istek', 405: '405 İzin verilmeyen metot', 429: '429 Çok fazla istek', 500: '500 Sunucu hatası' };

    var gonder = function () {
      adresYaz();
      getir(adres()).then(function (s) {
        son = s;
        var kd = $('#kod-durum');
        kd.textContent = DURUMLAR[s.durum] || String(s.durum);
        kd.className = s.ok ? 'iyi' : 'kotu';
        $('#kod-sure').textContent = s.sure + ' ms';
        $('#kalan').textContent = s.kalan !== null ? 'Kalan istek: ' + s.kalan + ', saniyede ' + s.limit + ' yenilenir.' : 'İstek sınırı yok.';
        ciz();
      }).catch(function (h) {
        son = null;
        $('#kod-durum').textContent = hataMesaji(h);
        $('#kod-durum').className = 'kotu';
        ciz();
      });
    };

    cinsiyetSekmeleri(form, function (c) {
      apiCinsiyet = c;
      adresYaz();
    });
    [adet, alan, seed].forEach(function (i) { i.addEventListener('input', adresYaz); });
    $('#adet-artir').addEventListener('click', function () {
      adet.value = String(Math.min((parseInt(adet.value, 10) || 0) + 1, 250));
      adresYaz();
    });
    $('#adet-azalt').addEventListener('click', function () {
      var n = (parseInt(adet.value, 10) || 1) - 1;
      adet.value = n > 0 ? String(n) : '';
      adresYaz();
    });
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      gonder();
    });

    $$('[data-gorunum]').forEach(function (b) {
      b.addEventListener('click', function () {
        gorunum = b.getAttribute('data-gorunum');
        $$('[data-gorunum]').forEach(function (x) { x.setAttribute('aria-selected', x === b ? 'true' : 'false'); });
        ciz();
      });
    });

    kopyalaDugme($('#url-kopyala'), tamAdres);
    kopyalaDugme($('#cikti-kopyala'), metin);
    $('#cikti-indir').addEventListener('click', function () {
      if (!son) return;
      var a = document.createElement('a');
      a.href = URL.createObjectURL(new Blob([metin()], { type: gorunum === 'curl' ? 'text/plain' : 'application/json' }));
      a.download = gorunum === 'curl' ? 'istek.sh' : 'profil.json';
      document.body.appendChild(a);
      a.click();
      setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 0);
    });

    gonder();
  }
})();
