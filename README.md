# currency-checker

Zadanie testowe.

Należy utworzyć bazę danych (testowane na PostgreSQL), wykonać `composer install` i można testować. Domyślnie do bazy zostaną dodani 2 userzy (`permitted@mail.com` i `forbidden@mail.com`, obaj z hasłem `password`).

## Endpointy:
- `POST /api/login_check` - logowanie (trzeba podać 'username' i 'password')
- `POST /api/currency` - dodanie nowej waluty. Domyślna data kursu waluty jest ustawiona na obecny dzień, ale opcjonalnie można podać inną
- `GET /api/currency/{date}/{currency?}` - pobranie kursów walut/waluty. Data jest podawana w formacie 'YYYY-MM-DD'

