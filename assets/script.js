(function () {
	'use strict';

	var ALANLAR = ['tam_isim', 'sehir', 'biyografi', 'kullanici_adi', 'sifre', 'eposta', 'adres', 'meslek',
		'dogum_tarihi', 'yas', 'telefon_numarasi', 'ilgi_alani', 'web_adresi'];
	var $ = function (id) { return document.getElementById(id); };
	var durum = $('durum');

	function acikMi(el) { return getComputedStyle(el).display !== 'none'; }
	function goster(el, acik) { el.style.display = acik ? 'block' : 'none'; }

	function modalAc(id, digerId) {
		return function (e) {
			e.preventDefault();
			goster($(digerId), false);
			goster($(id), !acikMi($(id)));
		};
	}

	$('hakkinda-gg').addEventListener('click', modalAc('hakkinda', 'api'));
	$('api-gg').addEventListener('click', modalAc('api', 'hakkinda'));

	document.querySelectorAll('.modal-tamam').forEach(function (b) {
		b.addEventListener('click', function () { goster(b.closest('.modal'), false); });
	});

	document.querySelectorAll('.pdit > input').forEach(function (input) {
		input.addEventListener('dblclick', function (e) {
			input.select();
			e.preventDefault();
		});
	});

	function doldur(p) {
		ALANLAR.forEach(function (alan) { $(alan).value = p[alan]; });
		$('profil_resmi').src = p.profil_resmi + '?tr=h-150,w-150,q-80';
		$('profil_resmi').alt = p.tam_isim + ' avatarı';
		$('profil_resmi_url').value = p.profil_resmi;
	}

	document.querySelectorAll('header button').forEach(function (btn) {
		btn.addEventListener('click', function () {
			document.querySelectorAll('header button').forEach(function (b) {
				b.classList.toggle('aktif', b === btn);
				b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
			});

			fetch('api.php?cinsiyet=' + encodeURIComponent(btn.getAttribute('data-cinsiyet')), { cache: 'no-store' })
				.then(function (yanit) {
					return yanit.json().then(function (veri) {
						if (!yanit.ok) throw new Error(veri.hata || 'Profil oluşturulamadı.');
						return veri;
					});
				})
				.then(function (veri) {
					doldur(veri);
					durum.textContent = '';
				})
				.catch(function (hata) {
					durum.textContent = hata instanceof SyntaxError || hata instanceof TypeError
						? 'Bağlantı hatası, lütfen tekrar deneyin.'
						: hata.message;
				});
		});
	});

	$('daha-ak').addEventListener('keydown', function (e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			this.click();
		}
	});

	$('daha-ak').addEventListener('click', function () {
		var ac = !acikMi($('daha'));
		var btn = $('daha-ak');
		goster($('daha'), ac);
		btn.classList.toggle('up', ac);
		btn.classList.toggle('down', !ac);
		btn.setAttribute('aria-expanded', ac ? 'true' : 'false');
		btn.textContent = ac ? 'DETAYLARI GİZLE' : 'DAHA FAZLA DETAY...';
	});
})();
