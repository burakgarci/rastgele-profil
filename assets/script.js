$("#hakkinda-gg").click(function(){
	$("#api").hide()
	$("#hakkinda").fadeToggle(100)
})

$("#api-gg").click(function(){
	$("#hakkinda").hide()
	$("#api").fadeToggle(100)
})

$("header button").click(function(){
	$('button.aktif').not(this).removeClass('aktif')
	$(this).addClass("aktif")

	var cinsiyet = $(this).attr("data-cinsiyet").replace("i","ı")
	var json = new XMLHttpRequest()

	json.onreadystatechange = function() {
		if (json.readyState == 4 && json.status == 200) {
			var profil = JSON.parse(json.responseText)
			$("#profil_resmi").attr("src", profil.profil_resmi)
			$("#tam_isim").val(profil.tam_isim)
			$("#sehir").val(profil.sehir)
			$("#biyografi").val(profil.biyografi)
			$("#kullanici_adi").val(profil.kullanici_adi)
			$("#sifre").val(profil.sifre)
			$("#eposta").val(profil.eposta)
		}
	}

	json.open('GET', 'api.php?cinsiyet=' + cinsiyet, true)
	json.send()

})

$(".modal-tamam").click(function(){
	$(this).parents(".modal").fadeOut(100);
})