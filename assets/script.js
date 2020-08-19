$("#hakkinda-gg").click(function(){
	$("#api").hide();
	$("#hakkinda").fadeToggle(100);
});

$("#api-gg").click(function(){
	$("#hakkinda").hide();
	$("#api").fadeToggle(100);
});

$("header button").click(function(){
	$('button.aktif').not(this).removeClass('aktif');
	$(this).addClass("aktif");

	var cinsiyet = $(this).attr("data-cinsiyet").replace("i","ı");
	var json = new XMLHttpRequest();

	json.onreadystatechange = function() {
		if (json.readyState == 4 && json.status == 200) {
			var profil = JSON.parse(json.responseText);
			$("#profil_resmi").attr("src", profil.profil_resmi+"?tr=h-150,w-150,q-80");
			$("#tam_isim").val(profil.tam_isim);
			$("#sehir").val(profil.sehir);
			$("#biyografi").val(profil.biyografi);
			$("#kullanici_adi").val(profil.kullanici_adi);
			$("#sifre").val(profil.sifre);
			$("#eposta").val(profil.eposta);
			$("#adres").val(profil.adres);
			$("#meslek").val(profil.meslek);
			$("#dogum_tarihi").val(profil.dogum_tarihi);
			$("#yas").val(profil.yas);
			$("#telefon_numarasi").val(profil.telefon_numarasi);
			$("#ilgi_alani").val(profil.ilgi_alani);
			$("#profil_resmi_url").val(profil.profil_resmi);
			$("#web_adresi").val(profil.web_adresi);
		}
	};

	json.open('GET', 'api.php?cinsiyet=' + cinsiyet, true);
	json.send();

});

$(".modal-tamam").click(function(){
	$(this).parents(".modal").fadeOut(100);
});

$("#daha-ak").click(function(){
	if ($("#daha").css('display') == 'none') {
		$("#daha").slideDown( "fast", function() {
			$("#daha-ak").removeClass("down");
			$("#daha-ak").addClass("up");
			$("#daha-ak").text("DETAYLARI GİZLE");
		});
	} else {
		$("#daha").slideUp( "fast", function() {
			$("#daha-ak").removeClass("up");
			$("#daha-ak").addClass("down");
			$("#daha-ak").text("DAHA FAZLA DETAY...");
		});
	}
});
