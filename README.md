## Laravel Import Excel

## Daftar Isi
- [Laravel Import Excel](#laravel-import-excel)
- [Daftar Isi](#daftar-isi)
- [Instalasi](#instalasi)
- [Resources](#resources)
- [Copyright](#copyright)

## Instalasi
1. Install Composer
```
composer install
```
2. Install NPM Package
```
npm install
```
3. Copy Environment
```
cp .env.example .env
```
4. Generate Key
```
php artisan key:generate
```
5. Migrate Database
```
php artisan migrate
```
5. Start Queue
```
php artisan queue:work
```
6. Start Laravel
```
php artisan serve
```
7. Start Vite
```
npm run dev
```
8. Buka Route /users untuk menampilkan halaman import user
9. Untuk API Key bisa membuat pada [Pusher](https://pusher.com/) yang nanti akan dimasukkan ke dalam file .env. Contoh :
    
```
PUSHER_APP_ID=pusherappid
PUSHER_APP_KEY=pusherappkey
PUSHER_APP_SECRET=pusherappsecret
PUSHER_HOST=host
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

## Resources
- Laravel [Laravel](https://laravel.com/docs/10.x/installation)
- Laravel Excel [Laravel Excel](https://docs.laravel-excel.com/3.1/getting-started/)
- Pusher [Pusher](https://pusher.com/)

## Copyright
2023 [Yoga Meleniawan Pamungkas](https://github.com/yogameleniawan)   
