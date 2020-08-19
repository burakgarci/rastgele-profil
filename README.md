## Hakkında

Rastgele Profil Oluşturucu tasarımcılar için rastgele profil bilgileri oluşturmaya yarayan basit bir araçtır. [Burak Garcı](http://burakgarci.net) tarafından geliştirilmiştir.

- Profil fotoğrafları [unsplash.com](http://unsplash.com) sitesi üzerinde **CC0 1.0 Evrensel** lisansı altında yayınlanan fotoğraflardan seçilmiştir.
- [burakgarci.net/iletisim](http://burakgarci.net/iletisim) adresinden geribildirimde bulunabilirsiniz.

- Bir şeyler ısmarlak ister misiniz? [Evet](http://paypal.me/garci) - [Hayır](https://google.com.tr/search?q=isteyenin+bir+yüzü+vermeyenin+iki+yüzü+kara)

## API

İstek limiti yoktur, bir defada en fazla 200 farklı kullanıcı profili oluşturabilirsiniz. Çıktı JSON formatındadır.

### Kullanım

	http://rp.burakgarci.net/api.php

**Çıktı:**

	{
	    "isim": "Metin",
	    "soyisim": "Yüksel",
	    "tam_isim": "Metin Yüksel",
	    "cinsiyet": "erkek",
	    "sehir": "Zile, Tokat",
	    "adres": "Çalıkuşu Sokağı No:826 Zile, Tokat",
	    "meslek": "Kurumsal Web İletişim Uzmanı",
	    "eposta": "yukselmetin239@example.com",
	    "kullanici_adi": "metin279",
	    "sifre": "qLJaNm89EI",
	    "dogum_tarihi": "19.07.1988",
	    "yas": "27",
	    "telefon_numarasi": "+905392037096",
	    "ilgi_alani": "Bilardo meraklısı",
	    "biyografi": "27 yaşında, Kurumsal Web İletişim Uzmanı",
	    "profil_resmi": "http://res.cloudinary.com/brk/image/upload/erkek/93.jpg",
	    "web_adresi": "yukselmetin.net"
	}

### Parametreler

**Cinsiyet seçerek oluşturma (kadın, erkek):**

	http://rp.burakgarci.net/api.php?cinsiyet=kadın

**Birden fazla profil oluşturma (en fazla 200):**

	http://rp.burakgarci.net/api.php?adet=20