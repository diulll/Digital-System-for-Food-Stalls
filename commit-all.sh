#!/bin/bash

# Commit script for Digital System for Food Stalls
# This script commits each change one by one

echo "Starting commit process..."

# 1. Commit Breeze Auth Controllers
git add app/Http/Controllers/Auth/
git commit -m "feat: Add Breeze authentication controllers"

# 2. Commit Profile Controller
git add app/Http/Controllers/ProfileController.php
git commit -m "feat: Add ProfileController for user profile management"

# 3. Commit Form Requests
git add app/Http/Requests/
git commit -m "feat: Add form request validation classes"

# 4. Commit View Components
git add app/View/
git commit -m "feat: Add Blade view components"

# 5. Commit Auth Views (login, register, etc)
git add resources/views/auth/
git commit -m "feat: Add authentication views (login, register, forgot password)"

# 6. Commit Blade Components
git add resources/views/components/
git commit -m "feat: Add reusable Blade UI components"

# 7. Commit Dashboard View
git add resources/views/dashboard.blade.php
git commit -m "feat: Add dashboard view"

# 8. Commit Layout Views
git add resources/views/layouts/
git commit -m "feat: Add layout templates (app, guest, navigation)"

# 9. Commit Profile Views
git add resources/views/profile/
git commit -m "feat: Add profile management views"

# 10. Commit Auth Routes
git add routes/auth.php
git commit -m "feat: Add authentication routes"

# 11. Commit Web Routes (redirect to login/register)
git add routes/web.php
git commit -m "feat: Update web routes - redirect home to register"

# 12. Commit Auth Tests
git add tests/Feature/Auth/
git commit -m "test: Add authentication feature tests"

# 13. Commit Profile Test
git add tests/Feature/ProfileTest.php
git commit -m "test: Add profile feature test"

# 14. Commit Tailwind Config
git add tailwind.config.js
git commit -m "config: Add Tailwind CSS configuration"

# 15. Commit PostCSS Config
git add postcss.config.js
git commit -m "config: Add PostCSS configuration"

# 16. Commit Vite Config
git add vite.config.js
git commit -m "config: Update Vite configuration for Breeze"

# 17. Commit CSS updates
git add resources/css/app.css
git commit -m "style: Update app.css with Tailwind imports"

# 18. Commit JS updates
git add resources/js/app.js
git commit -m "feat: Update app.js for Breeze"

# 19. Commit package.json
git add package.json
git commit -m "chore: Update package.json with Breeze dependencies"

# 20. Commit package-lock.json
git add package-lock.json
git commit -m "chore: Add package-lock.json"

# 21. Commit composer.json
git add composer.json
git commit -m "chore: Update composer.json with Breeze dependency"

# 22. Commit composer.lock
git add composer.lock
git commit -m "chore: Update composer.lock"

echo "All commits completed!"
echo "Pushing to GitHub..."

# Push all commits to GitHub
git push origin main

echo "Done! All changes pushed to GitHub."
