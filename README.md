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

Laravel Breeze a été utilisé pour fournir une base d’authentification sécurisée (login, register, sessions), afin de se concentrer sur la logique métier du projet plutôt que sur du code boilerplate.

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
