# MediTime

MediTime est une application web développée avec **Laravel**, inspirée de Doctolib, permettant la prise de rendez-vous médicaux entre patients et médecins.

Ce projet est réalisé dans le cadre d’un **projet universitaire Laravel**.

---

## ✨ Fonctionnalités

- Inscription / Connexion des utilisateurs
- Authentification sécurisée (Laravel Breeze)
- Accès protégé aux fonctionnalités selon l’état de connexion
- Gestion des utilisateurs (patients / médecins)
- Base de données MySQL avec migrations Laravel

---

## 🛠️ Technologies utilisées

- **Laravel 12**
- **PHP 8.2**
- **MySQL**
- **Blade**
- **Laravel Breeze**

---

## 🚀 Installation (local)

```bash
git clone https://github.com/Amu2ler/MediTime.git
cd MediTime
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
