<?php

$_['heading_title'] = '<strong>DNK &raquo; </strong>Przelewy24';
$_['text_dnk_przelewy24'] = '<a href="//anonym.to/?https://www.przelewy24.pl/" title="Przelewy24" target="_blank"><img src="view/image/payment/dnk_przelewy24.png" alt="Przelewy24"></a>';

$_['heading_title2'] = 'DNK::Przelewy24';
$_['text_payment'] = 'Płatności';

$_['topMessagesError'] = 'Ustawienia nie zostały zapisane. W konfiguracji znaleziono błędy, zostały zaznaczone poniżej.';
$_['topMessagesSuccess'] = 'Ustawienia zostały zapisane!';


$_['PanelStores'] = 'Konfiguracja dla multistore';
	$_['PanelStores_current'] = 'Bieżący Sklep:';
	$_['PanelStores_currentLong'] = 'Aktualnie edytujesz konfigurację dla Sklepu:';
	$_['PanelStores_select'] = 'Wybierz sklep z listy:';
	$_['PanelStores_submit'] = 'Zmień sklep &raquo;';

$_['PanelBasic'] = 'Ustawienia podstawowe';
	$_['p24id'] = 'Identyfikator klienta:';
	$_['payment_mode'] = 'Tryb pracy płatności';
	$_['payment_mode1'] = 'Produkcyjny';
	$_['payment_mode2'] = 'Testowy POPRAWNY';
	$_['payment_mode3'] = 'Testowy BŁĘDNY';
	$_['geo_zone'] = 'Strefa';
	$_['sms_mode'] = 'Tryb SMS Premium (P24 KOD)';

$_['PanelSms'] = 'Opcje trybu SMS Premium (P24 KOD)';
	$_['sms_mode_tip'] = 'W Trybie SMS Premium wartość zamówienia przestaje mieć znaczenie, weryfikacja płatności odbywa się wyłącznie na podstawie poprawności kodu SMS!';
	$_['sms_mode_dp'] = 'Kod tekstowy do wysyłania (z prefiksem <strong>DP</strong>)';
	$_['sms_mode_number'] = 'Twój numer SMS Premium';
	$_['sms_mode_price'] = 'Koszt SMS';
	$_['sms_price'] = '50 gr (62 gr z VAT)|1 zł (1,23 zł z VAT)|2 zł (2,46 zł z VAT)|3 zł (3,69 zł z VAT)|4 zł (4,92 zł z VAT)|5 zł (6,15 zł z VAT)|6 zł (7,38 zł z VAT)|7 zł (8,61 zł z VAT)|8 zł (9,84 zł z VAT)|9 zł (11,07 zł z VAT)|14 zł (17,22 zł z VAT)|19 zł (23,37 zł z VAT)|25 zł (30,75 zł z VAT)';
	$_['sms_mode_text'] = 'Dodatkowy tekst dla Klienta';

$_['PanelOrderStatuses'] = 'Statusy transakcji';
	$_['order_status_sms0'] = 'Rozpoczęcie płatności SMS';
	$_['order_status_sms1'] = 'Zakończenie płatności SMS';
	$_['order_status0'] = 'Po przejściu do Przelewy24';
	$_['order_status1'] = 'Po udanej realizacji płatności';
	$_['order_status2'] = 'Po nieudanej realizacji płatności';

$_['PanelCurrencies'] = 'Waluty';
	$_['PanelCurrencies_error'] = 'Uwaga, skrypt nie znalazł w systemie waluty PLN! Transakcje w Przelewy24 odbywają się jedynie w tej walucie. Dodaj tę walutę lub skorzystaj z automatyczneo przeliczania walut na podstawie <a href="//anonym.to/http://www.nbp.pl/kursy/kursya.html" target="_blank" rel="nofollow">Tabeli A kursów średnich walut obcych</a> NBP. <strong>Nie zapomnij zmienić tej opcji po zainstalowaniu nowej waluty!</strong>';
	$_['PanelCurrencies_success'] = '<strong>Pamiętaj! Wszystkie zamówienia będą opłacane w PLN, również te składane przez Klientów w innych walutach.</strong><br>Skrypt automatycznie przelicza walutę na podstawie kursu ustawionego w Opencart lub na podstawie <a href="//anonym.to/http://www.nbp.pl/kursy/kursya.html" target="_blank" rel="nofollow">Tabeli A kursów średnich walut obcych</a> NBP.';
	$_['currency_conversion_mode'] = 'Tryb przeliczania kwoty zamówienia na PLN';
	$_['currency_conversion_mode_tip'] = '<a href="//anonym.to/http://www.nbp.pl/kursy/kursya.html" target="_blank" rel="nofollow">Tabeli A kursów średnich walut obcych</a>. Nie zmieniaj tego adresu jeśli nie wiesz co robisz! Teoretycznie nigdy nie powinno być potrzeby jego zmiany.';
	$_['currency_conversion_mode0'] = 'Tabeli A kursów średnich walut obcych NBP';
	$_['currency_conversion_mode1'] = 'Wartości kursu ustawionej w Sklepie';
	$_['nbp_xml_url'] = 'URL aktualnej Tabeli A NBP';

$_['advPartTip'] = 'Poniżej znajdują się zaawansowane opcje, jeśli ich nie rozumiesz, nie musisz ich zmieniać by moduł działał poprawnie.';

$_['PanelPaymentMethods'] = 'Zarządzanie metodami płatności';
	$_['payment_methods_error'] = 'Nie udało się załadować listy metod płatności. Spróbuj odświeżyć zaczekać kilka minut i odśwież stronę. Jeśli problem będzie nie ustąpi skontaktuj się z Administratorem serwera lub Autorem modułu.';
	$_['payment_methods0'] = '1. Wszystkie dostępne';
	$_['payment_methods1'] = '2. Wymuś jedną metodę płatności';
	$_['payment_methods2'] = '3. Ogranicz do wybranych poniżej';
	$_['payment_methods_tip'] = 'Aktualną listę dostępnych metod płatności w Przelewy24, znajdziesz na <a rel="nofollow" target="_blank" href="//anonym.to/http://www.przelewy24.pl/cms,66,przelewy_kartyprintrzekazy.htm">stronie internetowej operatora</a>. 
	Jeśli uważasz, że poniższa lista metod płatności jest nieaktualna, możesz ją <button type="submit" name="updatePayments" class="btn btn-xs btn-warning">zaktualizować</button> w każdej chwili.';
	$_['payment_methods_selectable'] = 'Dostępne';
	$_['payment_methods_selection'] = 'Wybrane';
	$_['payment_methods2_default'] = '(3) Domyślnie zaznaczona metoda';
	$_['payment_method_last_user'] = '(3) Zapamiętuj ostatni wybór Klienta';
	$_['payment_method_fancy'] = '(3) Wygląd listy wyboru platności';
	$_['payment_method_fancy0'] = 'Prosta lista wyboru pod zamówieniem';
	$_['payment_method_fancy1'] = 'Graficzna lista wyboru na nowej stronie';
	$_['paymentsFancyPreview_prefix'] = 'Zobacz podgląd';
	$_['paymentsFancyPreview_suffix'] = 'graficznej listy wyboru płatności. <strong>Lista wybranych płatności odświeża się dopiero po zapisaniu konfiguracji!</strong><br>Liczba zamiast logo banku oznacza brak pliku graficznego, więcej informacji w Instrukcji Obsługi.';
	$_['payment_last_update'] = 'Ostatnia aktualizacja listy metod płatności:';

$_['PanelEmails'] = 'Powiadomienia e-mail o zmianach statusów zamówień';
	$_['email1_h'] = 'W poniższej sytuacji:';
	$_['email2_h'] = 'Powiadom Adminstratora?';
	$_['email3_h'] = 'Powiadom Klienta?';
	$_['email4_h'] = 'Treści wiadomości dla klientów';
	$_['email_title'] = 'Tytuł';
	$_['email_body'] = 'Treść';
	$_['email_placeholders'] = 'Zmienne dostępne w tytule i treści wiadomości';
	$_['email_tip_h1'] = 'Zmienna';
	$_['email_tip_h2'] = 'Opis';
	$_['email_tip_h3'] = 'Przykład';
	$_['email_tip_html'] = 'Uwaga! Kod HTML w wiadomościach jest niedozwolony i będzie usuwany. Przejścia do nowych linii będą zachowane.';

$_['PanelOther'] = 'Pozostałe opcje';
	$_['p24_language'] = 'Wymuszony język systemu Przelewy24';
	$_['p24_crc1'] = 'Weryfikuj klucz CRC <sup>2</sup>';
	$_['p24_crc2'] = 'Klucz CRC: <sup>3</sup>';
	$_['p24_crc1_tip1'] = '<sup>2</sup> Dodatkowa, nieobowiązkowa weryfikacja danych formularza przesyłanego do serwisu Przelewy24.<br>Ma to na celu weryfikację, czy  parametry wejściowe są prawidłowe i nie zostały zmodyfikowane.<br><strong>Wyłącz tą opcję jeśli płatności nie chcą działać.</strong>';
	$_['p24_crc1_tip2'] = '<sup>3</sup> <strong>Klucz CRC</strong> znajdziesz w <a rel="nofollow" target="_blank" href="//anonym.to/https://secure.przelewy24.pl/">Panelu Transakcyjnym</a>, w zakładce <code>Moje dane</code>.';

	$_['compatibility'] = 'Opcje kompatybilności:';
	$_['use_local_files'] = 'Użyj lokalnych plików graficznych/js/css, zamiast serwera CDN (pliki do pobrania na <a target="_blank" href="//opencart.dnk.net.pl/?rel=compatibility_cdn">opencart.dnk.net.pl</a>).';


// Max length: 32 chars
$_['new_order_status_sms0'] = 'Przelewy24: Płatn.SMS rozpoczęta';
$_['new_order_status_sms1'] = 'Przelewy24: Płatn.SMS zakończona';
$_['new_order_status0'] = 'Przelewy24: Płatność rozpoczęta';
$_['new_order_status1'] = 'Przelewy24: Płatność zakończona';
$_['new_order_status2'] = 'Przelewy24: Płatność nieudana!';


// default_email_1
$_['default_email_1_title'] = 'Zamówienie {order_id} oczekuje na płatność';
$_['default_email_1_body'] = 'Witaj {customer_first_name},
Twoje zamówienie właśnie zostało zarejestrowane i oczekuje na płatność z systemu Przelewy24.

--
Pozdrawiamy,
{store_title}

W razie pytań pisz na adres: {store_email}';

// default_email_2
$_['default_email_2_title'] = 'Zamówienie {order_id} zostało opłacone';
$_['default_email_2_body'] = 'Witaj {customer_first_name},
pomyślnie zarejestrowaliśmy płatność za zamówienie {order_id}.

Stan zamówienia możesz śledzić pod adresem: {order_url}

--
Pozdrawiamy,
{store_title}

W razie pytań pisz na adres: {store_email}';

// default_email_3
$_['default_email_3_title'] = 'Błąd w płatności za zamówienie {order_id}!';
$_['default_email_3_body'] = 'Witaj {customer_first_name},
nie udało się poprawnie zarejestrować płatności za zamówienie {order_id}.

Niezwłocznie skontaktuj się z obsługą sklepu, w celu wyjaśnienia sytuacji;
nasz adres email: {store_email} lub telefon: {store_telephone}

--
Pozdrawiamy,
{store_title}';

$_['admin_email_1_title'] = 'Nowe zamówienie z płatnością Przelewy24';
$_['admin_email_1_body'] = 'Klient {customer_first_name} {customer_last_name} złożył zamówienie nr {order_id}, na kwotę {order_total}.';
$_['admin_email_2_title'] = 'Pomyślna płatność Przelewy24 za zamówienie';
$_['admin_email_2_body'] = 'Płatność za zamówienie nr {order_id} została pomyślnie zarejestrowana przez Przelwy24.';
$_['admin_email_2_title'] = 'Błędna płatność Przelewy24 za zamówienie!';
$_['admin_email_2_body'] = 'Płatność za zamówienie nr {order_id} została zakończona z błędem!';


/**
 * verifySanitizeSettings
 */
$_['error_field_p24id'] = 'Identyfikator sprzedawcy musi składać się wyłącznie z 3-6 cyfr.';
$_['error_field_dnk_przelewy24_sort_order'] = 'Tylko cyfry większe lub równie 0 (zero).';
$_['error_field_sms_mode_dp'] = 'Nieprawidłowy kod';
$_['error_field_sms_mode_number'] = 'Nieprawidłowy numer';
$_['error_field_sms_mode_text'] = 'W tym polu tylko czysty tekst!';
$_['error_field_nbp_xml_url'] = 'Nieprawidłowy adres URL do zasobu';
$_['error_field_order_status_email'] = 'Użyto niedozwolonych znaków. Zostały usunięte.';
$_['error_field_p24_crc2'] = 'Pole musi zawierać 16 znaków alfanumerycznych lub wyłącz tą opcję.';


$_['config_save_info'] = 'Nie zapomnij na koniec zapisać wprowadzonych zmian!';
$_['error_payments_update'] = 'Nie udało się zaktualizować listy metod płatności; skrypt zwrócił błędy:';
$_['error_install'] = 'Z powodu powyższych błędów, nie udało się zainstalować modułu.';
$_['success_install'] = 'Moduł został pomyślnie zainstalowany!';
$_['success_update'] = 'Lista metod płatności została zaktualizowana!';
$_['success_reset'] = 'Ustawienia fabryczne zostały przywrócone!';
$_['config_save_warning'] = 'Wprowadzone zmiany nie zostaną zapisane. Czy chcesz kontynuować?';
$_['api_verion_info'] = 'Moduł zgony ze specyfikacją Systemu Przelewy24 wersja.2.64 data 2012-03-28 dla transakcji internetowych<br>oraz ze specyfikacją Systemu Przelewy24 wersja.2.83 data 2012-11-21 dla SMS Premium.';
$_['button_reverse'] = 'Przywróc ustawienia fabryczne';
$_['button_reverse_msg'] = 'Ta operacja jest nieodwracalna! Na pewno chcesz to zrobić?';
$_['file_status_0'] = 'brak pliku';
$_['file_status_1'] = 'znaleziono';


// PLACEHOLDERS
$_['dnk_p24_ph-store_url'] = 'Adres www sklepu klienta';
$_['dnk_p24_ph-store_name'] = 'Nazwa sklepu Klienta';
$_['dnk_p24_ph-store_title'] = 'Tytuł strony www ';
$_['dnk_p24_ph-store_owner'] = 'Właściciel sklepu';
$_['dnk_p24_ph-store_email'] = 'E-mail kontaktowy do sklepu';
$_['dnk_p24_ph-store_telephone'] = 'Telefon kontaktowy do sklepu';
$_['dnk_p24_ph-customer_first_name'] = 'Imię klienta';
$_['dnk_p24_ph-customer_last_name'] = 'Nazwisko klienta';
$_['dnk_p24_ph-customer_email'] = 'E-mail klienta';
$_['dnk_p24_ph-customer_telephone'] = 'Numer telefonu klienta';
$_['dnk_p24_ph-order_id'] = 'Numer zamówienia';
$_['dnk_p24_ph-order_url'] = 'Link do zamówienia dla klienta';
$_['dnk_p24_ph-order_total'] = 'Wartość zamówienia w walucie klienta';
$_['dnk_p24_ph-order_old_status'] = 'Poprzedni status zamówienia (o ile był)';
$_['dnk_p24_ph-order_new_status'] = 'Nowy status zamówienia';

