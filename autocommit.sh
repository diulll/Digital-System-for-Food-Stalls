#!/bin/bash

# Auto-commit script for Digital System for Food Stalls
# Commits each change one by one

echo "🚀 Starting auto-commit process..."
echo "=================================="

# 1. CashierController - Add getCategories API
echo "📝 Committing CashierController..."
git add app/Http/Controllers/CashierController.php
git commit -m "feat(cashier): add getCategories API endpoint for POS dynamic loading"

# 2. DashboardController - Add today's statistics
echo "📝 Committing DashboardController..."
git add app/Http/Controllers/DashboardController.php
git commit -m "feat(dashboard): add today's statistics (orders, transactions, income)"

# 3. MenuController - Add image upload handling
echo "📝 Committing MenuController..."
git add app/Http/Controllers/MenuController.php
git commit -m "feat(menu): add image upload, update, and delete handling"

# 4. MenuRequest - Add image validation
echo "📝 Committing MenuRequest..."
git add app/Http/Requests/MenuRequest.php
git commit -m "feat(validation): add image validation rules for menu"

# 5. Transaction Model - Fix fillable fields
echo "📝 Committing Transaction Model..."
git add app/Models/Transaction.php
git commit -m "fix(model): update Transaction fillable fields to match database schema"

# 6. Routes - Add categories API endpoint
echo "📝 Committing Routes..."
git add routes/web.php
git commit -m "feat(routes): add cashier categories API route"

# 7. Dashboard View - Add today's statistics card
echo "📝 Committing Dashboard View..."
git add resources/views/dashboard.blade.php
git commit -m "feat(ui): add today's statistics summary card to admin dashboard"

# 8. POS View - Add auto-refresh and dynamic menu loading
echo "📝 Committing POS View..."
git add resources/views/cashier/pos.blade.php
git commit -m "feat(pos): add auto-refresh menus, dynamic categories, redirect on close"

# 9. Menu Create View - Add image upload field
echo "📝 Committing Menu Create View..."
git add resources/views/menus/create.blade.php
git commit -m "feat(ui): add image upload field to menu create form"

# 10. Menu Edit View - Add image upload field with preview
echo "📝 Committing Menu Edit View..."
git add resources/views/menus/edit.blade.php
git commit -m "feat(ui): add image upload field with preview to menu edit form"

# 11. Menu Index View - Add image column
echo "📝 Committing Menu Index View..."
git add resources/views/menus/index.blade.php
git commit -m "feat(ui): add image column to menu list table"

# 12. Menu Show View - Add image display
echo "📝 Committing Menu Show View..."
git add resources/views/menus/show.blade.php
git commit -m "feat(ui): add image display to menu detail page"

# 13. Migration - Add image column to menus
echo "📝 Committing Migration (add image)..."
git add database/migrations/2026_01_11_055611_add_image_to_menus_table.php
git commit -m "migration: add image column to menus table"

# 14. Migration - Add is_available column to menus
echo "📝 Committing Migration (add is_available)..."
git add database/migrations/2026_01_11_062548_add_is_available_to_menus_table.php
git commit -m "migration: add is_available column to menus table"

# 15. Empty migrations (cleanup)
echo "📝 Committing empty migrations..."
git add database/migrations/2026_01_11_055604_add_image_to_menus_table.php
git add database/migrations/2026_01_11_062541_add_is_available_to_menus_table.php
git commit -m "chore: add empty migration files (placeholder)"

echo ""
echo "=================================="
echo "✅ All commits completed!"
echo ""
echo "📊 Summary:"
git log --oneline -15
echo ""
echo "🔄 To push to remote, run: git push origin main"
