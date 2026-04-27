# Programim-Ueb-Serveri-MiniShop

## Përshkrimi i Projektit
Ky projekt implementon një aplikacion web të ndërtuar me PHP (Faza I), duke u fokusuar në logjikën server-side, programimin e orientuar në objekte (OOP), dhe menaxhimin e sesioneve pa përdorimin e një databaze në këtë fazë.

Aplikacioni mundëson qasje të ndryshme për përdoruesit në varësi të rolit të tyre (ADMIN dhe USER) duke përdorur të dhëna statike.

## Qëllimi i Projektit
Ndërtimi i një strukture me include/require.
Implementimi i logjikës së autentikimit (Login/Logout) me Sessions.
Përdorimi i konceptit OOP (Klasat, Enkapsulimi, Trashëgimia).
Validimi i të dhënave në server përmes RegEx.
Menaxhimi i preferencave përmes Cookies.

## Siguria dhe Privilegjet
Vetëm përdoruesit e autentikuar kanë qasje në faqet e mbrojtura.
ADMIN ka privilegje për të parë vegla menaxhimi që USER-i i thjeshtë nuk i sheh.

## Struktura e projektit 
project/

index.php

login.php

logout.php

register.php

products.php

classes/ (product.php, user.php)

data/ (products.php, users.php)

includes/ (header.php, footer.php, nav.php)

README.md

## Si të ekzekutohet
1. Startoni Apache (përmes XAMPP ).
2. Vendosni projektin në folderin "htdocs".
3. Hapni browser-in: localhost/minishop/index.php

## Punuar nga
* Olta Morina
* Jola Shala
* Mirena Haliti
* Leonora Haxhijaj
* Aurela Kajtazi