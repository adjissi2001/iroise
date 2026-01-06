# 🔐 Migration vers Laravel Breeze - Instructions

## ✅ Modifications effectuées

### 1. **Nettoyage de l'ancien système**
- ❌ Supprimé `AutoController.php` (authentification personnalisée)
- ❌ Supprimé `RegisterController.php` (doublon avec Breeze)
- ✅ Modifié `AdminController.php` pour utiliser l'authentification Breeze
- ✅ Nettoyé `bienvenue.blade.php` (formulaire de connexion retiré)
- ✅ Mis à jour `layouts/haut.blade.php` avec déconnexion Breeze

### 2. **Configuration des routes** ([routes/web.php](routes/web.php))
```php
✅ Page d'accueil publique : /bienvenue
✅ Dashboard : /dashboard (auth requis)
✅ Profil : /profile/* (auth requis)
✅ Bénéficiaires : /beneficiaires/* (auth requis)
✅ Administration : /administration (auth requis)
```

### 3. **Système de rôles**
- ✅ Middleware `admin` créé ([app/Http/Middleware/EnsureUserIsAdmin.php](app/Http/Middleware/EnsureUserIsAdmin.php))
- ✅ Migration `is_admin` ajoutée à la table users
- ✅ Modèle User mis à jour
- ✅ Seeder AdminUserSeeder créé

---

## 🚀 Étapes à suivre pour finaliser

### **Étape 1 : Exécuter les migrations**
```bash
php artisan migrate
```

### **Étape 2 : Créer l'utilisateur admin**
```bash
php artisan db:seed --class=AdminUserSeeder
```

**Identifiants admin par défaut :**
- Email : `admin@iroise.fr`
- Mot de passe : `admin123`

### **Étape 3 : Tester l'authentification**

1. Accédez à `/bienvenue` ou `/`
2. Cliquez sur "Connexion"
3. Connectez-vous avec : `admin@iroise.fr` / `admin123`
4. Vous devriez être redirigé vers `/dashboard`

### **Étape 4 : Tester l'interface admin**

Accédez à `/administration` pour voir la liste des bénéficiaires.

---

## 🔧 Utilisation du middleware admin (optionnel)

Si vous voulez que **certaines routes soient réservées aux admins uniquement**, modifiez [routes/web.php](routes/web.php) :

```php
// Protéger l'administration avec le middleware admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/administration', [AdminController::class, 'selectBeneficiaires'])->name('admin.beneficiaires');
});

// Routes accessibles à tous les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    Route::get('/beneficiaires', [BeneficiaireController::class, 'index'])->name('beneficiaire.index');
    // ...
});
```

---

## 📋 Structure finale

### **Authentification Breeze**
- ✅ Connexion : `/login`
- ✅ Inscription : `/register`
- ✅ Mot de passe oublié : `/forgot-password`
- ✅ Déconnexion : `POST /logout`

### **Routes applicatives**
- ✅ Accueil : `/` ou `/bienvenue`
- ✅ Dashboard : `/dashboard`
- ✅ Profil : `/profile`
- ✅ Bénéficiaires : `/beneficiaires`
- ✅ Administration : `/administration`

---

## 🎯 Prochaines étapes recommandées

1. **Créer plus d'utilisateurs** via `/register`
2. **Définir qui est admin** :
   - Manuellement dans la base de données
   - Via un seeder
   - Via une interface d'administration

3. **Créer la migration pour la table `beneficiaire`** :
```bash
php artisan make:migration create_beneficiaire_table
```

4. **Améliorer la sécurité** :
   - Changer le mot de passe admin par défaut
   - Ajouter des validations de formulaire
   - Implémenter la vérification email

5. **Personnaliser les vues Breeze** dans `resources/views/auth/`

---

## ❓ FAQ

### Comment rendre un utilisateur admin ?

**Option 1 : Via la base de données**
```sql
UPDATE users SET is_admin = 1 WHERE email = 'user@example.com';
```

**Option 2 : Via Tinker**
```bash
php artisan tinker
>>> $user = User::where('email', 'user@example.com')->first();
>>> $user->is_admin = true;
>>> $user->save();
```

### Comment désactiver la protection admin ?

Dans [routes/web.php](routes/web.php), utilisez uniquement `middleware('auth')` au lieu de `middleware(['auth', 'admin'])`.

---

## 🔗 Ressources

- [Documentation Laravel Breeze](https://laravel.com/docs/12.x/starter-kits#laravel-breeze)
- [Documentation Middleware](https://laravel.com/docs/12.x/middleware)
- [Documentation Authentication](https://laravel.com/docs/12.x/authentication)

---

**✨ Migration terminée ! Votre application utilise maintenant Laravel Breeze pour l'authentification.**
